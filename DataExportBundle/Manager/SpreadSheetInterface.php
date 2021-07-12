<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 04/02/2020 on  14:53
 */

namespace Gta\DataExportBundle\Manager;

/**
 * Interface SpreadSheetInterface
 * @package Gta\DataExportBundle\Worksheet\Manager
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:51
 */
interface SpreadSheetInterface
{

    public function setName($filename): self;

    public function buildWorkSheet(array $data, $modelId): self;

    public function sendFile(): self;

    /**
     * @param $filename
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function writeFile($filename): string;

}