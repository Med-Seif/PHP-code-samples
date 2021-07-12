<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 09/03/2020 14:19
 */

namespace Gta\DataExportBundle\Planning\DataWriter\Body;


use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class HoraireRowBodyWriter
 *
 * @package Gta\DataExportBundle\Planning\DataWriter\Body
 * @author  Seif <ben.s@mipih.fr> (09/03/2020/ 14:19)
 * @version 19
 */
class HoraireRowBodyWriter extends AbstractRowBodyWriter
{

    /**
     * @param \Gta\DataExportBundle\Planning\Parts\Header\HeaderPart $headerPart
     * @param \Gta\DataExportBundle\Planning\Parts\Body\BodyPart     $bodyPart
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function write(HeaderPart $headerPart, BodyPart $bodyPart)
    {
        $row = 2;
        foreach ($bodyPart->getLsData() as $dataRow) {
            // format left side
            $col = 1;
            $bodyPart->getLsFormatter()->format($row, $col, $dataRow);
            $row_ = $row;
            foreach (['AM', 'AP', 'N1', 'N2'] as $period) {
                $this->getAdapter()->writeString($row_, 2, $period);
                $this->getAdapter()->mergeCellsRange($row_, 2, $row_ + 1, 2);
                $row_ = $row_ + 2;
            }
            $this->getAdapter()->mergeCellsRange($row, $col, $row + 7, $col);
            // container activity cell format
            foreach (['AM', 'AP', 'N1', 'N2'] as $period) {
                $col = 3;
                foreach ($headerPart->getData() as $column) {
                    $dataBody = $this->getDataActivity(
                        $bodyPart->getData(),
                        $dataRow['matric'],
                        $column['index_date'],
                        strtolower($period)
                    );

                    $bodyPart->getCellFormatter()->format($row, $col, $dataBody);
                    $col++;
                }
                $row = $row + 2;
            }
        }
    }
}