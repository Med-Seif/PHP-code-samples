<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 12/06/2019 18:07
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class HeaderIntervenantName
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (12/06/2019/ 18:07)
 * @version 19
 */
class HeaderIntervenantName implements ExpressionFunctionProviderInterface
{

    /**
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'HeaderIntervenantName', function ($arguments) {
            },
                function ($arguments, $field, $default = null) {
                    if (null !== $field) {
                        return "\n".$field;
                    }
                }
            ),
        );
    }
}