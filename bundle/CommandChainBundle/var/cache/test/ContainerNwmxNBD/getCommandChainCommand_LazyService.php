<?php

namespace ContainerNwmxNBD;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCommandChainCommand_LazyService extends Ezi_CommandChainBundle_Tests_KernelTestDebugContainer
{
    /**
     * Gets the private '.Ezi\CommandChainBundle\Command\CommandChainCommand.lazy' shared service.
     *
     * @return \Symfony\Component\Console\Command\LazyCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/console/Command/LazyCommand.php';

        return $container->privates['.Ezi\\CommandChainBundle\\Command\\CommandChainCommand.lazy'] = new \Symfony\Component\Console\Command\LazyCommand('command:chain', [], 'Console chain command', false, #[\Closure(name: 'Ezi\\CommandChainBundle\\Command\\CommandChainCommand')] function () use ($container): \Ezi\CommandChainBundle\Command\CommandChainCommand {
            return ($container->privates['Ezi\\CommandChainBundle\\Command\\CommandChainCommand'] ?? $container->load('getCommandChainCommandService'));
        });
    }
}
