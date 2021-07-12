<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/05/2019 09:58
 */

namespace Gta\Domain\Lib;

/**
 * Trait ArrayUtils
 * @author  Seif <ben.s@mipih.fr> (07/05/2019/ 11:08)
 * @package Gta\Domain\Lib
 * @version 19
 */
trait ArrayUtilsTrait
{
    /**
     * Aplatir un tableau en se basant sur une clef
     *
     * Exemple :
     * <code>
     *      <?php
     *      $input = ['a','b','c' => ['c1','c2','c3']];
     *      $output = self::flatternTable($input,'c');
     *      var_export($output);
     *      ?>
     * </code>
     * <pre>
     *      array(
     *          array('a','b','c1'),
     *          array('a','b','c2'),
     *          array('a','b','c3')
     *      );
     * </pre>
     *
     * @param array $tableIn
     *
     * @param string $dataGroupedKey clef de la donnée à se baser dessus pour aplatir le tableau
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public static function flatternTable(array $tableIn, $dataGroupedKey)
    {
        $flatterRow = function ($row) use ($dataGroupedKey) {
            $res = [];
            $arr = [];
            $children = [];
            foreach ($row as $k => $cell) {
                if (!is_array($cell) || ($k != $dataGroupedKey)) {
                    $arr[$k] = $cell;
                    continue;
                }
                foreach ($cell as $k1 => $value1) {
                    $children[$k1] = $value1;
                }
            }
            if (empty($children)) {
                return $res[] = $arr; // pas d'enfants, retourner le tronc
            }
            foreach ($children as $k => $child) {
                return $res [] = array_merge($arr, (array)$child);
            }

            return $res;
        };
        $tableOut = [];
        foreach ($tableIn as $v) {
            $tableOut = array_merge($tableOut, $flatterRow($v));
        }

        return $tableOut;
    }

    /**
     *
     * Fonction qui crée juste une copie d'une structure ou fusionne les structures passées en paramètre
     * utiliser ainsi :
     * $test = $this->arrayMerge($_POST); //mets tout dans un nouveau tableau
     * $test = $this->arrayMerge($_POST,$this->filter); //mets tout le 1er praramètre dans un tableau second paramètre
     * existant, retourne le second paramètre
     * LE TABLEAU PASSE EN 1er PARAMETRE N'EST PAS MODIFIE
     *
     * @param array $postData description...
     * @param array $outParams description...
     *
     * @return array
     * @author FBOU
     */
    public static function arrayMerge($postData, &$outParams = array())
    {
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                $outParams[$key] = array();
                self::arrayMerge($value, $outParams[$key]);
            } else {
                $outParams[$key] = $value;
            }
        }

        return $outParams;
    }

    /**
     *
     * Fonction qui crée juste une copie d'une structure ou fusionne les structures passées en paramètre
     * les valeurs sont passées à utf8_decode()
     * utiliser ainsi :
     * $test = $this->arrayMergeUTF8($_POST); //mets tout dans un nouveau tableau
     * $this->arrayMergeUTF8($_POST,$this->filter); //mets tout le 1er praram�tre dans un tableau second param�tre
     * existant, retourne le second param�tre LE TABLEAU PASSE EN 1er PARAMETRE N'EST PAS MODIFIE
     *
     * @param array $postData Description...
     * @param array $outParams Description...
     *
     * @return array
     * @author FBOU
     */
    public static function arrayMergeUTF8($postData, &$outParams = array())
    {
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                $outParams[$key] = array();
                self::arrayMergeUTF8($value, $outParams[$key]);
            } else {
                $outParams[$key] = utf8_decode($value);
            }
        }

        return $outParams;
    }

    /**
     *
     * Fonction qui crée juste une copie d'une structure ou fusionne les structures passées en paramètre
     * les valeurs sont passées à iconv('Windows-1252', 'utf-8',$)
     * utiliser ainsi :
     * $test = $this->arrayMergeWindows1252($_POST); //mets tout dans un nouveau tableau
     * $this->arrayMergeWindows1252($_POST,$this->filter); //mets tout le 1er praramètre dans un tableau second
     * paramètre existant, retourne le second paramètre LE TABLEAU PASSE EN 1er PARAMETRE N'EST PAS MODIFIE
     *
     * @param array $postData
     * @param array $outParams
     *
     * @return array
     * @author FBOU
     *
     */
    public static function arrayMergeWindows1252($postData, &$outParams = array())
    {
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                $outParams[$key] = array();
                self::arrayMergeWindows1252($value, $outParams[$key]);
            } else {
                $outParams[$key] = iconv('Windows-1252', 'utf-8', $value);
            }
        }

        return $outParams;
    }

    /**
     * Groupement des éléments d'un tableau par clef(s)
     * Pour un index donnée (le tableau $keys), on retourne :
     * 1-un ensemble d'elements Array ['index1' => Array ['index2' => ... Array['indexn' => Array [ 0 => $valeurElement
     * ]]..]]
     * 2-un ensemble d'elements Array ['index1' => Array ['index2' => ... Array['indexn' => Array [ 0 => $valeurElement
     * ]]..]]
     *
     * @param array $arr Tableau d'entrée
     * @param array $keys Clefs de groupement
     * @param boolean $unique Indiquer si le tableau comprend desc lefs uniques ou pas
     * @param boolean $deleteKey Si on veut supprimer la valeur indexée du row après l'indexation
     *
     * @param bool $toLowercase
     *
     * @param bool $trim Supprimer les espaces
     * @return array
     * @author Seif
     */
    public static function arrayGroupBy(
        array $arr,
        array $keys,
        $unique = true,
        $deleteKey = false,
        $toLowercase = true,
        $trim = false
    )
    {
        $grouped = array();
        $key = array_shift($keys);
        if (!is_string($key) && !is_int($key) && !is_float($key)) {
            trigger_error('arrayGroupBy(): La clef doit être une chaine de caractère ou un entier', E_USER_ERROR);
        }
        foreach ($arr as $value) {
            $value_ = $value;
            if ($deleteKey) {
                unset($value_[$key]);
            }
            if ($toLowercase) {
                $value[$key] = strtolower($value[$key]);
            }
            if ($trim) {
                $value[$key] = trim($value[$key]);
            }
            if ($unique) {
                if (empty($keys)) {
                    $grouped[$value[$key]] = $value_;
                    continue;
                }
                $grouped[$value[$key]][] = $value_;
                continue;
            }
            $grouped[$value[$key]][] = $value_;
        }
        if (count($keys) > 0) {
            foreach ($grouped as & $value) {
                $value = self::arrayGroupBy($value, $keys, $unique, $deleteKey, $toLowercase, $trim);
            }
        }

        return $grouped;
    }

    /**
     * Groupement des éléments d'un tableau par une seule clef (simple ou composé)
     * en ne pas ajoutant de niveau de profondeur
     *
     * @param array $arr
     * @param array $keys
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public static function arrayGroupByKey(array $arr, array $keys)
    {
        $return = [];
        foreach ($arr as $row) {
            $k = '';
            foreach ($keys as $key) {
                $k .= $row[$key];
            }
            $return [$k] = $row;
        }

        return $return;
    }

    /**
     *
     * Group an array of associative arrays by some key
     *
     * @param string $groupBy
     * @param array $data
     *
     * @return array
     * @author mber
     *
     */
    public static function groupBy($data, $groupBy)
    {
        if (empty($groupBy)) {
            return $data;
        }
        if (!is_array($data)) {
            return [];
        }
        $result = [];
        foreach ($data as $val) {
            if (!array_key_exists($groupBy, $val)) {
                $result[""][] = $val;
                continue;
            }
            $result[strtolower($val[$groupBy])][] = $val;
        }

        return $result;
    }

    /**
     * Test whether an array contains one or more string keys
     *
     * @param mixed $value
     * @param bool $allowEmpty Should an empty array() return true
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function hasStringKeys($value, $allowEmpty = false)
    {
        if (null !== ($res = self::processArray($value, $allowEmpty))) {
            return $res;
        }

        return count(array_filter(array_keys($value), 'is_string')) > 0;
    }

    /**
     * Test whether an array contains one or more integer keys
     *
     * @param mixed $value
     * @param bool $allowEmpty Should an empty array() return true
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function hasIntegerKeys($value, $allowEmpty = false)
    {
        if (null !== ($res = self::processArray($value, $allowEmpty))) {
            return $res;
        }

        return count(array_filter(array_keys($value), 'is_int')) > 0;
    }

    /**
     * Test whether an array contains one or more numeric keys.
     *
     * A numeric key can be one of the following:
     * - an integer 1,
     * - a string with a number '20'
     * - a string with negative number: '-1000'
     * - a float: 2.2120, -78.150999
     * - a string with float:  '4000.99999', '-10.10'
     *
     * @param mixed $value
     * @param bool $allowEmpty Should an empty array() return true
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function hasNumericKeys($value, $allowEmpty = false)
    {
        if (null !== ($res = self::processArray($value, $allowEmpty))) {
            return $res;
        }

        return count(array_filter(array_keys($value), 'is_numeric')) > 0;
    }

    /**
     * Test whether an array is a list
     *
     * A list is a collection of values assigned to continuous integer keys
     * starting at 0 and ending at count() - 1.
     *
     * For example:
     * <code>
     * $list = array('a', 'b', 'c', 'd');
     * $list = array(
     *     0 => 'foo',
     *     1 => 'bar',
     *     2 => array('foo' => 'baz'),
     * );
     * </code>
     *
     * @param mixed $value
     * @param bool $allowEmpty Is an empty list a valid list?
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function isList($value, $allowEmpty = false)
    {
        if (null !== ($res = self::processArray($value, $allowEmpty))) {
            return $res;
        }

        return (array_values($value) === $value);
    }

    /**
     * Test whether an array is a hash table.
     *
     * An array is a hash table if:
     *
     * 1. Contains one or more non-integer keys, or
     * 2. Integer keys are non-continuous or misaligned (not starting with 0)
     *
     * For example:
     * <code>
     * $hash = array(
     *     'foo' => 15,
     *     'bar' => false,
     * );
     * $hash = array(
     *     1995  => 'Birth of PHP',
     *     2009  => 'PHP 5.3.0',
     *     2012  => 'PHP 5.4.0',
     * );
     * $hash = array(
     *     'formElement,
     *     'options' => array( 'debug' => true ),
     * );
     * </code>
     *
     * @param mixed $value
     * @param bool $allowEmpty Is an empty array() a valid hash table?
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function isHashTable($value, $allowEmpty = false)
    {
        if (null !== ($res = self::processArray($value, $allowEmpty))) {
            return $res;
        }

        return (array_values($value) !== $value);
    }

    /**
     * Returns the first element of an array
     *
     * @param array $arr
     *
     * @return mixed|null
     * @author Seif <ben.s@mipih.fr>
     */
    public static function arrayGetFirstElement(array $arr)
    {
        if (0 === count($arr)) {
            return null;
        }

        return current(reset($arr));
    }

    /**
     *
     * Fonction qui permet de faire la somme de valeurs de tableaux indexé
     * exple :
     * Input:
     *      $array = [[
     *               'a'=>1,
     *               'b'=>1,
     *               'c'=>1,
     *               ],
     *               [
     *               'a'=>2,
     *               'b'=>2,
     *               ],
     *               [
     *               'a'=>3,
     *               'd'=>3,
     *               ]
     *               ];
     *
     * Output:
     *
     *               Array
     *               (
     *               [a] => 6
     *               [b] => 3
     *               [c] => 1
     *               [d] => 3
     *               )
     *
     * @param $array
     *
     * @return mixed
     * @author Lamrid Ouardia
     */
    public static function arraySumValuesByKey($array)
    {
        return array_reduce(
            $array,
            function ($arr, $item) {
                foreach ($item as $k => $v) {
                    $arr[$k] = isset($arr[$k]) ? $arr[$k] + $v : $v;
                }

                return $arr;
            },
            []
        );

    }

    /**
     * Gets Array with $keys from $input
     * If no matching between $keys ans $input, empty array will be returned
     *
     * @param array $input
     * @param       $keys
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     * @example
     *         Input = ['a' => 1, 'b' => 2, 'c' => 3]
     *         Keys = ['a','c', 'm']
     *         return = ['a' => 1, 'c' => 3]
     *
     */
    public static function arrayGetValuesByKeys(array $input, $keys)
    {
        if (!is_array($keys)) {
            if (isset($input[$keys])) {
                return [$keys => $input[$keys]];
            }

            return [];
        }
        $returnArray = [];
        foreach ($keys as $key) {
            if (isset($input[$key])) {
                $returnArray[$key] = $input[$key];
            }
        }

        return $returnArray;
    }

    /**
     * returns only elements that has their keys in the $keys params
     *
     * @param array $input
     * @param array $keys
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public static function arrayExtractValuesByKeys(array $input, array $keys)
    {
        return array_filter(
            $input,
            function ($key) use ($keys) {
                if (in_array($key, $keys)) {
                    return true;
                }

                return false;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @param      $value
     * @param bool $allowEmpty
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    private static function processArray($value, $allowEmpty)
    {
        if (!is_array($value)) {
            return false;
        }
        // must be always boolean
        $allowEmpty = (!is_bool($allowEmpty)) ? boolval($allowEmpty) : $allowEmpty;
        if (!$value) {
            return $allowEmpty;
        }

        return null;
    }
}