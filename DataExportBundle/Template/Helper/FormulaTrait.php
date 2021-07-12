<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 03/09/2019 15:29
 */

namespace Gta\DataExportBundle\Template\Helper;

/**
 * Trait FormulaTrait
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (03/09/2019/ 15:32)
 * @version 19
 */
trait FormulaTrait
{
    /**
     * Generates array of col indexes with their corresponding formulas to be applied then
     *
     * @param                                                       $dataMappingConfig
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getFormula($dataMappingConfig, DimensionHelper $dimensionHelper)
    {
        $i = 0;
        $formula = array();
        foreach ($dataMappingConfig as $v) {
            $i++;
            if (is_array($v) && isset($v['function'])) {
                // we use $i-1 to match correctly the array referencing system beginning from zéro in order to go well
                // with $footer php array where we want to attach our formula..yeaaaah
                $formula [$i - 1] = $this->exportAdapter->applyColFormula(
                    $i,
                    $dimensionHelper->getBodyRowIndexStart(),
                    $dimensionHelper->getBodyRowIndexEnd(),
                    $v['function']
                );
            }
        }

        return $formula;
    }
}