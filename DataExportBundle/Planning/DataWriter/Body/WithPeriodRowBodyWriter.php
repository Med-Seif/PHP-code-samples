<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 09/02/2020 on  00:58
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Body;


use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class WithPeriodRowBodyWriter
 * @package Gta\DataExportBundle\Planning\DataWriter\Body
 */
class WithPeriodRowBodyWriter implements BodyWriterInterface
{

    /**
     * @param HeaderPart $headerPart
     * @param BodyPart $bodyPart
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     */
    public function write(HeaderPart $headerPart, BodyPart $bodyPart)
    {
        $periodFormatter = FF::create(FF::FORMATTER_PERIOD);
        $row = 3;
        foreach ($bodyPart->getLsData() as $dataRow) {
            // format left side
            $col = 1;
            $bodyPart->getLsFormatter()->format($row, $col, $dataRow);
            $col++;
            foreach (['AM', 'AP', 'N1', 'N2'] as $period) {
                $periodFormatter->format($row, $col, $period);
                $col++;
                foreach ($headerPart->getData() as $column) {
                    $dataBody = $this->getDataActivity(
                        $bodyPart->getData(),
                        $dataRow['matric'],
                        $column['index_date'],
                        strtolower($period)
                    );

                    $bodyPart->getCellFormatter()->format($row, $col, $dataBody);
                    ++$col;
                }
                $row++;
            }


            ++$row;
        }
    }

    /**
     * @param array $data
     * @param $matric
     * @param $dateff
     * @param $horaire
     * @return mixed|null
     */
    public function getDataActivity(array $data, $matric, $dateff, $horaire)
    {
        $bodyKey = $matric.'_'.$dateff.'_'.$horaire;
        if (!array_key_exists($bodyKey, $data)) {
            return null;
        }

        return $data[$bodyKey];
    }
}