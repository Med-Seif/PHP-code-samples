<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 08/02/2020 on  22:57
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Body;


use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

interface BodyWriterInterface
{
    public function write(HeaderPart $headerPart, BodyPart $bodyPart);
}