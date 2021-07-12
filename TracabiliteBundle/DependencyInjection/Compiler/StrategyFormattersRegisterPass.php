<?php

namespace Gta\TracabiliteBundle\DependencyInjection\Compiler;

use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class StrategyFormattersRegisterPass
 *
 * @package Gta\TracabiliteBundle\DependencyInjection\Compiler
 * @author  Seif <ben.s@mipih.fr>
 * @version 19
 */
class StrategyFormattersRegisterPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function process(ContainerBuilder $container)
    {
        $tag = 'tracabilite.formatter_strategy';
        $taggedServices = $container->findTaggedServiceIds($tag);
        $formatterStrategies = array();
        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);
            $reflection = new \ReflectionClass($def->getClass());
            if (!$reflection->isSubclassOf(AbstractTracabiliteFormatter::class)) {
                $msg =
                    'If you tagged your service as "'.$tag.'", '
                    .'you must therfore verify that it:'." \n"
                    ." \n".'- Implements  "'.AbstractTracabiliteFormatter::class.'"'
                    ."\n\n Class : ".$def->getClass()
                    ."\n Service : ".$id;

                throw new \LogicException($msg);
            }
            $formatterStrategies [] = new Reference($id);
        }
        $formatterManager = $container->getDefinition('tracabilite.egmhist.monolog.formatter');
        $formatterManager->addMethodCall(
            'setSubscribedFormattingStrategies',
            [$formatterStrategies]
        );
    }
}
