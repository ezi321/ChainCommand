<?php

namespace Ezi\FooBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Ezi\FooBundle\DependencyInjection\FooExtension;

class FooBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new FooExtension();
        }
        return $this->extension;
    }
}