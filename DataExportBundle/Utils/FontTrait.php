<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  20:24
 */

namespace Gta\DataExportBundle\Utils;


trait FontTrait
{

    /**
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  18:58
     */
    public static function fontColor($color)
    {
        return [
            'font' => [
                'color' => [
                    'rgb' => $color,
                ],
            ],
        ];
    }
}