<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia
 * Date 17/01/2020 on  15:35
 */

namespace Gta\DataExportBundle\Manager;


use Gta\DataExportBundle\Builder\TsCollectifBuilder;

/**
 * Director is part of the builder pattern
 * It builds a complex object with the help of the builder
 * Class TsExportDirector
 * @package Gta\DataExportBundle\Worksheet\Manager
 * @author  mberrekia <berrekia.m@mipih.fr>
 * Date 11/02/2020 on  18:47
 */
class TsExportDirector
{
    /**
     * @var TsCollectifBuilder
     */
    private $builder;

    /**
     * TsExportDirector constructor.
     *
     * @param \Gta\DataExportBundle\Builder\TsCollectifBuilder $builder
     */
    public function __construct(
        TsCollectifBuilder $builder
    ) {
        $this->builder = $builder;
    }


    /**
     * @param array $data
     * @param array $params
     *
     * @return TsExportDirector
     * @throws \Gta\CoreBundle\Log\UndefinedLoggerException
     * @throws \Exception
     */
    final public function build(array $data, array $params): self
    {
        $this->builder
            ->setModelConfig($data, $params)
            ->setTitle()
            ->applyStyle()
            ->writeHeader()
            ->writeBody()
            ->applyPrintSetting();
        # this allows us to detect if options were defined and not been used at all
        # use this in debug mode, be careful not to push that in prod
        // YmlConfigArrayAccess::checkAllConfig(true);

        return $this;
    }
}