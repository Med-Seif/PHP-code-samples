<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 30/01/2020 on  12:15
 */

namespace Gta\DataExportBundle\Planning\Formatter\Activity;


use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Utils\ExportHelper as EH;
use Gta\DataExportBundle\Utils\TsKey;

/**
 * Class CellContainerFormatter
 * @package Gta\DataExportBundle\Worksheet\Formatter\Body
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 12/02/2020 on  19:22
 */
class CellContainerFormatter extends AbstractActivity
{
    /**
     * Format Ts collectif cell container
     *
     * @param       $row
     * @param       $col
     * @param       $data
     * @param array $extraData
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:27
     */
    public function format($row, $col, $data, $extraData = [])
    {
        if (YmlConfigArrayAccess::hasConfig('body')) {
            $config = YmlConfigArrayAccess::getConfig('body');
            $this->getAdapter()->rowHeight($row, $config['cell_activity_height']);
            $this->getAdapter()->rowHeight($row + 1, $config['cell_indicator_height']);
        }
        // Format forbidden cell
        if (!$data) {
            (new ForbiddenCellFormatter())->format($row, $col, $data);

            return;
        }

        $this->data = $data;
        $this->bgColor = EH::colorCode($data['col']);

        #  First row ..activity container
        $activity = new ActivityContainer($data, $this->bgColor);
        $this->getAdapter()->writeString($row, $col, $activity->getContent(), $this->getStyle());


        # Second row .. indicator container
        $showIndicator = YmlConfigArrayAccess::getConfig(TsKey::K_BODY)[TsKey::K_CELL_SHOW_INDICATEURS];
        if (true === $showIndicator && $this->hasIndicator()) {
            $this->getAdapter()->writeString($row + 1, $col, $this->getCorner(), $this->getIndicatorStyle());

            return;
        }
        # merge activity container when hasn't indicator
        $this->getAdapter()->mergeCellsRange($row, $col, $row + 1, $col);
    }
}