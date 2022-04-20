<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Event\ConsoleEvent;

class ChainBuilder implements ChainBuilderInterface
{
    private LoggerInterface $logger;
    private array $configuration;
    private CommandChain $commandChain;

    public function __construct(LoggerInterface $logger, array $configuration)
    {
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function build(ConsoleEvent $event): CommandChain
    {
        $config = $this->getConfiguration();
    }
}