<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 08/02/2020 on  20:24
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Header;


use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;

/**
 * Class SimpleHeaderWriter
 * @package Gta\DataExportBundle\Planning\DataWriter\Header
 */
class SimpleHeaderWriter implements HeaderWriterInterface
{
    /**
     * @param array $data
     * @param FormatterInterface $formatter
     * @param array $options
     */
    public function write(array $data, FormatterInterface $formatter, array $options = [])
    {
        $col = 3;
        $row = 1;
        foreach ($data as $column) {
            $formatter->format($row, $col, $column);
            $col++;
        }
    }
}