<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 28/01/2020 on  18:53
 */

namespace Gta\DataExportBundle\Planning\Formatter\Date;

use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;

/**
 * Class DateFormatter
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
class DateFormatter extends AbstractDate
{
    /**
     * @param       $row
     * @param       $col
     * @param       $data
     * @param array $extraData
     */
    public function format($row, $col, $data, $extraData = [])
    {
        $this->data = $data;
        $this->getAdapter()->writeString(
            $row,
            $col,
            $this->getValue(),
            $this->getStyle()
        );
    }

}