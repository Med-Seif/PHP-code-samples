<?php

namespace Gta\TracabiliteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gta_tracabilite');

        $rootNode
            ->children()
                ->arrayNode('traceability')
                    ->children()
                        ->arrayNode('crud')
                            ->children()
                                ->scalarNode('table_name')->isRequired()->end()
            ->booleanNode('enabled')->defaultFalse()->end()
                            ->arrayNode('actions')
                                ->children()
            ->booleanNode('fiche')->defaultFalse()->end()
            ->booleanNode('contrat')->defaultFalse()->end()
            ->booleanNode('droit_conge')->defaultFalse()->end()
            ->booleanNode('deplacement')->defaultFalse()->end()
            ->booleanNode('deplacement_exceptionnel')->defaultFalse()->end()
            ->booleanNode('affectation')->defaultFalse()->end()
            ->booleanNode('init_compteur')->defaultFalse()->end()
            ->booleanNode('maj_tta')->defaultFalse()->end()
            ->booleanNode('repartition_tta')->defaultFalse()->end()
            ->booleanNode('validation_tableau')->defaultFalse()->end()
            ->booleanNode('valott')->defaultFalse()->end()
            ->booleanNode('pose_horaire')->defaultFalse()->end()
            ->booleanNode('couverture')->defaultFalse()->end()
            ->booleanNode('couverture_affectation_couvrant')->defaultFalse()->end()
            ->booleanNode('maj_activite')->defaultFalse()->end()
            ->booleanNode('multi_pose')->defaultFalse()->end()
            ->booleanNode('copier_coller')->defaultFalse()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                        ->arrayNode('api')
                            ->children()
                                ->scalarNode('table_name')->end()
                                ->booleanNode('enabled')->defaultTrue()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
