<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 04/02/2020 16:07
 */

namespace Gta\Domain\Service;

use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\MedicalBundle\Mapper\ActiviteSoireeMapper;
use Gta\MedicalBundle\Mapper\DeplacementExceptionnelMapper;
use Gta\MedicalBundle\Mapper\DeplacementMapper;
use Gta\MedicalBundle\Repository\ActiviteSoireeRepository;
use Gta\MedicalBundle\Repository\DeplacementExceptionnelRepository;
use Gta\MedicalBundle\Repository\DeplacementRepository;
use Gta\MedicalBundle\Repository\GmplsRepository;
use Gta\MedicalBundle\Repository\GmservRepository;
use Gta\MedicalBundle\Service\TitleDataProvider;
use Gta\MedicalBundle\Service\TsCalendarProvider;
use Gta\MedicalBundle\Service\TsPlanningDetailProvider;
use Gta\MedicalBundle\Utils\Control\StateOfServiceControl;

/**
 * Class TsCollService
 *
 * @package Gta\Domain\Service
 * @author  Seif <ben.s@mipih.fr> (04/02/2020/ 16:07)
 * @version 19
 */
class TsCollService
{
    /**
     * @var \Gta\MedicalBundle\Repository\DeplacementRepository
     */
    private $deplacementRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\DeplacementExceptionnelRepository
     */
    private $deplacementExceptionnelRepository;
    /**
     * @var \Gta\MedicalBundle\Repository\ActiviteSoireeRepository
     */
    private $activiteSoireeRepository;
    /**
     * @var \Gta\MedicalBundle\Service\TsPlanningDetailProvider
     */
    private $tsPlanningDetailProvider;
    /**
     * @var \Gta\MedicalBundle\Service\TitleDataProvider
     */
    private $titleDataProvider;
    /**
     * @var \Gta\MedicalBundle\Service\TsCalendarProvider
     */
    private $tsCalendarProvider;
    /**
     * @var \Gta\MedicalBundle\Utils\Control\StateOfServiceControl
     */
    private $stateOfServiceControl;
    /**
     * @var \Gta\CoreBundle\ParamConverter\MainFilter
     */
    private $mainFilter;
    /**
     * @var GmservRepository
     */
    private $gmservRepository;

    /**
     * TsCollService constructor.
     *
     * @param \Gta\MedicalBundle\Repository\DeplacementRepository $deplacementRepository
     * @param \Gta\MedicalBundle\Repository\DeplacementExceptionnelRepository $deplacementExceptionnelRepository
     * @param \Gta\MedicalBundle\Repository\GmplsRepository $gmplsRepository
     * @param \Gta\MedicalBundle\Repository\ActiviteSoireeRepository $activiteSoireeRepository
     * @param \Gta\MedicalBundle\Service\TsPlanningDetailProvider $tsPlanningDetailProvider
     * @param \Gta\MedicalBundle\Service\TitleDataProvider $titleDataProvider
     * @param \Gta\MedicalBundle\Service\TsCalendarProvider $tsCalendarProvider
     * @param \Gta\MedicalBundle\Utils\Control\StateOfServiceControl $stateOfServiceControl
     * @param GmservRepository $gmservRepository
     * @param \Gta\CoreBundle\ParamConverter\MainFilter $mainFilter
     */
    public function __construct(
        DeplacementRepository $deplacementRepository,
        DeplacementExceptionnelRepository $deplacementExceptionnelRepository,
        GmplsRepository $gmplsRepository,
        ActiviteSoireeRepository $activiteSoireeRepository,
        TsPlanningDetailProvider $tsPlanningDetailProvider,
        TitleDataProvider $titleDataProvider,
        TsCalendarProvider $tsCalendarProvider,
        StateOfServiceControl $stateOfServiceControl,
        GmservRepository $gmservRepository,
        MainFilter $mainFilter
    ) {

        $this->deplacementRepository = $deplacementRepository;
        $this->deplacementExceptionnelRepository = $deplacementExceptionnelRepository;
        $this->gmplsRepository = $gmplsRepository;
        $this->activiteSoireeRepository = $activiteSoireeRepository;
        $this->tsPlanningDetailProvider = $tsPlanningDetailProvider;
        $this->titleDataProvider = $titleDataProvider;
        $this->tsCalendarProvider = $tsCalendarProvider;
        $this->stateOfServiceControl = $stateOfServiceControl;
        $this->mainFilter = $mainFilter;
        $this->gmservRepository = $gmservRepository;
    }

    /**
     * @param $dateff
     * @param $matric
     *
     * @return array
     * @throws \Doctrine\DBAL\Cache\CacheException
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadData($dateff, $matric)
    {
        $params = $this->mainFilter
            ->setDatdeb($dateff)
            ->setDatfin($dateff)
            ->setMatric($matric)
            ->toArray();

        $paramsDeplacement = [
            'codhop'    => $this->mainFilter->getCodhop(),
            'coddif'    => $this->mainFilter->getCoddif(),
            'mf_datdeb' => $dateff,
            'mf_datfin' => $dateff,
        ];

        return [
            'act'     => $this->tsPlanningDetailProvider->provideByMatricByDate($params, $matric),
            'dep'     => DeplacementMapper::mapData(
                $this->deplacementRepository->findByMatric($paramsDeplacement, $matric),
                $this->mainFilter->getService()
            ),
            'depx'    => DeplacementExceptionnelMapper::mapData(
                $this->deplacementExceptionnelRepository->findByMatric($paramsDeplacement, $matric)
            ),
            'soi'     => ActiviteSoireeMapper::mapData($this->activiteSoireeRepository->findByMatric($params)),
            'setting' => $this->tsCalendarProvider->provide($params, $dateff, false, TsCalendarProvider::MODE_DETAIL),
            'rightDep' => $this->gmplsRepository->getCountAstreinte($params)[0] > 0 && !$this->gmservRepository->serviceIsContinuOrUrgentiste($params),
            'right'   => $this->stateOfServiceControl->canUpdate($params),
        ];
    }

    /**
     * @param $matric
     * @param $dateff
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function getTitle($matric, $dateff)
    {
        return array('title' => $this->titleDataProvider->provide('Détail de la journée', $matric, $dateff));
    }
}