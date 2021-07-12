<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 06/02/2020 on  11:34
 */

namespace Gta\DataExportBundle\Planning\Parts\Header;


use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\Planning\DataWriter\Header\HeaderWriterInterface;

interface HeaderPartInterface
{

    public function getData();

    public function getFormatter(): FormatterInterface;

    public function getWriter(): HeaderWriterInterface;



}