<?php

namespace Matmar10\Bundle\RestApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ParentNodeDefinitionInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('matmar10_rest_api')
            ->children()

                ->scalarNode('serialize_type')
                    ->cannotBeEmpty()
                    ->defaultValue('json')
                ->end()

                ->integerNode('success_status_code')
                    ->min(0)
                    ->max(999)
                    ->defaultValue(200)
                    ->cannotBeEmpty()
                ->end()

                ->integerNode('exception_status_code')
                    ->min(0)
                    ->max(999)
                    ->defaultValue(500)
                    ->cannotBeEmpty()
                ->end()

                ->arrayNode('groups')
                    ->requiresAtLeastOneElement()
                    ->treatNullLike(array())
                    ->prototype('scalar')->end()
                    ->defaultValue(array('all'))
                ->end()

                ->arrayNode('groups_debug')
                    ->requiresAtLeastOneElement()
                    ->treatNullLike(array())
                    ->prototype('scalar')->end()
                    ->defaultValue(array('debug'))
                ->end()

                ->arrayNode('content_types')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('value')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()

            ->end();
        return $treeBuilder;
    }

}