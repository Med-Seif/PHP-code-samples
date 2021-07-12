<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:45
 */

namespace Gta\DataExportBundle\Planning\Formatter\Activity;

use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Utils\ExportHelper;
use Gta\DataExportBundle\Utils\TsIndicator;
use Gta\DataExportBundle\Utils\TsKey;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

/**
 * Class ActivityContainer
 * @package Gta\DataExportBundle\Worksheet\Formatter
 * @author  mberrekia <berrekia.m@mipih.fr>
 */
class ActivityContainer
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var RichText
     */
    private $content;
    /**
     * @var string
     */
    private $bgColor;
    /**
     * @var \Gta\DataExportBundle\Builder\YmlConfigArrayAccess
     */
    private $config;

    /**
     * ActivityContainer constructor.
     *
     * @param array $data
     * @param       $bgColor
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct(array $data, $bgColor)
    {
        $this->data = $data;
        $this->bgColor = $bgColor;
        if (YmlConfigArrayAccess::hasConfig('body')) {
            $this->config = YmlConfigArrayAccess::getConfig('body');
        }

        $this->content = new RichText();
        if ($data && null != $data['act']) {
            $showCouverture = YmlConfigArrayAccess::getConfig(TsKey::K_BODY)[TsKey::K_CELL_SHOW_COUVERTURE];
            if (true === $showCouverture) {
                $this->setCouverture();
            }
            $showActivite = YmlConfigArrayAccess::getConfig(TsKey::K_BODY)[TsKey::K_CELL_SHOW_ACTIVITE];
            if (true === $showActivite) {
                $this->setCode();
            }
            $showRemuneration = YmlConfigArrayAccess::getConfig(TsKey::K_BODY)[TsKey::K_CELL_SHOW_REMUNERATION];
            if (true === $showRemuneration) {
                $this->setRemuneration();
            }
        }
    }

    /**
     * @return RichText
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    final public function getContent()
    {
        return $this->content;
    }

    /**
     * set couverture indicator and color
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    private function setCouverture()
    {
        $hasCouv = $this->data['act']['has_couv'];
        $icon = TsIndicator::getCouverture($hasCouv);
        $color = ("1" == $hasCouv) ? ExportHelper::colorCode($this->data['act']['col_couv']) : $this->bgColor;
        $couverture = $this->content->createTextRun($icon);
        $couverture->getFont()
            ->setName('Arial')
            ->setColor(new Color($color))
            ->setSize($this->config['cell_couverture_font_size']);

    }

    /**
     * set activity code value, font underlined, color and size
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    private function setCode()
    {
        $act = $this->data['act'];
        $doubleAct = $this->data['dbl_act'];

        # Code activity string value
        $getCode = function () use ($act) {
            return $act['cod'];
        };

        # Underline code activity
        $getUnderline = function () use ($doubleAct) {
            if (null !== $doubleAct) {
                return true;
            }

            return "none";
        };

        # Text color code activity
        $getTxtColor = function () use ($act) {
            if (null !== $act['tco']) {
                return ExportHelper::colorCode($act['tco']);
            }

            return null;
        };


        if ($getCode()) {
            $code = $this->content->createTextRun($getCode());
            $code->getFont()->setUnderline($getUnderline());
            $code->getFont()->setName('Arial');
            $code->getFont()->setColor(new Color($getTxtColor()));
            $code->getFont()->setSize($this->config['cell_code_font_size']);
        }

    }

    /**
     * set remuneration icon from code value
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    private function setRemuneration()
    {
        $code = $this->data['act']['rem']['code'];
        $color = (null !== $code) ? Color::COLOR_BLACK : $this->bgColor;
        $icon = TsIndicator::getRemuneration($code);
        $remuneration = $this->content->createTextRun($icon);
        $remuneration->getFont()->setColor(new Color($color));
        $remuneration->getFont()
            ->setBold(true)
            ->setName('Arial')
            ->setSize($this->config['cell_remuneration_font_size']);
    }


}