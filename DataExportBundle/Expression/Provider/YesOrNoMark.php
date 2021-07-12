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
 * @author  Seif <ben.s@mipih.fr> (14/06/2019/ 19:40)
 * @version 19
 */
class YesOrNoMark implements ExpressionFunctionProviderInterface
{
    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'YesOrNoMark', function ($arguments) {
            },
                function ($arguments, $data) {
                    if ('O' === strtoupper($data)) {
                        return '√';
                    }
                    # add test 'N' === strtoupper($data) si un affichage personnalisé est demandé pour la valeur Non


                    return '';

                }
            ),
        );
    }
}