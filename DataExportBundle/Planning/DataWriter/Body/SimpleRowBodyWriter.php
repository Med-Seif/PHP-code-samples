<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 08/02/2020 on  21:08
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Body;


use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class SimpleRowBodyWriter
 * @package Gta\DataExportBundle\Planning\DataWriter\Body
 */
class SimpleRowBodyWriter extends AbstractRowBodyWriter
{
    /**
     * @param HeaderPart $headerPart
     * @param BodyPart   $bodyPart
     */
    public function write(HeaderPart $headerPart, BodyPart $bodyPart)
    {
        $row = 3;
        foreach ($bodyPart->getLsData() as $dataRow) {

            // format left side
            $col = 1;
            $bodyPart->getLsFormatter()->format($row, $col, $dataRow);
            $this->getAdapter()->mergeCellsRange($row, $col, $row + 1, $col);

            $col++;

            //container activity cell format
            foreach ($headerPart->getData() as $column) {
                foreach (['AM', 'AP', 'N1', 'N2'] as $period) {

                    $dataBody = $this->getDataActivity(
                        $bodyPart->getData(),
                        $dataRow['matric'],
                        $column['index_date'],
                        strtolower($period)
                    );

                    $bodyPart->getCellFormatter()->format($row, $col, $dataBody);
                    ++$col;
                }
            }

            # putain c'est pas matrix!!!
//            ++$row;
//            ++$row;
            $row = $row + 2;
        }
    }
}