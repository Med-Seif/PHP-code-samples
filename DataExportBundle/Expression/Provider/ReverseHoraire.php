<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 29/05/2019 10:01
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class ReverseHoraire
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (29/05/2019/ 10:15)
 * @version 19
 */
class ReverseHoraire implements ExpressionFunctionProviderInterface
{
    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'reverseHoraire', function ($arguments) {
            },
                function ($arguments, $hor) {
                    if ('AM' === strtoupper(trim($hor))) {
                        return 'MA';
                    }
                    return $hor;
                }
            ),
        );
    }
}