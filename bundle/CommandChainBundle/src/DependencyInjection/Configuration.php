<?php

namespace Ezi\CommandChainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

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

///*
//namespace SymfonyBundles\QueueBundle\DependencyInjection;
//
//use SymfonyBundles\QueueBundle\Service;
//use Symfony\Component\Config\Definition\Builder\TreeBuilder;
//use Symfony\Component\Config\Definition\ConfigurationInterface;
//use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
//
//class Configuration implements ConfigurationInterface
//{
//    public function getConfigTreeBuilder(): TreeBuilder
//    {
//        $builder = new TreeBuilder('chain_commands');
//        $rootNode = $builder->root();
//        $this->addChainSection($rootNode);
//
//        return $builder;
//    }
//
//    private function addServiceSection(ArrayNodeDefinition $node)
//    {
//        $node
//            ->children()
//            ->arrayNode('service')
//            ->addDefaultsIfNotSet()
//            ->children()
//            ->scalarNode('class')
//            ->defaultValue(Service\Queue::class)->cannotBeEmpty()
//            ->end()
//            ->scalarNode('alias')
//            ->defaultValue('queue')->cannotBeEmpty()
//            ->end()
//            ->scalarNode('storage')
//            ->defaultValue('redis')->cannotBeEmpty()
//            ->end()
//            ->end()
//            ->end()
//            ->end();
//    }
//
//    private function addSettingsSection(ArrayNodeDefinition $node)
//    {
//        $node
//            ->children()
//            ->arrayNode('settings')
//            ->addDefaultsIfNotSet()
//            ->children()
//            ->scalarNode('queue_default_name')
//            ->defaultValue('queue:default')->cannotBeEmpty()
//            ->end()
//            ->end()
//            ->end()
//            ->end();
//    }
//    private function addStoragesSection(ArrayNodeDefinition $node)
//    {
//        $node
//            ->children()
//            ->arrayNode('chains')
//            ->requiresAtLeastOneElement()
//            ->useAttributeAsKey('redis')
//            ->prototype('array')
//            ->children()
//                ->scalarNode('class')
//                ->defaultValue(Service\Storage\RedisStorage::class)->cannotBeEmpty()
//            ->end()
//            ->scalarNode('client')
//                ->defaultValue('sb_redis.client.default')->cannotBeEmpty()
//            ->end()
//            ->end()
//            ->end()
//            ->end()
//            ->end();
//    }
//}*/