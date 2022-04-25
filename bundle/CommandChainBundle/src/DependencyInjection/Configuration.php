<?php

namespace Ezi\CommandChainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class to validate and processing bundle configuration
 */
class Configuration implements ConfigurationInterface
{

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder('chain_commands');
        $root = $tree->getRootNode();
        $root
            ->children()
                ->arrayNode('chains')
                    ->useAttributeAsKey('chain')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('commands')
                            ->useAttributeAsKey('command')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('args')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $tree;
    }
}