<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/02/2019 20:16
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Exception\InvalidInputExportData;
use Gta\DataExportBundle\Template\Helper\DimensionHelper;
use Gta\DataExportBundle\Template\Helper\EvaluatorTrait;
use Gta\DataExportBundle\Template\Helper\FormulaTrait;
use Gta\DataExportBundle\Template\Helper\StylesTrait;
use Gta\Domain\Lib\Std;

/**
 * Class SimpleTableTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (07/02/2019/ 20:16)
 * @version 19
 */
class SimpleTableTemplate extends AbstractTemplate
{
    use FormulaTrait, EvaluatorTrait, StylesTrait;
    const PROVIDER = 'provider';

    /**
     * @var array
     */
    protected $specificStyles = [];

    /**
     * Writing of one Worksheet
     *
     * @param mixed $data
     *
     * @return \Gta\DataExportBundle\Template\SimpleTableTemplate
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateFile($data)
    {
        $dimensionHelper = new DimensionHelper($data, $this->colTitles);

        // en cas de données vides
        if (0 === count($data)) {
            $this->writeTable([0 => ['Pas de données à exporter']], [], [], 1, 1);

            return $this;
        }
        // tableau principal
        $this->createTable(
            $data,
            $dimensionHelper,
            $this->colTitles,
            true,
            false
        );
        $this->defineCommonOptions($dimensionHelper);

        return $this;
    }

    /**
     * Chaque template dorénavant génére son nom de fichier
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFileName()
    {
        $contextParams = $this->contextParams;
        $contextParams ['screenTitle'] = $this->getScreenTitle(); # it's a kind of magic, magic , magic hhhh

        return $this->el->evaluate($this->fileNameTemplate, $contextParams);
    }

    /**
     * @param mixed                                                 $data
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     *
     * @param array                                                 $dataMappingConfig couple 'col_name_in_export' =>
     *                                                                                 'col_name_in_data'
     *
     * @param bool                                                  $footer
     * @param bool                                                  $newSheet
     *
     * @return \Gta\DataExportBundle\Template\SimpleTableTemplate
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @author Seif <ben.s@mipih.fr>
     */
    protected function createTable(
        array $data,
        DimensionHelper $dimensionHelper,
        $dataMappingConfig = [],
        $footer = true,
        $newSheet = false

    ) {

        // tester si le tableau est une liste correcte
        if (!Std::isList($data, true)) {
            throw new InvalidInputExportData(array_keys($data));
        }
        if (true === $newSheet) {
            // ajouter une feuille et se positionner dessus
            $this->exportAdapter->addSheet();
        }

        // evaluer les données avec Expression Language
        $evaluatedData = $this->evaluateData($dataMappingConfig, $data);
        // titres de colonnes
        $colTitles = array_keys($dataMappingConfig);
        // largeur du tableau de données
        $count = count($evaluatedData[0]);
        // si le nombre de titres de colonnes est inférieur au nombre de colonnes de données
        if (count($colTitles) < $count) {
            // remplir par la valeur '?'
            $colTitles = array_pad($colTitles, count($evaluatedData[0]), '?');
        }

        // initialiser le footer
        $footerItem = [];
        // ajoute une ligne vide pour le footer de tableau
        if (true === $footer) {
            $headerLength = count($evaluatedData[0]);
            $footerItem = array_fill(0, $headerLength, null);
            $footerItem = array_replace($footerItem, $this->getFormula($dataMappingConfig, $dimensionHelper));
        }

        // dessiner le tableau
        $this->writeTable(
            $evaluatedData,
            $colTitles,
            $footerItem,
            $dimensionHelper->getStartRowIndex(),
            $dimensionHelper->getStartColIndex()
        );

        /* application de styles */

        // application des styles communs (par bloc)
        $this->applyCommonGlobalStyles($dimensionHelper);
        // application des styles par colonne
        $this->loadColumnStyles($dataMappingConfig);
        // application des styles spécifiques par case
        $this->applySpecificStyles();
        // appliquer les formules sur les colonnes
        $this->getFormula($dataMappingConfig, $dimensionHelper);

        return $this;
    }

    /**
     * Writing table
     *
     * @param     $data
     * @param     $header
     * @param     $footer
     *
     * @param int $startRow
     * @param int $startCol
     *
     * @return int
     * @author Seif <ben.s@mipih.fr>
     */
    protected function writeTable($data, $header, $footer, $startRow = 1, $startCol = 1)
    {
        array_unshift($data, $header);
        array_push($data, $footer);
        $this->exportAdapter->fromArray(
            $data,
            $startRow,
            $startCol
        );

        return count($data);
    }

    /**
     * @param $colName
     * @param $colTitle
     * @param $resRow
     *
     * @return bool|null
     * @author Seif <ben.s@mipih.fr>
     */
    protected function isEmptyColName($colName, $colTitle, & $resRow)
    {
        // clef de données vides
        if (0 === strlen(trim($colName))) {
            $resRow[$colName] = '???';

            return true;
        }

        return null;
    }

    /**
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function defineCommonOptions(DimensionHelper $dimensionHelper)
    {
        // application des filtres
        $this->exportAdapter
            ->enableZonalColumnFiltering(
                $dimensionHelper->getBodyRowIndexStart(),
                $dimensionHelper->getStartColIndex(),
                $dimensionHelper->getBodyRowIndexEnd(),
                $dimensionHelper->getEndColIndex()
            )
            ->applyFilter(null, null)
            ->setWorkSheetTitle($this->getScreenTitle());// nom de l'onglet

        // définir les entêtes et les pieds de âges d'mpression
        $this->definePrintHeadersConfig($this->contextParams);

        // définir les options d'impression
        $this->setPrintOptions($dimensionHelper);
    }

    /**
     * @param $params
     *
     * @author Seif <ben.s@mipih.fr>
     */
    final protected function definePrintHeadersConfig(array $params)
    {
        $params['this'] = $this; // utile pour l'appel des méthodes de la classe courant
        $evaluatedHeaderPrint = $this->el->evaluate($this->headerPrint, $params);
        $evaluatedFooterPrint = $this->el->evaluate($this->footerPrint, $params);
        $this->exportAdapter->setAllHeader($evaluatedHeaderPrint);
        $this->exportAdapter->setAllFooter($evaluatedFooterPrint);
    }

    /**
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     *
     * @author Seif <ben.s@mipih.fr>
     *
     */
    private function setPrintOptions(DimensionHelper $dimensionHelper)
    {
        $this->exportAdapter
            ->setMarginTop($this->marginTop)
            ->setMarginBottom($this->marginBottom)
            ->setMarginLeft($this->marginLeft)
            ->setMarginRight($this->marginRight)
            ->setFitHeight(0)
            ->setFitWidth(1)
            ->setLandscape()
            ->setScale(1.1)
            ->setRepeatRow(
                $dimensionHelper->getHeaderRowIndexStart(),
                $dimensionHelper->getHeaderRowIndexEnd()
            );
        // freeze
        $this->exportAdapter->freezePane(1,1);
        // paper size
        $this->exportAdapter->setPaperSizeIndex($this->paperSize);

        // Orientation
        if (ExportAdapterInterface::ORIENTATION_LANDSCAPE === $this->orientation) {
            $this->exportAdapter->setLandscape();
        } else {
            $this->exportAdapter->setPortrait();
        }

    }
}