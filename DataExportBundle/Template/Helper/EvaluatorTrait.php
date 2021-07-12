<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 03/09/2019 15:31
 */

namespace Gta\DataExportBundle\Template\Helper;

/**
 * Trait EvaluatorTrait
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (03/09/2019/ 15:32)
 * @version 19
 */
trait EvaluatorTrait
{
    /**
     * Mapping des données en se basant sur la configuration yml
     *
     * @param $dataMappingConfig
     * @param $data
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     * @see    https://symfony.com/doc/3.3/components/expression_language/syntax.html
     */
    protected function evaluateData($dataMappingConfig, $data)
    {
        $resData = [];
        $rowNumber = 1;

        // boucler sur les données de requête [0 => ['matric' => 'ABCDEF', 'name' => 'seif',...], ...]
        foreach ($data as $row) {
            $resData [] = $this->evaluateRow($row, $dataMappingConfig, $rowNumber);
            ++$rowNumber;
        }

        return $resData;
    }

    /**
     * Évaluation d'une ligne
     *
     * @param $row
     * @param $dataMappingConfig
     *
     * @param $rowNumber
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    protected function evaluateRow($row, $dataMappingConfig, $rowNumber = null)
    {
        $resRow = [];
        $colNumber = 1;
        // boucler sur le tableau de config (les cellules) => ['Matricule' => 'matric','Nom' => 'name', 'Salaire' => null,...]
        foreach ($dataMappingConfig as $colName => $colDataID) {
            $this->evaluateCell($colDataID, $colName, $row, $resRow, $rowNumber, $colNumber);
            ++$colNumber;
        }

        return $resRow;
    }

    /**
     * Évaluation d'une case dans l'export
     *
     * @param      $colDataID
     * @param      $colName
     * @param      $row
     * @param      $resRow
     *
     * @param null $rowNumber
     * @param null $colNumber
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    protected function evaluateCell($colDataID, $colName, $row, & $resRow, $rowNumber = null, $colNumber = null)
    {
        if (is_array($colDataID)) { // clef de config composée
            // on peut spécifier un alias dans le fichier de config qui sera la clef du tableau de données au lieu du provider
            // utile dans le cas ou ce dernier est une expression
            $colAlias = (isset($colDataID['alias'])) ? $colDataID['alias'] : $colDataID[self::PROVIDER];
            $colProvider = $colDataID[self::PROVIDER];
            if (null === $colProvider) {
                // si le provider est ~ , l'évaluation va déclencher une exception, donc il faut retourner
                return $colAlias;
            }
        } else {
            $colProvider = $colAlias = $colDataID;
        }

        // on utilisera l'expression language de symfony pour la compilation
        $resRow [$colAlias] = $this->el->evaluate($colProvider, $row);

        // retourner la clef de la donnée courante dans le tableau évalué
        return $colAlias;
    }
}