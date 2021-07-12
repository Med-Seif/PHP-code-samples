<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/05/2019 11:08
 */

namespace Gta\Domain\Lib;

/**
 * Trait StringUtils
 * @package Gta\Domain\Lib
 * @author  Seif <ben.s@mipih.fr> (07/05/2019/ 11:08)
 * @version 19
 */
trait StringUtilsTrait
{
    /**
     * Conversion d'une chaine en kebabCase x-x-x
     *
     * @param String  $string
     * @param Boolean $capitalizeFirstCharacter
     * @param String  $separator
     *
     * @return string
     */
    public static function toCamelCase($string, $capitalizeFirstCharacter = false, $separator = '_')
    {
        $str = str_replace(' ', '', ucwords(str_replace($separator, ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * Même fonction qui existe dans l'ancien
     *
     * @param $str
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function strFrenchToEnglish($str)
    {
        // liste des caractères en françias accentués : à â ç è é ê î ô ù û
        $str = str_replace(['à', 'â'], 'a', $str);
        $str = str_replace(['é', 'è', 'ê'], 'e', $str);
        $str = str_replace(['û', 'ù'], 'u', $str);
        $str = str_replace(['î'], 'i', $str);
        $str = str_replace(['ô'], 'o', $str);

        return str_replace(['ç'], 'c', $str);

    }
}