<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/05/2019 11:12
 */

namespace Gta\Domain\Lib;

/**
 * Class DateUtils
 *
 * @package Gta\Domain\Lib
 * @author  Seif <ben.s@mipih.fr> (07/05/2019/ 11:13)
 * @version 19
 */
trait DateTimeUtilsTrait
{

    /**
     * Fonction qui converti une date fr en En (format d/m/Y => Ymd)
     *
     * @param        $date
     *
     * @param string $inFormat
     *
     * @return string
     * @author Olam <lamrid.o@mipih.fr>
     */
    public static function convertDateEn($date, $inFormat = 'd/m/Y')
    {
        $date = date_create_from_format($inFormat, $date);

        return $date->format('Ymd');
    }


    /**
     * Fonction qui converti une dateTime en format Timestamp
     *
     * @param $format
     * @param $dateTime
     *
     * @return int
     */
    public static function convertDateTimeToTimestamp($format, $dateTime)
    {

        $date = \DateTime::createFromFormat($format, $dateTime);
        if (false === $date) {
            throw new \InvalidArgumentException(
                'Format does not match the supplied dateTime string, (format = '.$format.', dateTime = '.$dateTime.')'
            );
        }

        return $date->getTimestamp();
    }


    /**
     * Conversion de minutes en heures
     *
     * @param        $minutes
     * @param string $format
     *
     * @return false|string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function minutesToHour($minutes, $format = 'H\hi')
    {
        if (is_array($minutes) || is_object($minutes)) {
            return null;
        }

        return gmdate($format, intval(trim($minutes)) * 60);
    }

    /**
     * Conversion du format H:i à un nombre de minutes
     *
     * @param        $hours
     * @param string $separator
     *
     * @return bool|int
     * @author Seif <ben.s@mipih.fr>
     */
    public static function hourToMinutes($hours, $separator = 'h')
    {
        // vérifier le format H:i
        if (1 !== preg_match('/^([0-9]?[0-9]|2[0-3])'.$separator.'([0-5][0-9])$/', $hours)) {
            return false;
        }

        $hourParts = explode($separator, $hours);

        // conversion
        return intval($hourParts[0]) * 60 + intval($hourParts[1]);
    }

    /**
     * Conversion de yyyymmdd vers dd/mm/yyyy
     * NB : Pas de vérification de format ni filtrage ou transformation
     * cette fonction est idéale pour des valeurs venant de la base de données
     *
     * @param $strDate
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function convertDateEnToFr($strDate)
    {
        return substr($strDate, 6, 2)
            .'/'.substr($strDate, 4, 2)
            .'/'.substr($strDate, 0, 4);
    }

    /**
     * ????
     *
     * @param        $params
     * @param string $numberOfDays
     *
     * @return mixed
     * @author ?
     */
    public static function beforeAndAfterXDays($params, $numberOfDays = '2')
    {
        $getDate = function ($date, $signe, $number) {
            return date_create_from_format('d/m/Y', $date)->modify($signe.$number.' days')->format(
                'd/m/Y'
            );
        };
        $params['mf_datdeb'] = $getDate($params['mf_datdeb'], '-', $numberOfDays);
        $params['mf_datfin'] = $getDate($params['mf_datfin'], '+', $numberOfDays);

        return $params;
    }

    /**
     * Permet d'ajouter ou supprimer un intervalle de temps
     *
     * @param \DateTime|string $date
     *
     * @param int              $amount
     * @param string           $unit
     * @param string           $format
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function modifyDate($date, $amount = 0, $unit = 'days', $format = 'd/m/Y')
    {
        // accepte soit un objet date ou une cahine de caractères
        if (is_string($date)) {
            $date = date_create_from_format($format, $date);
        }

        return $date->modify($amount.' '.$unit)->format($format);
    }


    /**
     *  Fonction convertFormatHour : conversion de heure format hhmm en heures hhHmm pour l'affichage
     *  $hour doit être au format hhmm - valeur retournée : chaîne de type xxHyy
     *
     * @param $hour
     *
     * @return string
     */
    public static function convertFormatHour($hour)
    {
        if (4 === strlen(trim($hour)) && ctype_digit($hour) && false === strpos($hour, 'h')) {
//            return sprintf("%02dh%02d", substr($hour, 0, 2), substr($hour, 2, 2));

            # Ne pas créer des casses têtes, la vie est belle pourquoi la compliquer
            # il faut croire à la documentation de PHP, à google et à stackoverflow
            # dans l'instruction de haut, qui a été remplacée , il ya trois sous instructions , le sprintf et deux substr
            # tandisque en dessous une seule substr_replace tranquille, cool et qui peut assurer la tâche toute seule, il faut juste lui donner confiance

            # Aie mince confiance dans tes moyens, grosse confiance dans stackoverflow
            # Albert Eistein (n'a jamais dit ça entre 1913-1901)

            return substr_replace($hour, 'h', 2, 0);
        }

        return $hour;
    }

    /**
     * Compare 2 dates renvoie 1 si la 1ère date est la plus grande 2 si c'est la 2ème et 0 si elles sont équivalentes
     *
     * @param        $date1
     * @param        $date2
     * @param string $inFormat
     *
     * @return number
     *
     * @author ditte.t
     */
    public static function compareDate($date1, $date2, $inFormat = 'd/m/Y')
    {
        $date1 = date_create_from_format($inFormat, $date1);
        $date2 = date_create_from_format($inFormat, $date2);
        if ($date1 > $date2) {
            return 1;
        }
        if ($date2 > $date1) {
            return 2;
        }

        return 0;


    }

    /**
     * Prend un nombre de minutes en entrées et ressort le nombre d'heure sous la forme 'H,MM'
     *
     * @param number $minutes
     *
     * @return string
     *
     * @author ditte.t
     */
    public static function minToHour($minutes)
    {
        $minutesReste = $minutes % 60;
        $hour = ($minutes - $minutesReste) / 60;

        return $hour.','.$minutesReste;
    }

    /**
     * @param string|\DateTime $date
     * @param string           $formatIn
     * @param string           $formatOut
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function convertFrDateToOracleDate($date, $formatIn = 'd/m/Y', $formatOut = 'Y-m-d')
    {
        if ($date instanceof \DateTime) {
            return $date->format($formatOut);
        }
        $oDate = date_create_from_format($formatIn, $date);

        return $oDate->format($formatOut);
    }

    /**
     * Permet de calculer la différence entre deux dates (String) et retourne un interval
     * @param $strDate1
     * @param $strDate2
     * @param string $format
     * @return \DateInterval|false
     * @author Abdessami (bennani.a@mipih.fr)
     * Date 25/11/2019 11:54
     */
    public static function diffFromStrDates($strDate1, $strDate2, $format = 'd/m/Y')
    {
        $date1 = date_create_from_format($format, $strDate1);
        $date2 = date_create_from_format($format, $strDate2);

        $interval = date_diff($date1, $date2, true);
        return $interval;
    }
}