<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  11:54
 */

namespace Gta\DataExportBundle\Planning\Model;

use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;

/**
 * Interface TsModelInterface
 * @package Gta\DataExportBundle\Worksheet\Model
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  19:00
 */
interface TsModelInterface
{
    const M_DATE_HORAIRE_INTERVENANT_MODEL = '1';
    const M_INTERVENANT_DATE_HORAIRE_MODEL = '2';
    const M_DATE_INTERVENANT_HORAIRE_MODEL = '3';
    /**
     * You should add model here
     */
    const MODELS = [
        self::M_DATE_HORAIRE_INTERVENANT_MODEL,
        self::M_INTERVENANT_DATE_HORAIRE_MODEL,
        self::M_DATE_INTERVENANT_HORAIRE_MODEL
    ];
    const CALENDAR_KEY    = 'calendar';
    const INTERVENANT_KEY = 'intervenant';
    const BODY_KEY        = 'body';
    const ACTIVITY_KEY    = 'activity';
    const SEPARATION_GRID_COLOR = '323232';

    /**
     * @param array $data
     * @param array $params
     *
     * @return \Gta\DataExportBundle\Planning\Model\TsModelInterface
     */
    public function init(array $data, array $params): self;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return \Gta\DataExportBundle\Planning\Parts\Header\HeaderPart
     */
    public function getHeader(): HeaderPart;

    /**
     * @return \Gta\DataExportBundle\Planning\Parts\Body\BodyPart
     */
    public function getBody(): BodyPart;

    /**
     * @param $index
     *
     * @return bool
     */
    public function supports($index): bool;

    /**
     * @return int
     */
    public function getCountCol(): int;

    /**
     * @param $countCol
     *
     * @return \Gta\DataExportBundle\Planning\Model\TsModelInterface
     */
    public function setCountCol($countCol): self;

    /**
     * @return int
     */
    public function getCountRow(): int;

    /**
     * @param $countRow
     *
     * @return \Gta\DataExportBundle\Planning\Model\TsModelInterface
     */
    public function setCountRow($countRow): self;

    /**
     * @return mixed
     */
    public function getUid();

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyGlobalGridStyle();

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyLineBreakPrints();

    /**
     * free zone to apply your style for a specific model
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function applySpecificStyle();
}