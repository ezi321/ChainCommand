<?php

namespace Ezi\CommandChainBundle\Attributes;

use Ezi\CommandChainBundle\Service\ChainBuilder;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Ezi\CommandChainBundle\Service\CommandChain as ChainCommands;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CommandChain implements CommandChainAttributeInterface
{
    private array $configuration;

    /**
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->configuration = ['commands' => $commands];
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}