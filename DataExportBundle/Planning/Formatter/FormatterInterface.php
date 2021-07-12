<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 28/01/2020 on  18:52
 */

namespace Gta\DataExportBundle\Planning\Formatter;

/**
 * Interface FormatterInterface
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
interface FormatterInterface
{

    /**
     * @param $row
     * @param $col
     * @param $data
     * @param array $extratData
     * @return mixed
     */
    public function format($row, $col, $data, $extratData = []);
}