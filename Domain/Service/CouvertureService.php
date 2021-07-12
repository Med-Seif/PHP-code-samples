<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/06/2019 16:18
 */

namespace Gta\Domain\Service;


use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\Domain\Exception\MissingCouvertureException;
use Gta\MedicalBundle\Repository\GmcatRepository;
use Gta\MedicalBundle\Repository\GmcouvRepository;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants;
use Gta\TracabiliteBundle\Manager\TracabiliteManager;

/**
 * Class CouvertureService
 *
 * @package Gta\Domain\Service
 * @author  Seif <ben.s@mipih.fr> (26/06/2019/ 16:22)
 * @version 19
 */
class CouvertureService
{
    /**
     * @var \Gta\MedicalBundle\Repository\GmcouvRepository
     */
    private $gmcouvRepository;
    /**
     * @var \Gta\TracabiliteBundle\Manager\TracabiliteManager
     */
    private $tracabiliteManager;
    /**
     * @var \Gta\MedicalBundle\Repository\GmcatRepository
     */
    private $gmcatRepository;
    /**
     * @var \Gta\CoreBundle\ParamConverter\MainFilter
     */
    private $mainFilter;

    /**
     * CouvertureService constructor.
     *
     * @param \Gta\MedicalBundle\Repository\GmcouvRepository    $gmcouvRepository
     * @param \Gta\MedicalBundle\Repository\GmcatRepository     $gmcatRepository
     * @param \Gta\TracabiliteBundle\Manager\TracabiliteManager $tracabiliteManager
     * @param \Gta\CoreBundle\ParamConverter\MainFilter         $mainFilter
     */
    public function __construct(
        GmcouvRepository $gmcouvRepository,
        GmcatRepository $gmcatRepository,
        TracabiliteManager $tracabiliteManager,
        MainFilter $mainFilter
    ) {
        $this->gmcouvRepository = $gmcouvRepository;
        $this->gmcatRepository = $gmcatRepository;
        $this->tracabiliteManager = $tracabiliteManager;
        $this->mainFilter = $mainFilter;
    }

    /**
     * @param array $params
     *
     *
     * @return bool|\Exception
     * @throws \Doctrine\DBAL\ConnectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function saveAffectationCouvrant(array $params)
    {
        $actsen = function () use ($params) {
            if (empty($params['actsen'])) {
                return ' ';
            }
            $arrActsen = explode(' - ', $params['actsen']);
            if (empty($arrActsen[0])) {
                return ' ';
            }

            return $arrActsen[0];
        };
        // récupérer la catégorie
        $catData = $this->gmcatRepository->findByMatric(
            $this->mainFilter->toArray(),
            $params['matsen']
        );

        $params['catsen'] = count($catData) > 0 ? $catData[0]['gar'] : ' ';
        $params['codhop'] = $this->mainFilter->getCodhop();
        $params['utimaj'] = $this->mainFilter->getNomuti();
        $params['actsen'] = $actsen();
        // récupérer la couverture déjà existente avec les champs xxxSEN renseignés ( va nous servir dans la traçabilité)
        $rowCouvertureOld = $this->gmcouvRepository->getCouvrant($params);
        try {
            $this->gmcouvRepository->getDbConnection()->beginTransaction();

            // on supprime toujours la couverture (mettre à blanc la partie droite) puis affecter la nouvelle couverture
            if (false === $this->affectCouvrantCouverture($params)) {
                throw new MissingCouvertureException(404);
            }
            // récupérer la nouvelle couverture après update
            $rowCouvertureNew = $this->gmcouvRepository->getCouvrant($params);
            // lancer la tracabilité
            $this->traceCouverture($rowCouvertureOld, $rowCouvertureNew);

            $this->gmcouvRepository->getDbConnection()->commit();
        } catch (\Exception $e) {
            $this->gmcouvRepository->getDbConnection()->rollBack();

            return $e;
        }

        return true;
    }

    /**
     * @param mixed $rowCouvertureOld
     *
     * @param array $rowCouvertureNew
     *
     * @throws \Gta\TracabiliteBundle\Exception\MissingParamsException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerConfigurationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerException
     * @throws \Gta\TracabiliteBundle\Exception\UndefinedCodeFonctionnaliteException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    private function traceCouverture($rowCouvertureOld, $rowCouvertureNew)
    {
        if (!empty($rowCouvertureOld)) {
            // s'il y a déjà un couvrant, on trace sa suppression
            $this->tracabiliteManager->tracabiliteDispatch(
                TracabiliteConstants::UC_COUVERTURE_COUVRANT_AFFECT_SUPP,
                $rowCouvertureOld
            );
        }
        // tracer le nouveau couvrant
        if (!empty($rowCouvertureNew)) {
            $this->tracabiliteManager->tracabiliteDispatch(
                TracabiliteConstants::UC_COUVERTURE_COUVRANT_AFFECT_ADD,
                $rowCouvertureNew
            );
        }
    }

    /**
     * @param array $params
     *
     * @param bool  $cleanBeforeUpdate
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    private function affectCouvrantCouverture(array $params, $cleanBeforeUpdate = true)
    {
        $updatedRows = 0;
        if (true === $cleanBeforeUpdate) {
            $updatedRows = $this->gmcouvRepository->deleteCouvrantCouverture($params); // mettre à blanc
        }
        if (0 === $updatedRows) {
            // l'enregistrement n'existe pas puisqu'on a rien updaté
            return false;
        }
        $affectedRows = $this->gmcouvRepository->updateCouvrantCouverture(
            $params
        ); // affecter le couvrant (partie droite XXXSEN)
        if (0 === $affectedRows) {
            // l'enregistrement n'existe pas puisqu'on a rien updaté
            return false;
        }

        return $affectedRows;

    }
}