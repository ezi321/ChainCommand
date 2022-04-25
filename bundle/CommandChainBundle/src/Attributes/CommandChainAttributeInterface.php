<?php

namespace Ezi\CommandChainBundle\Attributes;

/**
 * Interface that guarantee to getting configuration array after attribute class initialize
 */
interface CommandChainAttributeInterface
{
    /**
     * @return array
     */
    public function getConfiguration(): array;
}