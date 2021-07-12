<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 29/04/2019 11:42
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Template\Helper\IntervNameAndMatricColorTrait;
use Gta\Domain\Lib\Std;


/**
 * Class MultiLineRowTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (02/05/2019/ 18:47)
 * @version 19
 */
class DeplacementTemplate extends SimpleTableTemplate
{
    use IntervNameAndMatricColorTrait;
    const PRINT_ALL_DATA_LINE = true;

    /**
     * @param mixed $data Données à exporter
     *
     * @return \Gta\DataExportBundle\Template\AbstractTemplate
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateFile($data)
    {
        $data = $this->flatternData($data);

        return parent::generateFile($data);
    }

    /**
     * {@inheritdoc}
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
        // colonne durée, coloriage en bleu des + ou = 3h
        if ('dur' === $colAlias && '' !== ($dur = $resRow['dur'])) {
            $minutes = Std::hourToMinutes($dur);
            if (intval($minutes) >= 180) { // on cenvertit en minute pour comparer
                $this->specificStyles[] = [
                    'row'   => $rowNumber,
                    'col'   => $colNumber,
                    'style' => self::$blueStyle,
                ];
            }
        }
        // coloriage Matric et HeaderBrokenIntervenant
        $this->intervNameAndMatricColor($colAlias, $row, $rowNumber, $colNumber);
    }
    
    /**
     * Aplatir le tableau pour l'export
     *
     * @param $data
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function flatternData($data)
    {
        $flatData = [];

        foreach ($data as $row) {
            $flatData[] = $row;
            if (0 === count($dep = $row['dep'])) {
                continue;
            }
            foreach ($dep as $rowDep) {
                unset($row['plus3h'], $row['moins3h']); // pour ne pas afficher le -3h et +3h à chaque ligne de déplacement
                // avec ligne totalement remplie dans le cas d'un déplacement OU
                // seul les horaires de déplacements + commentaire affiché
                if (self::PRINT_ALL_DATA_LINE) {
                    $flatData [] = array_merge($row, $rowDep);
                } else {
                    $flatData [] = $rowDep;
                }
            }
        }

        return $flatData;
    }
}