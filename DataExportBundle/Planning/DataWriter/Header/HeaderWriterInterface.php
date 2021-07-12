<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 07/02/2020 on  16:40
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Header;


use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;

interface HeaderWriterInterface
{
    public function write(array $data, FormatterInterface $formatter, array $options = []);



}