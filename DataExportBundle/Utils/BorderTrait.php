<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:59
 */

namespace Gta\DataExportBundle\Utils;

use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Class BorderTrait
 * @package Gta\DataExportBundle\ConfigLoader
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:59
 */
trait BorderTrait
{


    /**
     * @param $color
     * @param string $style
     * @return array
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  18:58
     */
    public static function top($color, $style = Border::BORDER_MEDIUM)
    {
        return [
            'borderStyle' => $style,
            'color' => [
                'argb' => $color,
            ],
        ];
    }

}