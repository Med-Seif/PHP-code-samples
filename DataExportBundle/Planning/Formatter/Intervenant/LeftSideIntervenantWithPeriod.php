<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 09/03/2020 14:45
 */

namespace Gta\DataExportBundle\Planning\Formatter\Intervenant;

use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Planning\Formatter\DefaultAdapterTrait;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

/**
 * Class LeftSideIntervenantWithPeriod
 *
 * @package Gta\DataExportBundle\Planning\Formatter\Intervenant
 * @author  Seif <ben.s@mipih.fr> (09/03/2020/ 14:45)
 * @version 19
 */
class LeftSideIntervenantWithPeriod extends AbstractIntervenant
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
     * @author Seif <ben.s@mipih.fr>
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