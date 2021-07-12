<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 27/02/2020 11:26
 */

namespace Gta\DataExportBundle\DependencyInjection\Compiler;


use Gta\DataExportBundle\Event\Listener\TsExportDataSourceJsonFileStrategy;
use Gta\DataExportBundle\Event\Listener\TsExportDataSourceStrategyInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TsExportDataSourceStrategyPass
 *
 * @package Gta\DataExportBundle\DependencyInjection\Compiler
 * @author  Seif <ben.s@mipih.fr> (27/02/2020/ 11:27)
 * @version 19
 */
class TsExportDataSourceStrategyPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @author  Seif <ben.s@mipih.fr>
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(TsExportDataSourceStrategyInterface::class)) {
            return;
        }
        if (!$container->hasParameter('export_ts_from_json_file')) {
            return;
        }
        $def = $container->getDefinition(TsExportDataSourceStrategyInterface::class);
        $parameter = $container->getParameter('export_ts_from_json_file');
        $env = $container->getParameter('kernel.environment');
        # mode JSON file to gather data from TSCOLL will NEVER be invoked in prod mode
        if (true ===  $parameter && 'dev' === $env) {
            $def->setClass(TsExportDataSourceJsonFileStrategy::class);
        }

    }
}