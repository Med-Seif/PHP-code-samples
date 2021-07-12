<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 17/02/2020 on  14:40
 */

namespace Gta\DataExportBundle\Tests\Formatter;


use Gta\DataExportBundle\Planning\Formatter\Intervenant\LeftSideIntervenant;
use PHPUnit\Framework\TestCase;

class IntervenantFormatterTest extends TestCase
{

    public function testGetName(){

        $intervenant =  new LeftSideIntervenant();

        $data =  [
            "matric" =>  "725622",
            "nom" => "AEKUYDO IM DUYFICCEUYO",
            "prenom" => "Amile",
            "name" => "AEKUYDO IM DUYFICCEUYO Amile",
            "es" => "E",
            "abs" => false,
            "ppt" =>  "025/100",
            "cat" =>  "MED",
            "cat_lib" =>  "Médecins"
        ];


        $intervenant->setData($data);
        $expected = $intervenant->getName();

        $actual  =  'AEKUYDO IM DUYFICC. Am.';
        $this->assertEquals($expected, $actual);

    }

}