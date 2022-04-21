<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\NonExistentChainException;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\ArrayInput;

class ChainBuilder implements ChainBuilderInterface
{
    private LoggerInterface $logger;
    private array $configuration;
    private CommandChainInterface $commandChain;

    public function __construct(LoggerInterface $logger, CommandChainInterface $commandChain, array $configuration)
    {
        $this->logger = $logger;
        $this->commandChain = $commandChain;
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function build(ConsoleEvent $event): CommandChainInterface
    {
        $config = $this->getConfiguration();
        $mainCommand = $event->getCommand();
        $app = $event->getCommand()->getApplication();

        $chainName = $event->getInput()->getFirstArgument();
        $chainName = $chainName ?? 'default';

        if(!array_key_exists($chainName, $config['chains'])) {
            throw new NonExistentChainException();
        }

        $this->commandChain->pushCommand($mainCommand, $event->getInput());

        foreach ($config['chains'][$chainName]['commands'] as $commandName => $args)
        {
            $command = $app->find($commandName);
            $arrayInput = new ArrayInput($args);
            $this->commandChain->pushCommand($command, $arrayInput);
        }

        return $this->commandChain;
    }
}