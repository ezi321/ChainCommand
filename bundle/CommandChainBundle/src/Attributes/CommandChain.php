<?php

namespace Ezi\CommandChainBundle\Attributes;

/**
 * Attribute class that provide command chain configuration by attribute
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class CommandChain implements CommandChainAttributeInterface
{
    /**
     * Configuration from attribute
     * @var array[]
     */
    private array $configuration;

    /**
     * Configuration commands chain array from attribute provides to master command
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->configuration = ['commands' => $commands];
    }

    /**
     * Getter to configuration array
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}