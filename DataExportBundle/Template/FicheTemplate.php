<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 22/05/2019 11:38
 */

namespace Gta\DataExportBundle\Template;

/**
 * Class FicheTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (22/05/2019/ 11:38)
 * @version 19
 */
class FicheTemplate extends SimpleTableTemplate
{
    const RED = 'C80000';

    /**
     * @param string       $colDataID
     * @param string       $colName
     * @param string       $row    Original data
     * @param string       $resRow Evaluated data, passed by ref and will contain the expected data after the call of
     *                             this method
     * @param null|integer $rowNumber
     * @param null|integer $colNumber
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function evaluateCell($colDataID, $colName, $row, & $resRow, $rowNumber = null, $colNumber = null)
    {
        $colAlias = parent::evaluateCell($colDataID, $colName, $row, $resRow, $rowNumber, $colNumber);

        if (null === $colAlias) {
            return;
        }
        // coloration du service
        if ('service' === $colAlias && null === $row['service']['servic']) {
            $this->specificStyles[] = [
                'row'   => $rowNumber,
                'col'   => $colNumber,
                'style' => $this->getServiceColor(),
            ];
        }
        //coloration du 1/2 P
        if ('nbj' === $colAlias) {
            $this->specificStyles[] = [
                'row'   => $rowNumber,
                'col'   => $colNumber,
                'style' => $this->getNbjColor($row),
            ];
        }
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function getServiceColor()
    {
        return $this->exportAdapter
            ->createNewStyleObject()
            ->fontColor(self::RED)
            ->getStyleObject();
    }

    /**
     * @param $row
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getNbjColor($row)
    {
        $color = ($row['sumdp'] === $row['nbj']) ? '000000' : self::RED;

        return $this->exportAdapter
            ->createNewStyleObject()
            ->fontColor($color)
            ->getStyleObject();
    }
}