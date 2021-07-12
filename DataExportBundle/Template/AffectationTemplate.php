<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 06/06/2019 19:01
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\StyleSheet\Colors;
use Gta\DataExportBundle\Template\Helper\DimensionHelper;
use Gta\DataExportBundle\Template\Helper\IntervNameAndMatricColorTrait;
use Gta\MedicalBundle\Utils\Lib\MedicalUtils;

/**
 * Class AffectationTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (06/06/2019/ 19:02)
 * @version 19
 */
class AffectationTemplate extends SimpleTableTemplate
{
    use IntervNameAndMatricColorTrait;

    /**
     * {@inheritdoc}
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function generateFile($data)
    {
        $dimensionHelperPrincipal = new DimensionHelper($data, $this->colTitles);

        // en cas de données vides
        if (0 === count($data)) {
            $this->writeTable([0 => ['Pas de données à exporter']], [], [], 1, 1);

            return $this;
        }

        // décaler l'export pour écrire à partir de la deuxième ligne et ne pas écraser ce qui a été ajouté en dessus
        $dimensionHelperPrincipal->setStartRowIndex(2);

        // regénérer les calculs
        $dimensionHelperPrincipal->calculate();

        // tableau principal
        $this->createTablePrincipal(
            $data,
            $dimensionHelperPrincipal,
            $this->colTitles,
            true,
            false
        );
        $this->defineCommonOptions($dimensionHelperPrincipal);

        /*** Tableau de légendes cohérences ***/
        $dataCoherence = [
            ['!', MedicalUtils::AFFECTATION_STATUS_HAS_NONE],
            ['', MedicalUtils::AFFECTATION_STATUS_HAS_OTHER_PRINCIPAL],
            ['', ''], // has double
        ];
        $startColCoherance = 2;
        $startRowCoherence = $dimensionHelperPrincipal->getFooterRowIndexEnd() + 2;
        $this->writeTable(
            $dataCoherence,
            [],
            [],
            $startRowCoherence,
            $startColCoherance
        );
        // création styles pour la cohérence
        $redStyle = $this->exportAdapter->createNewStyleObject()
            ->bgColor(Colors::RED)
            ->fontBold()
            ->alignHorizontal(ExportAdapterInterface::ALIGN_CENTER)
            ->alignVertical(ExportAdapterInterface::ALIGN_CENTER)
            ->border('all',1, Colors::BLACK)
            ->getStyleObject();
        $yellowStyle = $this->exportAdapter->createNewStyleObject()
            ->bgColor(Colors::YELLOW)
            ->border('all',1, Colors::BLACK)
            ->getStyleObject();
        $desciptionStyle = $this->exportAdapter->createNewStyleObject()
            ->fontSize(10)
            ->alignWraptext()
            ->border('all',1, Colors::BLACK)
            ->getStyleObject();
        // application style
        $this->exportAdapter
            ->applyStyle($startRowCoherence, $startColCoherance, $redStyle)
            ->applyStyle($startRowCoherence + 1, $startColCoherance, $redStyle)
            ->applyStyle($startRowCoherence + 2, $startColCoherance, $yellowStyle)
            ->applyStyle($startRowCoherence, $startColCoherance + 1, $desciptionStyle)
            ->applyStyle($startRowCoherence + 1, $startColCoherance + 1, $desciptionStyle)
            ->applyStyle($startRowCoherence + 2, $startColCoherance + 1, $desciptionStyle);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateCell(
        $colDataID,
        $colName,
        $row,
        & $resRow,
        $rowNumber = null,
        $colNumber = null
    ) {
        $colAlias = parent::evaluateCell(
            $colDataID,
            $colName,
            $row,
            $resRow,
            $rowNumber,
            $colNumber
        );
        // colonne durée, coloriage en bleu des + ou = 3h
        if (in_array($colAlias, ['scppas', 'scpppm', 'scppri', 'autre'])) {
            if ('O' === strtoupper($row[$colAlias])) {
                $this->specificStyles[] = [
                    'row'   => ++$rowNumber, // faut pas oublier l'entête en plus dans ce modèle, à rendre automatique
                    'col'   => $colNumber,
                    'style' => self::$greenStyle,
                ];
            }
            if ('N' === strtoupper($row[$colAlias])) {
                $this->specificStyles[] = [
                    'row'   => ++$rowNumber,
                    'col'   => $colNumber,
                    'style' => self::$redStyle,
                ];
            }
        }

        // coloriage Matric et HeaderBrokenIntervenant
        $this->intervNameAndMatricColor($colAlias, $row, ++$rowNumber, $colNumber); // ++$rowNumbe c pour le décalage!!!

        // colonne cohérence
        if ('coher' === $colAlias) {
            $scppri = $row['scppri'];
            $autre = $row['autre'];
            if (MedicalUtils::hasDoublePrincipal($scppri, $autre) && MedicalUtils::hasNone($scppri, $autre)) {
                $resRow[$colAlias] = 'Erreur';
            } elseif (MedicalUtils::hasOtherPrincipal($scppri, $autre)) {
                $resRow[$colAlias] = 'à vérifier';
            }
        }
    }

    /**
     * @param array                                                 $data
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     * @param array                                                 $dataMappingConfig
     * @param bool                                                  $footer
     * @param bool                                                  $newSheet
     *
     * @return $this
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    protected function createTablePrincipal(
        array $data,
        DimensionHelper $dimensionHelper,
        $dataMappingConfig = [],
        $footer = true,
        $newSheet = false
    ) {


        /**** FLY ****/
        parent::createTable(
            $data,
            $dimensionHelper,
            $dataMappingConfig,
            $footer,
            $newSheet
        );

        $this->writeFirstHeaderTitles(); // appeler just'après l'appel au parent pour avoir le style par défaut affecté

        return $this;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    private function writeFirstHeaderTitles()
    {
        /***** Écriture *****/

        // ajout de la première ligne de header (manuellement)
        // NB!! jusqu' à présent ca sert à rien de rendre ce traitement générique
        $this->exportAdapter->fromArray(
            [
                1 => 'HeaderBrokenIntervenant',
                2 => '',
                3 => '',
                4 => '',
                5 => 'Plafonnement',
                6 => '',
                7 => 'Tableau principal sur',
                8 => '',
                9 => '',
            ],
            1,
            1
        );

        /***** Fusion de cellules *****/

        // HeaderBrokenIntervenant
        $this->exportAdapter->mergeCellsRange(1, 1, 1, 4);
        // Plafonnement
        $this->exportAdapter->mergeCellsRange(1, 5, 1, 6);
        // Tab principal
        $this->exportAdapter->mergeCellsRange(1, 7, 1, 9);


        /**** Application style nouvelle ligne du header ****/

        // application style header
        $style = $this->generateStyleFromArray($this->style['header']);
        $this->exportAdapter->applyStyleRange($style, 1, 1, 1, 8);

        // NB!! que le dernier style configuré sera récupéré par défaut donc il faut vider l'objet avant
        $this->exportAdapter->createNewStyleObject(); // vidage
        $this->exportAdapter->borderRight(1, '939393');
        $bordersStyle = $this->exportAdapter->getStyleObject();
        $this->exportAdapter->applyStyleRange($bordersStyle, 1, 1, 1, 4); // HeaderBrokenIntervenant
        $this->exportAdapter->applyStyleRange($bordersStyle, 1, 5, 1, 6); // Plafonnement
    }
}