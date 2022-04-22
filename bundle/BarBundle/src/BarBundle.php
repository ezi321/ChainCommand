<?php

namespace Ezi\BarBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Ezi\BarBundle\DependencyInjection\BarExtension;

class BarBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new BarExtension();
        }
        return $this->extension;
    }
}