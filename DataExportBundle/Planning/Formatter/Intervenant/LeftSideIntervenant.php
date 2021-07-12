<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 30/01/2020 on  12:14
 */

namespace Gta\DataExportBundle\Planning\Formatter\Intervenant;

use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

/**
 * Class LeftSideIntervenant
 * @package Gta\DataExportBundle\Planning\Formatter\Intervenant
 */
class LeftSideIntervenant extends AbstractIntervenant
{
    use DefaultAdapterTrait;

    /**
     * @param       $row
     * @param       $col
     * @param       $data
     * @param array $extraData
     *
     * @return mixed|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function format($row, $col, $data, $extraData = [])
    {
        $config = [];
        if (YmlConfigArrayAccess::hasConfig('left')) {
            $config = YmlConfigArrayAccess::getConfig('left');
        }
        $this->data = $data;
        $richIntervenant = new RichText();
        $richIntervenant->createTextRun($this->getName())
            ->getFont()
            ->setSize($config['first_row_font_size'])
            ->setColor(new Color($this->getColor()));

        $richIntervenant->createTextRun($this->getSecondRow())
            ->getFont()
            ->setColor(new Color($this->getColor()))
            ->setSize($config['second_row_font_size'])
            ->setName('Arial');

        $this->getAdapter()->writeString($row, $col, $richIntervenant);
    }


}