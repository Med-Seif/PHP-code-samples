<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 17/05/2019 11:47
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Template\Helper\IntervNameAndMatricColorTrait;

/**
 * Class ContratTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (17/05/2019/ 11:47)
 * @version 19
 */
class ContratTemplate extends SimpleTableTemplate
{
    use IntervNameAndMatricColorTrait;
    /**
     * @param      $colDataID
     * @param      $colName
     * @param      $row
     * @param      $resRow
     * @param null $rowNumber
     * @param null $colNumber
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function evaluateCell($colDataID, $colName, $row, & $resRow, $rowNumber = null, $colNumber = null)
    {
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
        if ('name' === $colDataID) { // le id configuré dans le yml
            $style = $this->generateInlineStyle($row, $colDataID); // la donnée couleur existe déjà dans le retour des données brutes
            $this->specificStyles[] = [
                'row'   => $rowNumber,
                'col'   => $colNumber,
                'style' => $style,
            ];
        }
        // coloriage Matric et HeaderBrokenIntervenant
        $this->intervNameAndMatricColor($colAlias, $row, $rowNumber, $colNumber);
    }

    /**
     * Utilisé quand le style est récupéré à partir des données brutes
     * ou chaqueligne de données contiendra une clef '__attributes'
     *
     * @param $row
     * @param $id
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function generateInlineStyle($row, $id)
    {
        if (!isset($row['__attributes'][$id]['color'])) {
            return [];
        }
        $color = $row['__attributes'][$id]['color'];

        return $this->exportAdapter
            ->createNewStyleObject()
            ->fontColor($color)
            ->getStyleObject();
    }

}