<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 13/05/2019 11:39
 */

namespace Gta\TracabiliteBundle\Tests\Manager;


use Doctrine\Common\Annotations\AnnotationRegistry;
use Gta\TracabiliteBundle\Manager\TracabiliteManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class TracabiliteManagerTest
 *
 * @package Gta\TracabiliteBundle\Tests\Manager
 * @author  Seif <ben.s@mipih.fr> (13/05/2019/ 11:39)
 * @version 19
 */
class TracabiliteManagerTest extends KernelTestCase
{
    /**
     * @var TracabiliteManager
     */
    private $tracabiliteManager;
    private $params = array(
        'mf_typtab' => 'SEN',
        'mf_type_periode' => 'garde',
        'mf_datdeb' => '06/05/2019',
        'mf_datfin' => '02/06/2019',
        'mf_servic' => 'FBOU',
        'mf_sertyp' => 'REG',
        'mf_sercon' => ' ',
        'codhop' => '081',
        'coddif' => '10',
        'nomuti' => 'PLA081',
        'utipla' => 'PLAN00',
        'utigrd' => 'GAR081',
        'matric' => '000000',
        'typper' => 'PNM',
        'appcod' => 'GAR',
        'profil' => 'ADM_GTT',
        'pflser' => 'TOUS',
        'scppas' => '',
        'scpppm' => '',
        'scppri' => '',
    );


    public function testTracerAffectation()
    {

    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerContrat()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerCouverture()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerDeplacementExceptionnel()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerDeplacement()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerDroitConge()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    public function testTracerFiche()
    {
    }

    public function testPoseHoraire()
    {
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    protected function tearDown()
    {
        AnnotationRegistry::reset();
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->tracabiliteManager = $container->get(TracabiliteManager::class);
    }
}