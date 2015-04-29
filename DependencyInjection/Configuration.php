<?php

namespace Fenrizbes\PublicationsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fenrizbes_publications');

        $rootNode
            ->children()
                ->scalarNode('date_format')
                    ->cannotBeEmpty()
                    ->defaultValue('F d, Y')
                ->end()

                ->scalarNode('datetime_format')
                    ->cannotBeEmpty()
                    ->defaultValue('F d, Y H:i')
                ->end()

                ->arrayNode('sonata_admin')
                    ->addDefaultsIfNotSet()

                    ->children()
                        ->scalarNode('group_label')
                            ->cannotBeEmpty()
                            ->defaultValue('fp.label.admin')
                        ->end()

                        ->scalarNode('content_editor')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
