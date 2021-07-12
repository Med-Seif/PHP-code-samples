<?php
/**
 * User FBOU
 * Date time: 08/10/2019
 */

namespace Gta\Domain\Service;

use Gta\CoreBundle\Repository\UfRepository;
use Gta\Domain\Mapping\Mapper\DeplacementAndExpMapper;
use Gta\MedicalBundle\Repository\DeplacementExceptionnelRepository;
use Gta\MedicalBundle\Repository\EtatCivilRepository;
use Gta\MedicalBundle\Repository\GmdpxRepository;
use Gta\MedicalBundle\Repository\GmesecRepository;
use Gta\MedicalBundle\Repository\GmplsRepository;

/**
 * Class DeplacementService
 *
 * @package Gta\MedicalBundle\Service
 * @author  FBOU
 * @version 19
 */
class DeplacementExceptionnelService extends AbstractService
{
    /**
     * @var \Gta\MedicalBundle\Repository\GmplsRepository
     */
    private $gmplsRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\DeplacementRepository
     */
    private $deplacementExceptionnelRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\GmcdpRepository
     */
    private $gmdpxRepository;
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
     * @param \Gta\MedicalBundle\Repository\DeplacementExceptionnelRepository $deplacementExceptionnelRepository
     * @param \Gta\MedicalBundle\Repository\GmdpxRepository       $gmdpxRepository
     * @param \Gta\MedicalBundle\Repository\EtatCivilRepository   $etatCivilRepository
     * @param \Gta\CoreBundle\Repository\UfRepository             $ufRepository
     * @param \Gta\MedicalBundle\Repository\GmesecRepository      $gmesecRepository
     * @param \Gta\Domain\Mapping\DataMapper                      $dataMapper
     */
    public function __construct(
        GmplsRepository $gmplsRepository,
        DeplacementExceptionnelRepository $deplacementExceptionnelRepository,
        GmdpxRepository $gmdpxRepository,
        EtatCivilRepository $etatCivilRepository,
        UfRepository $ufRepository,
        GmesecRepository $gmesecRepository,
        \Gta\Domain\Mapping\DataMapper $dataMapper
    ) {
        $this->gmplsRepository = $gmplsRepository;
        $this->deplacementExceptionnelRepository = $deplacementExceptionnelRepository;
        $this->gmdpxRepository = $gmdpxRepository;
        $this->etatCivilRepository = $etatCivilRepository;
        $this->ufRepository = $ufRepository;
        $this->gmesecRepository = $gmesecRepository;
        $this->dataMapper = $dataMapper;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @author FBOU
     */
    public function getData()
    {
        $data = [
            'ast'  => $this->gmplsRepository->findByTypeAct($this->getParamsArray()),
            'dep'  => $this->deplacementExceptionnelRepository->findAll($this->getParamsArray()),
            'valo' => $this->gmdpxRepository->findAll($this->getParamsArray()),
            // valorisation
            'int'  => $this->etatCivilRepository->findAll($this->getParamsArray()),
            'tuf'  => array_column($this->ufRepository->findAll($this->getParamsArray()), 'tuflibl', 'tufufon'),
            'serv' => $this->gmesecRepository->findAll($this->getParamsArray()),
        ];

        return $this->dataMapper->map($data, null, DeplacementAndExpMapper::class);

    }


}