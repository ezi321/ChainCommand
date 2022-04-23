<?php

namespace Ezi\CommandChainBundle\Attributes;

interface CommandChainAttributeInterface
{
    public function getConfiguration(): array;
}