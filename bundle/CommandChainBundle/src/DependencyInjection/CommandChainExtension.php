<?php

namespace Ezi\CommandChainBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CommandChainExtension extends Extension
{
    const ALIAS = 'chain_commands';

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $configurartion = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configurartion, $configs);

        $container->getDefinition(
            "ezi.command_chain_subscriber"
        )->setArgument(2, $config);
        $container->getDefinition(
            "ezi.command_chain_builder"
        )->setArgument(1, $config);
    }

    public function getAlias()
    {
        return self::ALIAS;
    }
}