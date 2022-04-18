<?php

namespace Ezi\BarBundle;

use Ezi\CommandChainBundle\DependencyInjection\Compiler\CommandChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Ezi\CommandChainBundle\DependencyInjection\CommandChainExtension;

class BarBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CommandChainCompilerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new CommandChainExtension();
        }
        return $this->extension;
    }
}