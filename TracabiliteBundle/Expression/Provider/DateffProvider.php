<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 30/07/2019 11:23
 */

namespace Gta\TracabiliteBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class DateffProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (30/07/2019/ 11:24)
 * @version 19
 */
class DateffProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'currentDate', function ($arguments) {
            },
                function ($arguments, $format = 'd/m/Y', $default = null) {
                    return date($format);
                }
            ),
            new ExpressionFunction(
                'dateFormat', function ($arguments) {
            },
                function ($arguments, $date, $formatIn = 'd/m/Y H:i:s', $formatOut = 'd/m/Y') {
                    $date = \DateTime::createFromFormat($formatIn, $date);

                    return $date->format($formatOut);
                }
            ),
        );
    }
}