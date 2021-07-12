<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 06/02/2020 on  10:57
 */

namespace Gta\DataExportBundle\Planning\Parts\Body;

use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\Planning\DataWriter\Body\BodyWriterInterface;

/**
 * Class BodyPart
 * @package Gta\DataExportBundle\Worksheet\Parts
 */
final class BodyPart implements BodyPartInterface
{

    /**
     * @var array
     */
    private $lsData;
    /**
     * @var FormatterInterface
     */
    private $lsFormatter;
    /**
     * @var array
     */
    private $data;
    /**
     * @var FormatterInterface
     */
    private $cellFormatter;
    /**
     * @var BodyWriterInterface
     */
    private $writer;

    /**
     * BodyPart constructor.
     *
     * @param array                                                              $lsData
     * @param \Gta\DataExportBundle\Planning\Formatter\FormatterInterface        $lsFormatter
     * @param array                                                              $data
     * @param \Gta\DataExportBundle\Planning\Formatter\FormatterInterface        $cellFormatter
     * @param \Gta\DataExportBundle\Planning\DataWriter\Body\BodyWriterInterface $writer
     */
    public function __construct(
        array $lsData,
        FormatterInterface $lsFormatter,
        array $data,
        FormatterInterface $cellFormatter,
        BodyWriterInterface $writer
    ) {

        $this->lsData = $lsData;
        $this->lsFormatter = $lsFormatter;
        $this->data = $data;
        $this->cellFormatter = $cellFormatter;
        $this->writer = $writer;
    }

    /**
     * @return array
     */
    public function getLsData(): array
    {
        return $this->lsData;
    }

    /**
     * @return FormatterInterface
     */
    public function getLsFormatter(): FormatterInterface
    {
        return $this->lsFormatter;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return FormatterInterface
     */
    public function getCellFormatter(): FormatterInterface
    {
        return $this->cellFormatter;
    }

    /**
     * @return BodyWriterInterface
     */
    public function getWriter(): BodyWriterInterface
    {
        return $this->writer;
    }

}