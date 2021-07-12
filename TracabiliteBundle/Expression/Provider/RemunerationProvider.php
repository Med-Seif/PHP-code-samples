<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 24/05/2019 16:43
 */

namespace Gta\TracabiliteBundle\Expression\Provider;


use Gta\MedicalBundle\Utils\Lib\MedicalUtils;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class RemunerationProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (24/05/2019/ 16:44)
 * @version 19
 */
class RemunerationProvider implements ExpressionFunctionProviderInterface
{

    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author Seif <ben.s@mipih.fr>
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'getRemuneration', function ($arguments) {
            },
                function ($arguments, $plscmp, $plsadd) {
                    return MedicalUtils::REMUNERATION_TYPES_SHORT[$plscmp.'/'.$plsadd];

                }
            ),
            new ExpressionFunction(
                'getExistingRemu', function ($arguments) {
            },
                function ($arguments, $existing, $existing_snd) {
                    $plscmp = 'plscmp';
                    if ($existing && $existing_snd && $existing['actdur'] != 2) {
                        return MedicalUtils::REMUNERATION_TYPES_SHORT[$existing[$plscmp].'/'.$existing['plsadd']].
                            ', '.MedicalUtils::REMUNERATION_TYPES_SHORT[$existing_snd[$plscmp].'/'.$existing_snd['plsadd']];
                    }
                    if ($existing) {
                        return MedicalUtils::REMUNERATION_TYPES_SHORT[$existing[$plscmp].'/'.$existing['plsadd']];
                    }
                    if ($existing_snd) {
                        return MedicalUtils::REMUNERATION_TYPES_SHORT[$existing_snd[$plscmp].'/'.$existing_snd['plsadd']];
                    }

                    return ' ';
                }
            ),
        );
    }

}