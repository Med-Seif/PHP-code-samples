<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 08/02/2020 on  18:24
 */

namespace Gta\DataExportBundle\Planning\Formatter\Date;


use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Class AbstractDate
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
abstract class AbstractDate implements FormatterInterface
{
    use DefaultAdapterTrait;

    /**
     * @var
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 18/02/2020 on  12:54
     */
    protected $data;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->getName()." \n".$this->getDate();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->data['date_short_name'];
    }

    /**
     * @return bool|string
     */
    public function getDate()
    {
        return substr($this->data['dateff'], 0, 5);
    }

    /**
     * @return string
     */
    public function getBgColor()
    {
        switch ($this->data['typj']) {
            case 'S':
            case 'D':
                return "E2E2E2";
            case 'JF':
                return "A3A3A3";
        }

        return 'FFFFFF';
    }

    /**
     * @return array
     */
    public function getStyle()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'argb' => $this->getBgColor(),
                ],
            ],
        ];
    }

}