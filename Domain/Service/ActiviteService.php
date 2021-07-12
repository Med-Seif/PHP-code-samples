<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 10/04/2019 19:31
 */

namespace Gta\Domain\Service;

use Gta\CoreBundle\Repository\ColorRepository;
use Gta\CoreBundle\Repository\NomenclatureRepository;
use Gta\Domain\Lib\Std;
use Gta\MedicalBundle\Mapper\ActiviteMapper;
use Gta\MedicalBundle\Repository\ActiviteRepository;
use Gta\MedicalBundle\Repository\GmtriactRepository;

/**
 * Class ActiviteService
 *
 * @package Gta\MedicalBundle\Service
 * @author  Seif <ben.s@mipih.fr> (10/04/2019/ 19:32)
 * @version 19
 */
class ActiviteService
{

    const MODE_GENERAL = 'general';
    const MODE_MEDICAL = 'medical';
    /**
     * @var \Gta\CoreBundle\Repository\NomenclatureRepository
     */
    private $nomenclatureRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\ActiviteRepository
     */
    private $activiteRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\GmtriactRepository
     */
    private $gmtriactRepository;
    /**
     * @var \Gta\CoreBundle\Repository\ColorRepository
     */
    private $colorRepository;
    /**
     * @var array
     */
    private $params;

    /**
     * Activite constructor.
     *
     * @param \Gta\CoreBundle\Repository\NomenclatureRepository $nomenclatureRepository
     * @param \Gta\MedicalBundle\Repository\ActiviteRepository  $activiteRepository
     * @param \Gta\MedicalBundle\Repository\GmtriactRepository  $gmtriactRepository
     * @param \Gta\CoreBundle\Repository\ColorRepository        $colorRepository
     */
    public function __construct(
        NomenclatureRepository $nomenclatureRepository,
        ActiviteRepository $activiteRepository,
        GmtriactRepository $gmtriactRepository,
        ColorRepository $colorRepository
    ) {
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->activiteRepository = $activiteRepository;
        $this->gmtriactRepository = $gmtriactRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * @param $mainFilter
     * @param $droitMode
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setParams($mainFilter, $droitMode)
    {
        $this->params = array_merge($mainFilter, ['f_activites' => $droitMode]);
    }

    /**
     * @param string $mode
     *
     * @param null   $mainFilter
     * @param null   $droitMode
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadDataMapped($mode = self::MODE_GENERAL, $mainFilter = null, $droitMode = null)
    {
        if (null !== $mainFilter && null !== $droitMode) {
            $this->setParams($mainFilter, $droitMode);
        }

        return ActiviteMapper::mapData(
            $this->{'get'.ucfirst($mode).'Data'}(),
            $this->getColorData(),
            $this->getGmtriactData(),
            $this->getNomenclatureData()
        );
    }

    /**
     * @param null $mainFilter
     * @param null $droitMode
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadAllMappedData($mainFilter = null, $droitMode = null)
    {
        if (null !== $mainFilter && null !== $droitMode) {
            $this->setParams($mainFilter, $droitMode);
        }
        $colorData = $this->getColorData();
        $gmtriactData = $this->getGmtriactData();
        $nomenclatureData = $this->getNomenclatureData();
        $medicalData = $this->getMedicalData();
        $generalData = $this->getGeneralData();

        return array(
            self::MODE_MEDICAL =>
                ActiviteMapper::mapData(
                    $medicalData,
                    $colorData,
                    $gmtriactData,
                    $nomenclatureData
                ),
            self::MODE_GENERAL =>
                ActiviteMapper::mapData(
                    $generalData,
                    $colorData,
                    $gmtriactData,
                    $nomenclatureData
                ),
        );
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function getColorData()
    {
        return $this->colorRepository->findActivity($this->params);
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getMedicalData()
    {
        return $this->activiteRepository->findMedical($this->params);
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getGeneralData()
    {
        return $this->activiteRepository->findGeneral($this->params);
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getNomenclatureData()
    {
        $nomenclature = $this->nomenclatureRepository->findByRubcod($this->params, ['ACTTYP', 'PCGCPOS']);

        return [
            'acttyp'  => Std::arrayGroupBy($nomenclature['acttyp'], ['tnoval'], true, true),
            'pcgcpos' => Std::arrayGroupBy($nomenclature['pcgcpos'], ['tnokey'], true, true),
        ];
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getGmtriactData()
    {
        return $this->gmtriactRepository->findAll($this->params);
    }
}