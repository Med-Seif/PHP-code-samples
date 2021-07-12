<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 04/03/2020 11:05
 */

namespace Gta\DataExportBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ExportListenerPass
 *
 * @package Gta\DataExportBundle\DependencyInjection\Compiler
 * @author  Seif <ben.s@mipih.fr> (04/03/2020/ 11:05)
 * @version 19
 */
class ExportListenerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('is_export_listener');
        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);
            $def->addTag(
                'is_logger',
                array(
                    'channel' => 'export',
                )
            )
                ->addTag(
                    'core.authenticated_user',
                    array(
                        'require_authentication' => true,
                    )
                )
                ->addMethodCall(
                    'setQueryTypeParamName',
                    array(
                        $container->getParameter('query_type_param_name'),
                    )
                )
                ->addMethodCall(
                    'setQueryFormatParamName',
                    array(
                        $container->getParameter('query_format_param_name'),
                    )
                );

        }
    }
}