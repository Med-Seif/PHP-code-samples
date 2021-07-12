<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 13/02/2020 on  14:37
 */

namespace Gta\DataExportBundle\Utils;

/**
 * Class TsIndicator
 * @package Gta\DataExportBundle\Worksheet\Formatter\Helper
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 13/02/2020 on  14:55
 */
class TsIndicator
{

    const CODE_REMUNERE = '0';
    const CODE_RECUPERE = '1';
    const CODE_PRIS_EN_COMPTE = '2';
    const CODE_NON_PRIS_EN_COMPTE = '3';
    const CODE_TPS_ADD_REMUNERE = '4';
    const CODE_TPS_ADD_RECUPERE = '5';
    const CODE_TPS_ADD_PRIS_EN_COMPTE = '6';


    const ICON_TYPE_REMUNERATION = [
        self::CODE_REMUNERE => '',
        self::CODE_RECUPERE => ' >',
        self::CODE_PRIS_EN_COMPTE => ' Ø',
        self::CODE_NON_PRIS_EN_COMPTE => ' X',
        self::CODE_TPS_ADD_REMUNERE => ' ┼',
        self::CODE_TPS_ADD_RECUPERE => ' ↑',
        self::CODE_TPS_ADD_PRIS_EN_COMPTE => ' ─',
    ];

    const ICON_CORNER = '■';
    const ICON_COUVERTURE = '☻ ';
    const WHITESPACE = ' . ';


    /**
     * @param $code
     * @return mixed|string
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 13/02/2020 on  15:12
     */
    public static function getRemuneration($code)
    {
        if (!isset(self::ICON_TYPE_REMUNERATION[$code])) {
            return self::WHITESPACE;
        }

        return self::ICON_TYPE_REMUNERATION[$code];
    }

    /**
     * @param $couv
     * @return string
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 13/02/2020 on  15:12
     */
    public static function getCouverture($couv)
    {
        if ("1" == $couv) {
            return self::ICON_COUVERTURE;
        }

        return self::WHITESPACE;
    }

}