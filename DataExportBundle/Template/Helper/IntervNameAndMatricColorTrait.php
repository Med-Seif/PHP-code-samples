<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 17/06/2019 09:33
 */

namespace Gta\DataExportBundle\Template\Helper;

use Gta\MedicalBundle\Utils\Lib\MedicalUtils;

/**
 * Trait IntervNameAndMatricColorTrait
 * @package Gta\DataExportBundle\Template\Helper
 * @author  Seif <ben.s@mipih.fr> (17/06/2019/ 09:33)
 * @version 19
 */
trait IntervNameAndMatricColorTrait
{
    use ColorsStyleTrait;

    /**
     * @param $colAlias
     * @param $row
     * @param $rowNumber
     * @param $colNumber
     *
     * @author Seif <ben.s@mipih.fr>
     */
    protected function intervNameAndMatricColor(
        $colAlias,
        $row,
        $rowNumber,
        $colNumber
    ) {
        $this->defineColorStyles();
        $matric = 'matric';
        if (in_array($colAlias, ['name', $matric])) {
            if (MedicalUtils::intervIsIE($row[$matric])) {
                $this->specificStyles[] = [
                    'row'   => $rowNumber,
                    'col'   => $colNumber,
                    'style' => self::$greenStyle,
                ];
            } elseif (MedicalUtils::intervIsRE($row[$matric])) {
                $this->specificStyles[] = [
                    'row'   => $rowNumber,
                    'col'   => $colNumber,
                    'style' => self::$blueStyle,
                ];
            }
        }
    }


}