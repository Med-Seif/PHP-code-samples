<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 07/02/2020 on  10:27
 */

namespace Gta\DataExportBundle\Planning\Parts\Header;


use Gta\DataExportBundle\Factory\FormatterFactory;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\Planning\DataWriter\Header\HeaderWriterInterface;

/**
 * Class HeaderPart
 * @package Gta\DataExportBundle\Planning\Parts\Header
 */
class HeaderPart implements HeaderPartInterface
{
    /**
     * @var array
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 18/02/2020 on  12:56
     */
    private $data;
    /**
     * @var FormatterInterface
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 18/02/2020 on  12:56
     */
    private $formatter;
    /**
     * @var HeaderWriterInterface
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 07/02/2020 on  18:36
     */
    private $writer;

    /**
     * HeaderPart constructor.
     * @param array $data
     * @param $formatter
     * @param HeaderWriterInterface $writer
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     */
    public function __construct(
        array $data,
        $formatter,
        HeaderWriterInterface $writer
    ) {
        $this->data = $data;
        $this->formatter = FormatterFactory::create($formatter);
        $this->writer = $writer;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * @return HeaderWriterInterface
     */
    public function getWriter(): HeaderWriterInterface
    {
        return $this->writer;
    }

}