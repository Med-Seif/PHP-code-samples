<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 30/01/2020 on  14:14
 */

namespace Gta\DataExportBundle\Planning\Model;


use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Builder\YmlConfigArrayAccess;
use Gta\DataExportBundle\Configurator\TsTemplateConfigurator;
use Gta\DataExportBundle\Planning\Parts\Body\BodyPart;
use Gta\DataExportBundle\Planning\Parts\Header\HeaderPart;
use Gta\DataExportBundle\Utils\TsKey;

/**
 * Class AbstractTsModel
 * @package Gta\DataExportBundle\Worksheet\Model
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:59
 */
abstract class AbstractTsModel implements TsModelInterface
{
    /**
     * @var HeaderPart
     */
    protected $header;
    /**
     * @var BodyPart
     */
    protected $body;
    /**
     * @var \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     */
    protected $adapter;
    /**
     * @var array
     */
    private $data;
    /**
     * @var array
     */
    private $params;
    /**
     * @var integer
     */
    private $countCol;
    /**
     * @var integer
     */
    private $countRow;
    /**
     * @var \Gta\DataExportBundle\Configurator\TsTemplateConfigurator
     */
    private $configurator;

    /**
     * AbstractTsModel constructor.
     *
     * @param \Gta\DataExportBundle\Configurator\TsTemplateConfigurator $configurator
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface     $adapter
     */
    public function __construct(TsTemplateConfigurator $configurator, ExportAdapterInterface $adapter)
    {
        $this->configurator = $configurator;
        $this->adapter = $adapter;
    }

    /**
     * @param array $data
     * @param array $params
     *
     * @return \Gta\DataExportBundle\Planning\Model\TsModelInterface
     * @throws \Gta\DataExportBundle\Exception\ConfigFileParseException
     */
    public function init(array $data, array $params): TsModelInterface
    {
        $this->data = $data;
        $this->params = $params;
        $this->configurator->configure($this);

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return HeaderPart
     */
    public function getHeader(): HeaderPart
    {
        return $this->header;
    }


    /**
     * @return BodyPart
     */
    public function getBody(): BodyPart
    {
        return $this->body;
    }


    /**
     * @return int
     */
    public function getCountCol(): int
    {
        return $this->countCol;
    }

    /**
     * @param $countCol
     *
     * @return TsModelInterface
     */
    public function setCountCol($countCol): TsModelInterface
    {
        $this->countCol = $countCol;

        return $this;

    }

    /**
     * @return int
     */
    public function getCountRow(): int
    {
        return $this->countRow;
    }

    /**
     * @param $countRow
     *
     * @return TsModelInterface
     */
    public function setCountRow($countRow): TsModelInterface
    {
        $this->countRow = $countRow;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function getUid();

    /**
     * @param $index
     *
     * @return bool
     */
    public function supports($index): bool
    {
        return ($this->getUid() === $index);
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    protected function getNumberOfDaysPerPage()
    {
        $paperSize = YmlConfigArrayAccess::getConfig(TsKey::K_PRINT_METHOD_CALLS)[TsKey::K_PRINT_PAPER_SIZE_INDEX];
        if (ExportAdapterInterface::PAGE_SIZE_A4 === $paperSize) {
            return YmlConfigArrayAccess::getConfig(TsKey::K_PRINT_OPTIONS)[TsKey::K_PRINT_A4_NMB_COLS_PER_PAGE];
        }
        if (ExportAdapterInterface::PAGE_SIZE_A3 === $paperSize) {
            return YmlConfigArrayAccess::getConfig(TsKey::K_PRINT_OPTIONS)[TsKey::K_PRINT_A3_NMB_COLS_PER_PAGE];
        }
        throw new \LogicException('You should not be Here Thibault');
    }
}