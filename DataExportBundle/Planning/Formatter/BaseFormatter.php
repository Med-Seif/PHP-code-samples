<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 28/01/2020 on  15:27
 */

namespace Gta\DataExportBundle\Planning\Formatter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Class BaseFormatter
 * @package Gta\DataExportBundle\Planning\Formatter
 */
abstract class BaseFormatter implements FormatterInterface
{
    /**
     * @param $row
     * @param $col
     * @param $data
     * @param array $extratData
     */
    public function format($row, $col, $data, $extratData = [])
    {
        // TODO: Implement format() method.
    }


}