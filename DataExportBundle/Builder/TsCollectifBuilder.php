<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 30/01/2020 on  15:12
 */

namespace Gta\DataExportBundle\Builder;

use Gta\CoreBundle\Log\GtaLoggerTrait;
use Gta\CoreBundle\ParamConverter\MainFilter as MF;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Planning\Model\TsModelInterface;
use Gta\DataExportBundle\Strategy\TsModelStrategy;
use Psr\Log\LoggerAwareInterface;

/**
 * Builder is an interface that build parts of complex object.
 * Class TsCollectifBuilder
 * @package Gta\DataExportBundle\Worksheet\Builder
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:55
 */
class TsCollectifBuilder implements WorksheetBuilderInterface, LoggerAwareInterface
{
    use GtaLoggerTrait;

    /**
     * @var ExportAdapterInterface
     */
    private $adapter;
    /**
     * @var TsModelInterface
     */
    private $model;
    /**
     * @var TsModelStrategy
     */
    private $strategy;
    /**
     * @var array
     */
    private $params;
    /**
     * @var \Gta\DataExportBundle\Builder\GlobalStyleApplier
     */
    private $styleApplier;
    /**
     * @var \Gta\DataExportBundle\Builder\PrintConfigApplier
     */
    private $printConfigApplier;

    /**
     * TsCollectifBuilder constructor.
     *
     * @param ExportAdapterInterface                           $adapter
     * @param TsModelStrategy                                  $strategy
     * @param \Gta\DataExportBundle\Builder\GlobalStyleApplier $styleApplier
     * @param \Gta\DataExportBundle\Builder\PrintConfigApplier $printConfigApplier
     */
    public function __construct(
        ExportAdapterInterface $adapter,
        TsModelStrategy $strategy,
        GlobalStyleApplier $styleApplier,
        PrintConfigApplier $printConfigApplier
    ) {
        $this->adapter = $adapter;
        $this->strategy = $strategy;
        $this->styleApplier = $styleApplier;
        $this->printConfigApplier = $printConfigApplier;
    }


    /**
     * @param array $data
     * @param array $params
     *
     * @return WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     */
    public function setModelConfig(array $data, array $params): WorksheetBuilderInterface
    {

        $this->log('_________load worksheet model Start', [__CLASS__, __FUNCTION__]);
        $this->params = $params;
        $this->model = $this->strategy->loadModel(
            $data,
            $params
        );
        $this->log('_________load worksheet model END');

        return $this;
    }

    /**
     * @param $sheetTitle
     *
     * @return WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    public function setTitle($sheetTitle = null): WorksheetBuilderInterface
    {
        $this->log('_________set title Start', [__CLASS__, __FUNCTION__]);

        if (is_null($sheetTitle)) {
            $sheetTitle = $this->params[MF::MF_TYPTAB]." ".$this->params[MF::MF_SERVIC].' '.$this->params[MF::MF_SERTYP];
        }
        $this->adapter->setWorkSheetTitle($sheetTitle);
        $this->log('_________set title  END');

        return $this;

    }

    /**
     * @return WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    public function writeHeader(): WorksheetBuilderInterface
    {
        $this->log('_________write header Start', [__CLASS__, __FUNCTION__]);

        $header = $this->model->getHeader();
        $header->getWriter()->write(
            $header->getData(),
            $header->getFormatter()
        );
        $this->log('_________write header End');

        return $this;
    }

    /**
     * @return WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @author mberrekia <berrekia.m@mipih.fr>
     */
    public function writeBody(): WorksheetBuilderInterface
    {
        $this->log('_________write body Start', [__CLASS__, __FUNCTION__]);
        $this->model->getBody()
            ->getWriter()
            ->write(
                $this->model->getHeader(),
                $this->model->getBody()
            );

        $this->log('_________write body End');

        return $this;
    }


    /**
     * @return WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyStyle(): WorksheetBuilderInterface
    {
        $this->log('_________apply style Start', [__CLASS__, __FUNCTION__]);
        $this->styleApplier->setModel($this->model)->apply();
        $this->log('_________apply style END');

        return $this;
    }

    /**
     * @return \Gta\DataExportBundle\Builder\WorksheetBuilderInterface
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyPrintSetting(): WorksheetBuilderInterface
    {
        $this->log('_________apply print setting Start', [__CLASS__, __FUNCTION__]);
        $this->printConfigApplier->setModel($this->model)->apply($this->params);
        $this->log('_________apply print setting END');

        return $this;
    }
}