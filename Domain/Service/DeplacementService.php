<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 25/04/2019 10:34
 */

namespace Gta\Domain\Service;

use Gta\CoreBundle\Repository\UfRepository;
use Gta\Domain\Mapping\Mapper\DeplacementAndExpMapper;
use Gta\MedicalBundle\Repository\DeplacementRepository;
use Gta\MedicalBundle\Repository\EtatCivilRepository;
use Gta\MedicalBundle\Repository\GmcdpRepository;
use Gta\MedicalBundle\Repository\GmesecRepository;
use Gta\MedicalBundle\Repository\GmplsRepository;

/**
 * Class DeplacementService
 *
 * @package Gta\MedicalBundle\Service
 * @author  Seif <ben.s@mipih.fr> (25/04/2019/ 10:34)
 * @version 19
 */
class DeplacementService extends AbstractService
{
    /**
     * @var \Gta\MedicalBundle\Repository\GmplsRepository
     */
    private $gmplsRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\DeplacementRepository
     */
    private $deplacementRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\GmcdpRepository
     */
    private $gmcdpRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\EtatCivilRepository
     */
    private $etatCivilRepository;
    /**
     * @var \Gta\CoreBundle\Repository\UfRepository
     */
    private $ufRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\GmesecRepository
     */
    private $gmesecRepository;
    /**
     * @var \Gta\Domain\Mapping\DataMapper
     */
    private $dataMapper;

    /**
     * DeplacementService constructor
     *
     * @param \Gta\MedicalBundle\Repository\GmplsRepository       $gmplsRepository
     * @param \Gta\MedicalBundle\Repository\DeplacementRepository $deplacementRepository
     * @param \Gta\MedicalBundle\Repository\GmcdpRepository       $gmcdpRepository
     * @param \Gta\MedicalBundle\Repository\EtatCivilRepository   $etatCivilRepository
     * @param \Gta\CoreBundle\Repository\UfRepository             $ufRepository
     * @param \Gta\MedicalBundle\Repository\GmesecRepository      $gmesecRepository
     * @param \Gta\Domain\Mapping\DataMapper                      $dataMapper
     */
    public function __construct(
        GmplsRepository $gmplsRepository,
        DeplacementRepository $deplacementRepository,
        GmcdpRepository $gmcdpRepository,
        EtatCivilRepository $etatCivilRepository,
        UfRepository $ufRepository,
        GmesecRepository $gmesecRepository,
        \Gta\Domain\Mapping\DataMapper $dataMapper
    ) {
        $this->gmplsRepository = $gmplsRepository;
        $this->deplacementRepository = $deplacementRepository;
        $this->gmcdpRepository = $gmcdpRepository;
        $this->etatCivilRepository = $etatCivilRepository;
        $this->ufRepository = $ufRepository;
        $this->gmesecRepository = $gmesecRepository;
        $this->dataMapper = $dataMapper;
    }

    /**
     * @return array
     * @throws \Gta\CoreBundle\Exception\Mapping\MissingRubriqueTNOException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function getData()
    {
        $data = [
            'ast'  => $this->gmplsRepository->findByTypeAct($this->getParamsArray()),
            'dep'  => $this->deplacementRepository->findAll($this->getParamsArray()),
            'valo' => $this->gmcdpRepository->findAll($this->getParamsArray()),
            // valorisation
            'int'  => $this->etatCivilRepository->findAll($this->getParamsArray()),
            'tuf'  => array_column($this->ufRepository->findAll($this->getParamsArray()), 'tuflibl', 'tufufon'),
            'serv' => $this->gmesecRepository->findAll($this->getParamsArray()),
        ];

        return $this->dataMapper->map($data, null, DeplacementAndExpMapper::class);

    }
    /**
     * @param $plsact
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getNature($plsact)
    {
        return (isset($plsact)) ? $plsact : ' '; # NDPNAT cannot be NULL
    }

    /**
     * @param $actdur
     * @param $typhor
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getNdptyp($actdur, $typhor)
    {
        if ('2' !== $actdur) {
            return $typhor;
        }
        if ('N1' == $typhor || 'N2' == $typhor) {
            return 'N1';
        }

        return 'AM';
    }

}