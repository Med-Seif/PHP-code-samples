<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 12/02/2020 on  20:28
 */

namespace Gta\DataExportBundle\Planning\Formatter\Activity;


use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use Gta\DataExportBundle\Planning\Formatter\FormatterInterface;
use Gta\DataExportBundle\StyleSheet\Colors;
use Gta\DataExportBundle\Utils\ExportHelper as EH;
use Gta\DataExportBundle\Utils\TsIndicator;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Class AbstractActivity
 * @package Gta\DataExportBundle\Worksheet\Formatter\Activity
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 12/02/2020 on  20:33
 */
abstract class AbstractActivity implements FormatterInterface
{
    use DefaultAdapterTrait;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $bgColor;

    /**
     * @var
     */
    protected $indBgColor;

    /**
     * @return bool
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 13/02/2020 on  12:19
     */
    public function hasIndicator()
    {
        return ($this->onAnotherService() || $this->hasCorner());
    }

    /**
     * @return string|null
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 13/02/2020 on  12:19
     */
    public function getCorner()
    {
        if ($this->hasCorner()) {
            return TsIndicator::ICON_CORNER;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getIndicatorStyle(): array
    {
        $bgColor = ($this->onAnotherService()) ? Colors::GRAY : $this->bgColor;

        return array_merge(
            EH::getFill($bgColor),
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
                'font'      => [
                    'size'  => 15,
                    'color' => [
                        'rgb' => '006400',
                    ],
                ],
            ]
        );
    }

    /**
     * @return array
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 31/01/2020 on  17:41
     */
    public function getStyle()
    {
        $colorFill = EH::getFill($this->bgColor);
        $borderStyle = [];
        if ($this->isSortie()) {
            $borderStyle = [
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => [
                            'argb' => '000000',
                        ],
                    ],
                ],
            ];
        }

        return array_merge($colorFill, $borderStyle);
    }

    /**
     * @return bool
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:27
     */
    public function isSortie()
    {
        return ('1' === $this->data['sor']);
    }

    /**
     * @return bool
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:27
     */
    private function onAnotherService()
    {
        return ('0' == $this->data['act_on_curser']);
    }

    /**
     * @return bool
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 12/02/2020 on  20:27
     */
    private function hasCorner()
    {
        return ('1' == $this->data['has_corner']);
    }

}