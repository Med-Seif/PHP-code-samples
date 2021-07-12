<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 03/09/2019 15:33
 */

namespace Gta\DataExportBundle\Template\Helper;


use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Trait StylesTrait
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (03/09/2019/ 15:35)
 * @version 19
 */
trait StylesTrait
{
    /**
     * Generate a style object from config file and returns it
     *
     * @param array $styleRules
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    protected function generateStyleFromArray(array $styleRules)
    {
        // indispensable pour vider le conteneur interne de style !!
        $this->exportAdapter->createNewStyleObject();

        foreach ($styleRules as $rule => $value) {
            $arg = (is_array($value)) ? $value : [$value]; // si la fonction accepte plusieurs arguments
            call_user_func_array([$this->exportAdapter, $rule], $arg);
        }

        // fabriquer un objet style
        return $this->exportAdapter->getStyleObject();
    }

    /**
     * Appliquer des styles qui ont été générées lors de l'écriture
     * Ce sont des styles dynamiques qui demandent de la valuer de la donnée écrite
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function applySpecificStyles()
    {
        if (0 === count($this->specificStyles)) {
            return;
        }
        $pa = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
        foreach ($this->specificStyles as $style) {
            $row = $pa->getValue($style, '[row]');
            $this->exportAdapter->applyStyle(
                ++$row, // !! ne pas oublier la ligne pour les headers, pour avoir un adressage précis
                // (fait dans un premier temps sur les données pures, sans des titres ni un footer)
                $pa->getValue($style, '[col]'),
                $pa->getValue($style, '[style]')
            );
        }
    }

    /**
     * Apply styles to common parts of a spreadsheet, (header, body etc...)
     * Global styles are loaderd from a config file
     *
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyCommonGlobalStyles(DimensionHelper $dimensionHelper)
    {
        // autosizer le tout
        // NB : cette instruction écrase toutes les modifications de largeurs qui la précéde
        $this->exportAdapter->setAutoSize();

        // appliquer le style par défaut
        $default = $this->style['default'];
        $this->exportAdapter->setDefaultStyle(
            $this->generateStyleFromArray($default)
        );

        // appliquer le style au header (titres)
        $header = $this->style['header'];
        $this->exportAdapter->applyStyleRange(
            $this->generateStyleFromArray($header),
            $dimensionHelper->getHeaderRowIndexStart(),
            $dimensionHelper->getStartColIndex(),
            $dimensionHelper->getHeaderRowIndexEnd(),
            $dimensionHelper->getHeaderLength()
        );

        // appliquer les styles au corps
        $body = $this->style['body'];
        $this->exportAdapter->applyStyleRange(
            $this->generateStyleFromArray($body),
            $dimensionHelper->getBodyRowIndexStart(),
            $dimensionHelper->getStartColIndex(),
            $dimensionHelper->getBodyRowIndexEnd(),
            $dimensionHelper->getHeaderLength()
        );

        // appliquer le zèbre au tableau
        $odd = $this->style['odd'];
        $i = $dimensionHelper->getBodyRowIndexStart();
        while ($i <= $dimensionHelper->getBodyRowIndexEnd()) {
            $this->exportAdapter->applyStyleRange(
                $this->generateStyleFromArray($odd),
                $i,
                $dimensionHelper->getStartColIndex(),
                $i,
                $dimensionHelper->getHeaderLength()
            );
            $i = $i + 2;
        }

        // appliquer le style au footer
        $this->exportAdapter->applyStyleRange(
            $this->generateStyleFromArray($header),
            $dimensionHelper->getFooterRowIndexStart(),
            $dimensionHelper->getStartColIndex(),
            $dimensionHelper->getFooterRowIndexEnd(),
            $dimensionHelper->getHeaderLength()
        );
    }

    /**
     * Loads styles per column
     *
     * @param $dataMappingConfig
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function loadColumnStyles($dataMappingConfig)
    {
        $i = 1;
        $widhKeyName = 'width';
        foreach ($dataMappingConfig as $colDataID) {
            if (is_array($colDataID) && isset($colDataID['style'])) {
                $style = $colDataID['style'];

                // le width et le height (les dimensions) ne font pas partie du style
                if (isset($style[$widhKeyName])) {
                    $width = $style[$widhKeyName];
                    unset($style[$widhKeyName]); // sinon exception lors de la génération, pas de fonction de génération associé à width
                    $this->exportAdapter
                        ->setColumnAutosize($i, false)
                        ->columnWidth($i, $width);
                }

                $styleObject = $this->generateStyleFromArray($style);
                $this->exportAdapter->applyStyleColumn($styleObject, $i);
            }
            $i++;
        }
    }
}