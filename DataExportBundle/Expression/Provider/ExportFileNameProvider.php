<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 18/02/2020 14:45
 */

namespace Gta\DataExportBundle\Expression\Provider;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class ExportFileNameProvider
 *
 * @package Gta\DataExportBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (18/02/2020/ 14:45)
 * @version 19
 */
class ExportFileNameProvider implements ExpressionFunctionProviderInterface
{

    /**
     * @return ExpressionFunction[] An array of Function instances
     * NomEcran_NomUti_Codhop_Typtab_servic_sertyp_datdeb_datfin_datjour_heureMinSecMill
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'fileName', function ($arguments) {
            },
                function ($arguments, $screenTitle) {
                    $currentDate = new \DateTime();

                    $fnameArray = [
                        $screenTitle,
                        $arguments['user'],
                        $arguments['codhop'],
//                        $arguments['libetb'],
                        $arguments['servic'],
                        $arguments['datdeb'],
                        $arguments['datfin'],
                        $currentDate->format('dmY_His'),
                    ];

                    array_walk(
                        $fnameArray,
                        function (&$item) {
                            if (null === $item) {
                                $item = 'NaN';

                                return;
                            }
                            $item = trim($item);
                            $item = str_replace([' ', '/', ':'], '', $item);
                        }
                    );

                    return implode('-', $fnameArray);
                }
            ),
        );
    }
}