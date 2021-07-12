<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 30/04/2019 19:58
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class OuiNonProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (30/04/2019/ 19:58)
 * @version 19
 */
class GrandVProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'v', function ($arguments) {
            },
                function ($arguments, $data) {
                    return (true === $data || 'true' === $data) ? '√' : '';

                }
            ),
        );
    }
}