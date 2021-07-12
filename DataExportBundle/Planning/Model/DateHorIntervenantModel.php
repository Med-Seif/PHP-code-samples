<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  10:14
 */

namespace Gta\DataExportBundle\Planning\Model;

use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\DataWriter\Body\SimpleRowBodyWriter;
use Gta\DataExportBundle\Planning\DataWriter\Header\WithPeriodHeaderWriter;
use Gta\DataExportBundle\Planning\Formatter\Activity\CellContainerFormatter;
use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class DateHorIntervenantModel
 * @package Gta\DataExportBundle\Worksheet\Model
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:59
 */
class DateHorIntervenantModel extends AbstractTsModel
{
    /**
     * @param array $data
     * @param array $params
     *
     * @return TsModelInterface
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     */
    public function init(array $data, array $params): TsModelInterface
    {
        parent::init($data, $params);

        $this->header = new HeaderPart(
            $data[self::CALENDAR_KEY],
            FF::FORMATTER_DATE,
            new WithPeriodHeaderWriter()
        );
        /** @var CellContainerFormatter $cellFormatter */
        $cellFormatter = FF::create(FF::FORMATTER_ACTIVITY);


        $this->body = new BodyPart(
            $data[self::INTERVENANT_KEY],
            FF::create(FF::FORMATTER_INTERVENANT_INLINE),
            $data[self::BODY_KEY],
            $cellFormatter,
            new SimpleRowBodyWriter()
        );
        // Date number * Period number + first column
        $this->setCountCol(count($this->header->getData()) * 4 + 1);
        // Intervenant number * 2 (rows by intervenant) + hearder rows
        $this->setCountRow(count($this->body->getLsData()) * 2 + 2);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return '1';
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyGlobalGridStyle()
    {
        $i = 1;
        $this->adapter->createNewStyleObject();
        $this->adapter->borderRight(3, self::SEPARATION_GRID_COLOR);
        $this->adapter->applyStyleColumn($this->adapter->getStyleObject(), $i);

        while ($i < $this->adapter->getLastCol()) {
            $i = $i + 4;
            $this->adapter->createNewStyleObject();
            $this->adapter->borderRight(1, self::SEPARATION_GRID_COLOR);
            $this->adapter->applyStyleColumn($this->adapter->getStyleObject(), $i);
        }
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyLineBreakPrints()
    {
        $numberOfCOlsPerDay = 4;
        $numberOfDaysPerPage = $this->getNumberOfDaysPerPage();
        # 4 colonnes d'horaires par jour, 1 => la colonnes des intervenants, 1 => ou il fait faire le line break, c'est juste après
        $i = $numberOfCOlsPerDay;
        while ($i < $this->adapter->getLastCol()) {
            $this->adapter->setBreakCol(($numberOfDaysPerPage * $i) + 1 + 1);
            $i = $i + $numberOfCOlsPerDay;
        }
    }

    /**
     * free zone to apply your style for a specific model
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applySpecificStyle()
    {
    }
}