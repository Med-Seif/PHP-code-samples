<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 12/04/2019 18:56
 */

namespace Gta\Domain\Service;

use Gta\Domain\Mapping\DataMapper;
use Gta\CoreBundle\Repository\PdrRepository;
use Gta\Domain\Lib\Std;
use Gta\Domain\Mapping\Mapper\DroitCongeMapper;
use Gta\MedicalBundle\Repository\Pcg2Repository;
use Gta\MedicalBundle\Repository\CetRepository;
use Gta\Domain\Service\AbstractService;

/**
 * Class DroitCongeService
 *
 * @package Gta\MedicalBundle\Service
 * @author  Seif <ben.s@mipih.fr> (12/04/2019/ 19:01)
 * @version 19
 */
class DroitCongeService extends AbstractService
{
    /**
     * @var \Gta\CoreBundle\Repository\PdrRepository
     */
    private $pdrRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\CetRepository
     */
    private $cetRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\Pcg2Repository
     */
    private $pcg2Repository;
    /**
     * @var \Gta\Domain\Mapping\DataMapper
     */
    private $dataMapper;

    /**
     * DroitCongeService constructor.
     *
     * @param \Gta\CoreBundle\Repository\PdrRepository     $pdrRepository
     * @param \Gta\MedicalBundle\Repository\CetRepository  $cetRepository
     * @param \Gta\MedicalBundle\Repository\Pcg2Repository $pcg2Repository
     * @param \Gta\Domain\Mapping\DataMapper               $dataMapper
     */
    public function __construct(PdrRepository $pdrRepository, CetRepository  $cetRepository, Pcg2Repository $pcg2Repository, DataMapper $dataMapper)
    {
        $this->pdrRepository = $pdrRepository;
        $this->cetRepository = $cetRepository;
        $this->pcg2Repository = $pcg2Repository;
        $this->dataMapper = $dataMapper;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function getDroitsConges()
    {
        // notre main filter sous forme de tableau
        $arrParams = $this->getParams()->toArray();

        // IdData la clef primaire pour identifier chaque elemnet du tableau d'une mani??re unique et faire la correspondance entre les deux tableaux
        $idData = ['name', 'matric', 'position', 'motif', 'typpersconge'];
        // donn??es de base PDR
        $pdrData = $this->pdrRepository->findAll($arrParams);
        $pdrData = Std::arrayGroupByKey($pdrData, $idData);
        // donn??es de base CET (le package ne sait pas faire pour tout un TS d'un coup, il faut boucler sur les intervenants qui sont affect??s)
        $cetData = $this->cetRepository->findAll($arrParams);
        $cetData = Std::arrayGroupByKey($cetData, $idData);
        // donn??es compl??mentaire PCG2
        $pcg2Data = $this->pcg2Repository->findSum($arrParams);
        // indexer par clef primaire pour voir la m??me clef avec PDR (voir le $idPdr
        $pcg2Data = Std::arrayGroupByKey($pcg2Data, $idData);
        // variable de retour
        $result = [];

        // closure de calcul de cong??s pos??s
        $fCongesPoses = function ($idPdr, & $pcg2Data) {
            $val = null;
            if (isset($pcg2Data[$idPdr])) { // si l'intervenant ?? des cong??s pos??s
                $val = floatval($pcg2Data[$idPdr]['jours']);
                unset($pcg2Data[$idPdr]); // supprimer pour ne pas avoir des doublons lors du merge apr??s
            }

            return $val;
        };

        // go
        foreach ($pdrData as $idPdr => $rowPdrData) {
            $newRowPdr = $rowPdrData;
            $congesPoses = $fCongesPoses($idPdr, $pcg2Data); // passage par ref??rence du 2eme param??tre
            $newRowPdr['conge_pose'] = $congesPoses;
            $newRowPdr['reliquat'] = $rowPdrData['jours'] - $congesPoses - $rowPdrData['versement_jours'];
            $newRowPdr['is_pdr'] = true;
            $result[$idPdr] = $newRowPdr;
        }

        // modifier les el??ments de pcg2 pour les pr??parer au mapping : calcul de reliquat
        array_walk(
            $pcg2Data,
            function (&$item) {
                $item['reliquat'] = (in_array($item['position'], ['CEP', 'CET'])) ? 0 : - $item['jours'];
            }
        );

        // merger les deux
        $droitConges = array_merge($result, $pcg2Data,$cetData);

        // notez bien que $droitConges est la fusion, donc il faut trier le r??sultat
        ksort($droitConges);

        return $this->dataMapper->map($droitConges, $arrParams, DroitCongeMapper::class, ['rubcod' => 'TSTREG5']);
    }
}