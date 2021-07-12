<?php

namespace Gta\DataExportBundle;

use Gta\DataExportBundle\DependencyInjection\Compiler\ExportListenerPass;
use Gta\DataExportBundle\DependencyInjection\Compiler\TsExportDataSourceStrategyPass;
use Gta\DataExportBundle\DependencyInjection\Compiler\TsExportModelStrategyPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class GtaDataExportBundle
 * @package Gta\DataExportBundle
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class GtaDataExportBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new ExportListenerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1)
            # will be executed after priority 0, this is very important because this pass should be run after LoggerPass && AuthenticatedUserPass
            ->addCompilerPass(new TsExportModelStrategyPass())
            ->addCompilerPass(new TsExportDataSourceStrategyPass());

    }
}
