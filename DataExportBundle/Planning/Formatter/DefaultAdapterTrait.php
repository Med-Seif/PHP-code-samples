<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 10/02/2020 on  15:10
 */

namespace Gta\DataExportBundle\Planning\Formatter;



use Gta\DataExportBundle\Manager\SpreadSheetManager;

/**
 * Trait DefaultAdapterTrait
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 10/02/2020 on  15:10
 */
trait DefaultAdapterTrait
{
    /**
     * @return \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     */
    public function getAdapter()
    {
        return SpreadSheetManager::getAdapter();
    }
}