<?php

namespace Ezi\CommandChainBundle;

use Ezi\CommandChainBundle\DependencyInjection\Compiler\CommandChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Ezi\CommandChainBundle\DependencyInjection\CommandChainExtension;

/**
 * Main command chain bundle class
 */
class CommandChainBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CommandChainCompilerPass());
    }

    /**
     * @return ExtensionInterface|null
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new CommandChainExtension();
        }
        return $this->extension;
    }
}