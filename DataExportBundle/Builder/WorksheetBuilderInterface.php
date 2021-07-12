<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 28/01/2020 on  14:15
 */

namespace Gta\DataExportBundle\Builder;

/**
 * Interface WorksheetBuilderInterface
 * @package Gta\DataExportBundle\Builder
 * @version 19
 */
interface WorksheetBuilderInterface
{
    /**
     * @param array $data
     * @param array $params
     *
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function setModelConfig(array $data, array $params): self;

    /**
     * @param null $sheetTitle
     *
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function setTitle($sheetTitle = null): self;

    /**
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function writeHeader(): self;

    /**
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function writeBody(): self;

    /**
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function applyPrintSetting(): self;

    /**
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     */
    public function applyStyle(): self;
}