<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 06/02/2020 on  11:54
 */

namespace Gta\DataExportBundle\Planning\Formatter\Period;


use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;


/**
 * Class PeriodFormatter
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
class PeriodFormatter implements FormatterInterface
{
    use DefaultAdapterTrait;

    /**
     * @param $row
     * @param $col
     * @param $data
     * @param array $extraData
     */
    public function format($row, $col, $data, $extraData = [])
    {
        $reversePeriod = function ($data) {
            if ('AM' === strtoupper(trim($data))) {
                return 'MA';
            }

            return $data;
        };
        $this->getAdapter()->writeString($row, $col, $reversePeriod($data), $this->getStyle($extraData));
    }

    public function getColor($color)
    {
        if (null !== $color) {
            switch ($color) {
                case 'r':
                    return 'dc3545';
                case 'b':
                    return '275a87';
                case 'g':
                    return '28a745';
            }
        }
        return '000000';
    }

    public function getStyle($color)
    {
        return [
            'font' => [
                'color' => [
                    'argb' => $this->getColor($color),
                ],
            ],
        ];
    }


}