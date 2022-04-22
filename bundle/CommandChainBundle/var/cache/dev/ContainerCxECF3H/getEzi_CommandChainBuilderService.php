<?php

namespace ContainerCxECF3H;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getEzi_CommandChainBuilderService extends Ezi_CommandChainBundle_Tests_TestKernelDevDebugContainer
{
    /**
     * Gets the public 'ezi.command_chain_builder' shared service.
     *
     * @return \Ezi\CommandChainBundle\Service\ChainBuilder
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/Service/ChainBuilderInterface.php';
        include_once \dirname(__DIR__, 4).'/src/Service/ChainBuilder.php';

        return $container->services['ezi.command_chain_builder'] = new \Ezi\CommandChainBundle\Service\ChainBuilder(($container->privates['monolog.logger'] ?? $container->load('getMonolog_LoggerService')), ($container->services['ezi.command_chain'] ?? $container->load('getEzi_CommandChainService')), ['chains' => []]);
    }
}
