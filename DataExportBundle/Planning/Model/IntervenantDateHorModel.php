<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  10:14
 */

namespace Gta\DataExportBundle\Planning\Model;

use Gta\DataExportBundle\Factory\FormatterFactory as FF;
use Gta\DataExportBundle\Planning\DataWriter\Header\SimpleHeaderWriter;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Class IntervenantDateHorModel
 * @package Gta\DataExportBundle\Worksheet\Model
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:59
 */
class IntervenantDateHorModel extends AbstractTsModel
{

    /**
     * @param array $data
     * @param array $params
     *
     * @return TsModelInterface
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     * @throws \Gta\DataExportBundle\Exception\FormatterException
     */
    public function init(array $data, array $params): TsModelInterface
    {
        parent::init($data, $params);

        $this->header = new HeaderPart(
            $data[self::INTERVENANT_KEY],
            FF::HEADER_BROKEN_INTERVENANT,
            new SimpleHeaderWriter()
//            [
//                'colStart' => 3,
//                'rowStart' => 1,
//                'rows' => 1,
//                'width' => 30,
//                'height' => 60,
//                'firstColWidth' => 30,
//                'firstColIndex' => 'A',
//                'style' => [
//                    'alignment' => [
//                        'horizontal' => Alignment::HORIZONTAL_CENTER,
//                        'vertical' => Alignment::VERTICAL_CENTER,
//                        'wrapText' => false,
//                    ],
//                ],
//            ]
        );

//        $this->header = new HeaderPart(
//            $data[self::INTERVENANT_KEY],
//            FormatterFactory::HEADER_BROKEN_INTERVENANT,
//            false,
//            [
//                'colStart' => 3,
//                'rowStart' => 1,
//                'rows' => 1,
//                'width' => 30,
//                'height' => 60,
//                'firstColWidth' => 30,
//                'firstColIndex' => 'A',
//            ]
//        );
//
//        $this->leftside = new ModelLeftside(
//            $data[self::CALENDAR_KEY],
//            FormatterFactory::HEADER_INLINE_DATE,
//            true,
//            []
//        );
//
//
//        $this->body = new BodyPart(
//            $data[self::BODY_KEY],
//            FormatterFactory::BODY_ACTIVITY,
//            false,
//            []
//        );
//
//
        $this->setCountCol(count($this->header->getData()) + 2)
            ->setCountRow(/*count($this->leftside->getData()) * 4 + */ 1);

//

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return '2';
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyGlobalGridStyle()
    {
        return;
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyLineBreakPrints()
    {
        return;
    }

    /**
     * free zone to apply your style for a specific model
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applySpecificStyle()
    {
        return;
    }
}