<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 10/04/2019 11:28
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class PeriodeProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (10/04/2019/ 11:28)
 * @version 19
 */
class PeriodeProvider implements ExpressionFunctionProviderInterface
{
    const DATE_DEMENAGEMENT_SUR_MARS = '31/12/3000';
    /**
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'periodeInterval', function ($arguments) {
            },
                function ($arguments, $periode) {
                    if (self::DATE_DEMENAGEMENT_SUR_MARS === trim($periode['datfin'])) {
                        return $periode['datdeb'].' -';
                    }
                    return $periode['datdeb'].' - '.$periode['datfin'];

                }
            ),
        );
    }
}