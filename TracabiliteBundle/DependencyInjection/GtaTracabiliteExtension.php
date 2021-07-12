<?php

namespace Gta\TracabiliteBundle\DependencyInjection;

use Gta\CoreBundle\Tests\Utils\Tracabilite\TracabiliteTest;
use Gta\TracabiliteBundle\Event\Listener\ApiJournalListener;
use Gta\TracabiliteBundle\Log\Formatter\AbstractDbTableLogFormatter;
use Gta\CoreBundle\Log\HasLoggingFeatureInterface;
use Gta\TracabiliteBundle\Manager\TracabiliteFirewall;
use Gta\TracabiliteBundle\Manager\TracabiliteManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GtaTracabiliteExtension extends Extension
{
    const TRACEABILITY = 'traceability';
    const CRUD         = 'crud';
    const API          = 'api';

    /**
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->applyCrudConfig($config, $container);
        $this->applyApiInvokeConfig($config, $container);
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getAlias()
    {
        return 'gta_tracabilite';
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyCrudConfig(array $config, ContainerBuilder $container)
    {
        $serviceId1 = TracabiliteManager::class;
        if ($container->hasDefinition($serviceId1)) {
            $def1 = $container->getDefinition($serviceId1);
            $def1->addMethodCall(
                'setLoggingDisabled',
                [!$config[self::TRACEABILITY][self::CRUD]['enabled']]
            );
        }

        $serviceId2 = 'tracabilite.egmhist.monolog.formatter';
        if ($container->hasDefinition($serviceId2)) {
            $def2 = $container->getDefinition($serviceId2);
            $def2->addMethodCall(
                'setDbTableName',
                [$config[self::TRACEABILITY][self::CRUD]['table_name']]
            );
            $def2->addMethodCall(
                'setStrategiesList',
                [$config[self::TRACEABILITY][self::CRUD]['actions']]
            );

        }

        return $container;
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyApiInvokeConfig(array $config, ContainerBuilder $container)
    {
        if ($container->hasDefinition(ApiJournalListener::class)) {
            $def1 = $container->getDefinition(ApiJournalListener::class);
            if (in_array(
                HasLoggingFeatureInterface::class,
                class_implements(
                    ApiJournalListener::class
                )
            )) {
                $def1->addMethodCall(
                    'setLoggingDisabled',
                    [!$config[self::TRACEABILITY][self::API]['enabled']]
                );
            }
        }

        if ($container->hasDefinition('tracabilite.api_journal.monolog.formatter')) {
            $def2 = $container->getDefinition('tracabilite.api_journal.monolog.formatter');
            if (in_array(
                AbstractDbTableLogFormatter::class,
                class_parents(
                    $def2->getClass()
                )
            )) {
                $def2->addMethodCall(
                    'setDbTableName',
                    [$config[self::TRACEABILITY][self::API]['table_name']]
                );
            }
        }

        return $container;
    }
}
