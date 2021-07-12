<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 01/07/2019 14:38
 */

namespace Gta\DataExportBundle\Template\Helper;

use Gta\DataExportBundle\StyleSheet\Colors;

/**
 * Trait ColorsStyleTrait
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (01/07/2019/ 14:39)
 * @version 19
 */
trait ColorsStyleTrait
{
    /**
     * @var array
     */
    public static $greenStyle;
    /**
     * @var array
     */
    public static $redStyle;
    /**
     * @var array
     */
    public static  $blueStyle;
    /**
     * @var string
     */
    private static $ERR_MSG = 'You should define colors before using them by calling '.__CLASS__.'::'.'defineColorsStyles() method';

    /**
     * @return array
     */
    public static function getGreenStyle()
    {
        if (!self::$redStyle) {
            throw new \LogicException(
                self::$ERR_MSG
            );
        }

        return self::$greenStyle;
    }

    /**
     * @return array
     */
    public static function getRedStyle()
    {
        if (!self::$redStyle) {
            throw new \LogicException(
                self::$ERR_MSG

            );
        }

        return self::$redStyle;
    }

    /**
     * @return array
     */
    public static function getBlueStyle()
    {
        if (!self::$redStyle) {
            throw new \LogicException(self::$ERR_MSG);
        }

        return self::$blueStyle;
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    private function defineColorStyles()
    {
        if (!self::$greenStyle) {
            self::$greenStyle = $this
                ->getExportAdapter()
                ->createNewStyleObject()
                ->fontColor(Colors::GREEN)
                ->getStyleObject();
        }
        if (!self::$redStyle) {
            self::$redStyle = $this
                ->getExportAdapter()
                ->createNewStyleObject()
                ->fontColor(Colors::RED)
                ->getStyleObject();
        }
        if (!self::$blueStyle) {
            self::$blueStyle = $this
                ->getExportAdapter()
                ->createNewStyleObject()
                ->fontColor(Colors::BLUE)
                ->getStyleObject();
        }
    }


}