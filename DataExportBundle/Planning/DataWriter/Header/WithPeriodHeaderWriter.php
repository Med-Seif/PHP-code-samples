<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 08/02/2020 on  20:25
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Header;


use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\Planning\Manager\TsExportDirector;

/**
 * Class WithPeriodHeaderWriter
 * @package Gta\DataExportBundle\Planning\DataWriter\Header
 */
class WithPeriodHeaderWriter implements HeaderWriterInterface
{
    use DefaultAdapterTrait;
    /**
     * @var FormatterInterface
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 18/02/2020 on  12:53
     */
    private $periodFormatter;

    /**
     * WithPeriodHeaderWriter constructor.
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     */
    public function __construct()
    {
        $this->periodFormatter = FF::create(FF::FORMATTER_PERIOD);
    }

    /**
     * @param array              $data
     * @param FormatterInterface $formatter
     * @param array              $options
     */
    public function write(array $data, FormatterInterface $formatter, array $options = [])
    {
        $col = 2;
        $row = 1;
        $besoin = null;


        $getBesoin = function ($data, $period) {
            if ($data['setting'] && null !== $data['setting'][strtolower($period)]['besoin']) {
                return $data['setting'][strtolower($period)]['besoin']['col'];
            }
            return null;
        };

        foreach ($data as $column) {
            $formatter->format($row, $col, $column);

            # if period row is required
            $this->getAdapter()->mergeCellsRange($row, $col, $row, $col + 3);

            // write period row
            foreach (['AM', 'AP', 'N1', 'N2'] as $period) {

                $this->periodFormatter->format($row + 1, $col, $period, $getBesoin($column, $period));
                ++$col;
            }

        }

    }
}