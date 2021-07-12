<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 30/04/2019 16:31
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class OptionalProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (30/04/2019/ 18:51)
 * @version 19
 */
class OptionalProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'opt', function ($arguments) {
            },
                function ($arguments, $field, $default = null) {
                    if (isset($arguments[$field])) {
                        return $arguments[$field];
                    }

                    return $default ?: '';
                }
            ),
        );
    }
}