<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  10:14
 */

namespace Gta\DataExportBundle\Planning\Model;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\DataWriter\Body\HoraireRowBodyWriter;
use Gta\DataExportBundle\Planning\DataWriter\Header\SimpleHeaderWriter;
use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class DateIntervenantHorModel
 * @package Gta\DataExportBundle\Worksheet\Model
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:59
 */
class DateIntervenantHorModel extends AbstractTsModel
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
            new SimpleHeaderWriter()
        );

        $cellFormatter = FF::create(FF::FORMATTER_ACTIVITY);

        $this->body = new BodyPart(
            $data[self::INTERVENANT_KEY],
            FF::create(FF::FORMATTER_INTERVENANT_WITH_PERIOD),
            $data[self::BODY_KEY],
            $cellFormatter,
            new HoraireRowBodyWriter()
        );

        // Date number * Period number + first column
        $this->setCountCol(count($this->header->getData()) + 2);
        // Intervenant number * 2 (rows by intervenant) + hearder rows
        $this->setCountRow(count($this->body->getLsData()) * 8 + 1);


        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return '3';
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyGlobalGridStyle()
    {
        return;
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyLineBreakPrints()
    {
        # Thibault tu peux encore optimiser cette fonction, j'ai plus ma tête
        # Mikael Jordan m'a appelé hier et m'a dit "Man we'r waiting you", je lui ai répondu casses toi M.t..r F..k.r j'aime Zidane
        $numberOfCOlsPerDay = 1;
        $numberOfDaysPerPage = $this->getNumberOfDaysPerPage();
        # 4 colonnes d'horaires par jour, 2 => la colonnes des intervenants + horaire, 1 => ou il fait faire le line break, c'est juste après
        $i = $numberOfCOlsPerDay;
        while ($i < $this->adapter->getLastCol()) {
            $this->adapter->setBreakCol(($numberOfDaysPerPage * $i) + 2 + 1);
            $i = $i + $numberOfCOlsPerDay;
        }
    }

    /**
     * free zone to apply your style for a specific model
     *
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function applySpecificStyle()
    {
        // global worksheet style (borders, ...)
        $horaireColStyle = $this->adapter
            ->createNewStyleObject()
            ->alignHorizontal(ExportAdapterInterface::ALIGN_CENTER)
            ->alignVertical(ExportAdapterInterface::ALIGN_CENTER)
            ->fontSize(8)
            ->getStyleObject();

        $this->adapter->applyStyleRange(
            $horaireColStyle,
            2,
            2,
            $this->getCountRow(),
            2
        );
        $this->adapter->columnWidth(2,3);
    }
}