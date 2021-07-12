<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 09/02/2020 on  00:39
 */

namespace Gta\DataExportBundle\Planning\Formatter\Intervenant;

use Gta\DataExportBundle\StyleSheet\Colors;
use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\MedicalBundle\Utils\Lib\MedicalUtils;

/**
 * Class AbstractIntervenant
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
abstract class AbstractIntervenant implements FormatterInterface
{
    use DefaultAdapterTrait;

    /**
     * @var
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:16
     */
    protected $data;

    /**
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:16
     */
    public function getName()
    {
        if (23 <= strlen($this->data['name'])) {

            $nom  =  $this->data['nom'];
            if(18 < strlen($this->data['nom'])){
                $nom  =  substr($this->data['nom'], 0, 18).'.';
            }

            return  $nom.' '.substr($this->data['prenom'], 0, 2).'.';
        }


        return $this->data['name'];
    }


    /**
     * @param array $data
     */
    public function  setData(array $data){
        $this->data =  $data;
    }

    /**
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:16
     */
    public function getMatric()
    {
        return $this->data['matric'];
    }

    /**
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:16
     */
    public function getCat()
    {
        return $this->data['cat'];
    }

    /**
     * @return mixed
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:16
     */
    public function getPpt()
    {
        return $this->data['ppt'];
    }

    /**
     * @return string
     */
    public function getPes()
    {

        if (true == $this->data['abs']) {
            return 'NP';
        }

        if (in_array($this->data['es'], ['E', 'S', 'E/S'])) {
            return $this->data['es'];
        }

        return '';

    }

    /**
     * @return string
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:14
     */
    public function getSecondRow()
    {
        return "\n".$this->getMatric().' '.$this->getCat().'  '.$this->getPpt().'  '.$this->getPes();
    }


    /**
     * @return string
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 14/02/2020 on  08:13
     */
    public function getColor()
    {
        $matric = $this->getMatric();

        if (MedicalUtils::intervIsIE($matric)) {
            return Colors::GREEN;
        }
        if (MedicalUtils::intervIsRE($matric)) {
            return Colors::BLUE;
        }

        return Colors::BLACK;
    }
}