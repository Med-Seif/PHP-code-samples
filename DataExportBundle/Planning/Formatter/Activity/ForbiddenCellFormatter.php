<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 12/02/2020 on  19:08
 */

namespace Gta\DataExportBundle\Planning\Formatter\Activity;


use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\StyleSheet\Colors;
use Gta\DataExportBundle\Utils\ExportHelper as EH;

/**
 * Class ForbiddenCellFormatter
 * @package Gta\DataExportBundle\Worksheet\Formatter\Activity
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 13/02/2020 on  12:19
 */
class ForbiddenCellFormatter implements FormatterInterface
{
    use DefaultAdapterTrait;


    /**
     * @param $row
     * @param $col
     * @param $data
     * @param array $extratData
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  19:11
     */
    public function format($row, $col, $data, $extratData = [])
    {
        $this->getAdapter()
            ->writeString($row, $col, null, EH::getFill(Colors::DARK_GRAY))
            ->mergeCellsRange($row, $col, $row + 1, $col);
    }
}