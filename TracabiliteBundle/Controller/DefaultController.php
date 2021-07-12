<?php

namespace Gta\TracabiliteBundle\Controller;

use Gta\MedicalBundle\Repository\GmcouvRepository;
use Gta\MedicalBundle\Tests\Repository\RandomMatricGeneratorTrait;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;
use Gta\TracabiliteBundle\Manager\TracabiliteManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package Gta\TracabiliteBundle\Controller
 * @author  Seif <ben.s@mipih.fr> (13/05/2019/ 15:58)
 * @version 19
 */
class DefaultController extends Controller
{
    use RandomMatricGeneratorTrait;
    private $params = array(
        'typhor' => 'a','actact'=>'c','heured'=>'r','heuref'=>'g','commentaire'=>'f',
        'mf_typtab' => 'SEN',
        'mf_type_periode' => 'garde',
        'mf_datdeb' => '06/05/2019',
        'mf_datfin' => '02/06/2019',
        'mf_servic' => '07',
        'mf_sertyp' => 'SEC',
        'mf_sercon' => ' ',
        'codhop' => '081',
        'coddif' => '10',
        'nomuti' => 'PLA081',
        'utipla' => 'PLAN00',
        'utigrd' => 'GAR081',
        'matric' => '864822',
        'typper' => 'PNM',
        'appcod' => 'GAR',
        'profil' => 'ADM_GTT',
        'pflser' => 'TOUS',
        'scppas' => '',
        'scpppm' => '',
        'scppri' => '',
        'ts_codact' => 'A',
        'ts_plsdat' => '25/05/2019',
        'ts_typhor' => 'N1',
        'ts_plscmp' => 'NON',
        'ts_plsadd' => 'NON',
        'dr1pos' => '?',
        'dr1mot' => '?',
        'dranne' => '?',
        // rémunération
        'action' => '?',
        'plsact' => 'G',
        'depcom' => 'seif',
        'plsdate' => '01/02/2001',
        'plscmp' => ' ', 'plsadd' => ' ',
        'plshor' => '12:12',
        'data' => [
            'plscmp' => 'NON',
            'plsadd' => 'NON',
            'plsuf' => ' ',
        ],

    );

    /**
     * @Route ("/trac_all")
     *
     * @author Seif <ben.s@mipih.fr>
     *
     * @param \Gta\TracabiliteBundle\Manager\TracabiliteManager $tracabiliteManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Gta\TracabiliteBundle\Exception\MissingParamsException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerConfigurationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTriggerException
     * @throws \Gta\TracabiliteBundle\Exception\UndefinedCodeActiviteException
     * @throws \ReflectionException
     */
    public function tracAction(TracabiliteManager $tracabiliteManager)
    {
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_AFFECTATION_AJOUT, $this->params,[]);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_AFFECTATION_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_AFFECTATION_SUPP, $this->params);
//
        $extra = [
            ['dephdb' => '09:00', 'dephfn' => '10:00'],
            ['dephdb' => '10:00', 'dephfn' => '12:00'],
        ];
        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DEPLACEMENT_SUPP_AUTO, $this->params, $extra);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DEPLACEMENT_EX_AJOUT, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DEPLACEMENT_EX_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DEPLACEMENT_EX_SUPP, $this->params);
//
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_ACTIVITE_POSE_HORAIRE, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_ACTIVITE_REMUNERATION_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_COUVERTURE_COUVERT_SUPP, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_COUVERTURE_COUVRANT_SUPP, $this->params);
//
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DROIT_CONGE_AJOUT, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DROIT_CONGE_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_DROIT_CONGE_SUPP, $this->params);
//
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_CONTRAT_AJOUT, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_CONTRAT_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_CONTRAT_SUPP, $this->params);
//
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_FICHE_NOUV, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_FICHE_AJOUT, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_FICHE_MAJ, $this->params);
//        $tracabiliteManager->tracabiliteDispatch(Tc::UC_FICHE_SUPP, $this->params);


        return new Response('yeah');
    }

    /**
     * @Route ("/trac1")
     *
     * @param \Gta\MedicalBundle\Repository\GmcouvRepository $gmcouvRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author Seif <ben.s@mipih.fr>
     */
    public function testGmcouvInsertAction(GmcouvRepository $gmcouvRepository)
    {
        $params = [
            'CODHOP' => '081',
            'DATCOU' => '07/12/2018',
            'MATCOU' => $this->getMatric(true),
            'HORCOU' => 'N1',
            'TYPCOU' => 'SEN',
            'SERVCO' => '07',
            'SERTCO' => 'SEC',
            // primary key ends here
            'CATCOU' => 'MAA',
            'ACTCOU' => 'A',
            'MATSEN' => 'XXXXXX',
            'HORSEN' => 'N2',
            'TABSEN' => 'SEN',
            'SERSEN' => '04',
            'TYPSEN' => 'SEC',
            'CATSEN' => 'MED',
            'ACTSEN' => 'A',
            'UTIMAJ' => 'XXXXXX',
            'DATMAJ' => '08/02/2019',
        ];
        $gmcouvRepository->insert($params);
        $params['MATCOU'] = $this->getRandomMatric(true);
        $gmcouvRepository->insert($params);
        $params['MATCOU'] = $this->getRandomMatric(true);
        $gmcouvRepository->insert($params);
        $params['MATCOU'] = $this->getRandomMatric(true);
        $gmcouvRepository->insert($params);
        $params_ = array(
            'codhop'          => '081',
            'coddif'          => '10',
            'nomuti'          => 'PLA081',
            'utipla'          => 'PLAN00',
            'utigrd'          => 'GAR081',
            'matric'          => '',
            'typper'          => 'PNM',
            'appcod'          => 'GAR',
            'profil'          => 'ADM_GTT',
            'pflser'          => 'TOUS',
            'mf_typtab'       => 'SEN',
            'mf_type_periode' => null,
            'mf_datdeb'       => '07/12/2018',
            'mf_datfin'       => '07/12/2018',
            'mf_servic'       => '07',
            'mf_sertyp'       => 'SEC',
            'mf_sercon'       => ' ',
            'ts_matric'       => $this->getMatric(true),
            'ts_typhor'       => 'N1',
            'ts_plsdat'       => '07/12/2018',
            'ts_codact'       => 'RE',
            'ts_actdur'       => '2',
            'ts_plsgar'       => 'OUI',
            'ts_plscmp'       => 'NON',
            'ts_plsadd'       => 'NON',
            'ts_acttyp'       => 'A01',
            'ts_pltyjr'       => 'SGR',
            'ts_force'        => 'force',
            'ts_modiff'       => 'couvert',
            'ts_typhor_next' => 'N2',
            'ts_codact_next'  => 'RE',
        );
        $res = $gmcouvRepository->deleteCouvert($params_);

        return $this->json([$res]);
    }

    /**
     * @param \Gta\MedicalBundle\Repository\GmcouvRepository $gmcouvRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author Seif <ben.s@mipih.fr>
     */
    public function testupdateTsAction(GmcouvRepository $gmcouvRepository)
    {
        $params = array(
            'codhop'          => '081',
            'coddif'          => '10',
            'nomuti'          => 'PLA081',
            'utipla'          => 'PLAN00',
            'utigrd'          => 'GAR081',
            'matric'          => '',
            'typper'          => 'PNM',
            'appcod'          => 'GAR',
            'profil'          => 'ADM_GTT',
            'pflser'          => 'TOUS',
            'mf_typtab'       => 'SEN',
            'mf_type_periode' => null,
            'mf_datdeb'       => '07/12/2018',
            'mf_datfin'       => '07/12/2018',
            'mf_servic'       => '07',
            'mf_sertyp'       => 'SEC',
            'mf_sercon'       => ' ',
            'ts_matric'       => '511921',
            'ts_typhor'       => 'N1',
            'ts_plsdat'       => '07/12/2018',
            'ts_codact'       => 'RE',
            'ts_actdur'       => '2',
            'ts_plsgar'       => 'OUI',
            'ts_plscmp'       => 'NON',
            'ts_plsadd'       => 'NON',
            'ts_acttyp'       => 'A01',
            'ts_pltyjr'       => 'SGR',
            'ts_force'        => 'force',
            'ts_modiff'       => 'couvert',
            'ts_typhor_next' => 'N2',
            'ts_codact_next'  => 'RE',
        );
        $gmcouvRepository->deleteCouvert($params);

        return $this->json(['abc']);
    }
}
