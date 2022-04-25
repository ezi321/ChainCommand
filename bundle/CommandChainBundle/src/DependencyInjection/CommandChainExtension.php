<?php

namespace Ezi\CommandChainBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;


/**
 * Class to provide service configuration to container
 */
class CommandChainExtension extends Extension
{
    /**
     * Configuration alias constant
     */
    const ALIAS = 'chain_commands';

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configurartion = $this->getConfiguration($configs, $container);
        $config         = $this->processConfiguration($configurartion, $configs);

        $container->getDefinition(
            "ezi.command_chain_builder"
        )->setArgument(1, $config);
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return self::ALIAS;
    }
}