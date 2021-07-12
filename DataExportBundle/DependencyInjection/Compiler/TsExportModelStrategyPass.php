<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  16:31
 */

namespace Gta\DataExportBundle\DependencyInjection\Compiler;


use Gta\DataExportBundle\Planning\Model\TsModelInterface;
use Gta\DataExportBundle\Strategy\TsModelStrategy;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TsExportModelStrategyPass
 *
 * @package Gta\DataExportBundle\DependencyInjection\Compiler
 * @author  I will not do it for you every time!!
 * @version 19
 */
class TsExportModelStrategyPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \ReflectionException
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(TsModelStrategy::class)) {
            return;
        }
        $definition = $container->findDefinition(TsModelStrategy::class);

        // find all service IDs with the data_export.ts_model_strategy tag
        $taggedServices = $container->findTaggedServiceIds('data_export.ts_model_strategy');

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);
            $reflection = new \ReflectionClass($def->getClass());
            $interfaceCond = $reflection->implementsInterface(TsModelInterface::class);
            if (!$interfaceCond) {
                $msg = "bena yahza9";
                throw new \LogicException($msg);
            }
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addModel', array(new Reference($id)));
        }
    }
}