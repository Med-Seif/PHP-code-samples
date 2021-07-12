<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:29
 */

namespace Gta\DataExportBundle\Utils;


use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Class ColorTrait
 * @package Gta\DataExportBundle\ConfigLoader
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:30
 */
trait ColorTrait
{


    /**
     * @param $color
     * @return array
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 11/02/2020 on  19:34
     */
    public static function getFill($color)
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'argb' => $color,
                ],
            ],
        ];
    }


    /**
     * @param $color
     * @return string
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  19:18
     */
    public static function colorCode($color)
    {
        return ltrim($color, "#");
    }


}