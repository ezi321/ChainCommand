<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Event\ConsoleEvent;

class ChainBuilder implements ChainBuilderInterface
{
    private LoggerInterface $logger;
    private CommandChain $commandChain;

    public function __construct(LoggerInterface $logger, CommandChain $emptyChain)
    {
        $this->logger = $logger;
        $this->$emptyChain = $emptyChain;
    }

    public function build(ConsoleEvent $event): CommandChain
    {
        // TODO: Implement build() method.
    }
}