<?php
/**
 * Created by PhpStorm.
 * User: ditte.t
 * Date: 28/05/2019
 * Time: 14:51
 */

namespace Gta\TracabiliteBundle\Expression\Provider;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class DisplayModifMajActTsColProvider
 *
 * @package Gta\TracabiliteBundle\Expression\Provider
 * @author  METS TON NOM THIBAULT JE VAIS T'ASSASINER WTF!!!
 * @version 19
 */
class DisplayModifMajActTsColProvider implements ExpressionFunctionProviderInterface
{

    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     * @author tditt
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'getModif', function ($arguments) {
            },
                function ($arguments, $new, $old) {
                    if (trim($new) !== trim($old)) {
                        return $new.' ('.$old.')';
                    }

                    return $new;
                }
            ),
            new ExpressionFunction(
                'getModifHeure', function ($arguments) {
            },
                function ($arguments, $new, $old) {
                    if (trim($new) !== trim($old)) {
                        return ', heure: '.$new.' ('.$old.')';
                    }
                    if (trim($new) != '0000') {
                        return ', heure: '.$new;
                    }

                    return '';
                }
            ),
            new ExpressionFunction(
                'getTyph', function ($arguments) {
            },
                function ($arguments, $typh, $existAct) {
                    if ($existAct) {
                        return $existAct['plshor'].' '.$typh;
                    }

                    return $typh;
                }
            ),
            new ExpressionFunction(
                'getActModif', function ($arguments) {
            },
                function ($arguments, $existing, $existing_snd) {
                    if ($existing && $existing_snd && $existing['actdur'] != 2) {
                        return $existing['plsact'].', '.$existing_snd['plsact'];
                    }
                    if ($existing) {
                        return $existing['plsact'];
                    }
                    if ($existing_snd) {
                        return $existing_snd['plsact'];
                    }

                    return ' ';
                }
            ),
            new ExpressionFunction(
                'gatActDep', function ($arguments) {
            },
                function ($arguments, $existing, $existing_snd, $dephor) {
                    if ($dephor === $existing['plshor']) {
                        return $existing['plsact'];
                    }
                    if ($existing_snd) {
                        return $existing_snd['plsact'];
                    }

                    return $existing['plsact'];

                }
            ),
            new ExpressionFunction(
                'getHeure', function ($arguments) {
            },
                function ($arguments, $heure) {
                    return substr($heure, 0, 2).'h'.substr($heure, 2, 2);
                }
            ),
        );
    }

}