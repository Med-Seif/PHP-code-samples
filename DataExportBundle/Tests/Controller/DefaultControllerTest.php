<?php

namespace Gta\DataExportBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @author Seif <ben.s@mipih.fr>
     */
    private $params = [
        'mf_typtab'       => 'SEN',
        'mf_type_periode' => 'garde',
        'mf_datdeb'       => '01/04/2018',
        'mf_datfin'       => '05/05/2018',
        'mf_servic'       => '07',
        'mf_sertyp'       => 'SEC',
        'mf_sercon'       => ' ',
        'codhop'          => '081',
        'coddif'          => '10',
        'nomuti'          => 'PLA081',
        'utipla'          => 'PLAN00',
        'utigrd'          => 'GAR081',
        'matric'          => ' ',
        'typper'          => 'PNM',
        'appcod'          => 'GAR',
        'profil'          => 'ADM_GTT',
        'pflser'          => 'TOUS',
        'export'          => 1,
    ];

    public function testExportActions()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/medical/affectation/list',
            $this->params
        );
        var_dump($client->getResponse());
        $this->assertInstanceOf(BinaryFileResponse::class, $client->getResponse());
    }
}
