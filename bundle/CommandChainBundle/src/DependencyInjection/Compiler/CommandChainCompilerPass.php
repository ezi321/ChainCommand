<?php

namespace Ezi\CommandChainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class that used to process container configuration
 */
class CommandChainCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        // TODO: Implement process() method.
    }
}