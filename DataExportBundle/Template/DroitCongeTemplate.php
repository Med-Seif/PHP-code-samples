<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 22/05/2019 13:35
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Template\Helper\ColorsStyleTrait;

/**
 * Class DroitCongeTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (22/05/2019/ 13:35)
 * @version 19
 */
class DroitCongeTemplate extends SimpleTableTemplate
{
    use ColorsStyleTrait;

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function writeTable($data, $header, $footer, $startRow = 1, $startCol = 1)
    {

        // grouper par matric, name et category
        $groupedData = [];
        foreach ($data as $row) {
            $uid = $row['matric'].$row['name'].$row['category'];
            $groupedData [$uid][] = $row;
        }
        $flatData = [];
        foreach ($groupedData as $groupedRow) {
            // boucler sur chaque sous groupe
            foreach ($groupedRow as $k => $row) {
                if (0 === $k) { // on affiche le matricule, le nom et la cat seulement UNE fois par groupe
                    $flatData [] = $row;
                    continue;
                }
                // mettre vide à la place des données communes
                $row['matric'] = $row['name'] = $row['category'] = '';
                $flatData [] = $row;
            }
        }

        return parent::writeTable($flatData, $header, $footer, $startRow, $startCol);
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    protected function evaluateCell($colDataID, $colName, $row, & $resRow, $rowNumber = null, $colNumber = null)
    {
        $this->defineColorStyles();
        $colAlias = parent::evaluateCell(
            $colDataID,
            $colName,
            $row,
            $resRow,
            $rowNumber,
            $colNumber
        );
        if (null === $colAlias) {
            return;
        }
        if ('reliquat' === $colAlias && intval($row[$colAlias]) < 0) {
            $this->specificStyles[] = [
                'row'   => $rowNumber,
                'col'   => $colNumber,
                'style' => self::getRedStyle(),
            ];
        }

    }
}