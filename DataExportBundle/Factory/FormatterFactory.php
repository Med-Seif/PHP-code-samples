<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 28/01/2020 on  18:39
 */

namespace Gta\DataExportBundle\Factory;

use Gta\DataExportBundle\Exception\FormatterException;
use Gta\DataExportBundle\Planning\Formatter\Activity\CellContainerFormatter;
use Gta\DataExportBundle\Planning\Formatter\Date\DateFormatter;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\Planning\Formatter\Intervenant\HeaderBrokenIntervenant;
use Gta\DataExportBundle\Planning\Formatter\Intervenant\LeftSideIntervenant;
use Gta\DataExportBundle\Planning\Formatter\Intervenant\LeftSideIntervenantWithPeriod;
use Gta\DataExportBundle\Planning\Formatter\Period\PeriodFormatter;


/**
 * Factory to create formatter easily.
 * Class FormatterFactory
 * @package Gta\DataExportBundle\Factory
 */
abstract class FormatterFactory
{
    // Formatter types
    const FORMATTER_DATE = 'date';
    const FORMATTER_PERIOD = 'period';
    const FORMATTER_INTERVENANT_INLINE = 'intervenantInline';
    const FORMATTER_INTERVENANT_BROKEN = 'intervenantBroken';
    const FORMATTER_ACTIVITY = 'activity';
    const FORMATTER_INTERVENANT_WITH_PERIOD = 'intervenantWithPeriod';


    private static $formatters = [
        self::FORMATTER_DATE => DateFormatter::class,
        self::FORMATTER_PERIOD => PeriodFormatter::class,
        self::FORMATTER_INTERVENANT_BROKEN => HeaderBrokenIntervenant::class,
        self::FORMATTER_INTERVENANT_INLINE => LeftSideIntervenant::class,
        self::FORMATTER_ACTIVITY => CellContainerFormatter::class,
        self::FORMATTER_INTERVENANT_WITH_PERIOD => LeftSideIntervenantWithPeriod::class
    ];


    /**
     * @param string $typeFormatter
     * @param array  $config
     *
     * @return FormatterInterface
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 30/01/2020 on  10:05
     */
    public static function create(string $typeFormatter, $config = array ()): FormatterInterface
    {
        if (!isset(self::$formatters[$typeFormatter])) {
            throw new FormatterException("Man!! No formatter found for type ".$typeFormatter);
        }
        //Instantiate  formatter
        $className = self::$formatters[$typeFormatter];

        return new $className();
    }
}