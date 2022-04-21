<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class CommandChain implements CommandChainInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var array
     */
    private array $commandQueue;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->commandQueue = [];
    }

    /**
     * @param OutputInterface $output
     * @return int
     * @throws CommandExecutionException
     */
    public function execute(OutputInterface $output): int
    {
        $result = Command::SUCCESS;
        foreach ($this->commandQueue as $name => $arr) {
            $result = $arr['command']?->execute($arr['args'], $output);
            if ($result !== Command::SUCCESS) {
                throw new CommandExecutionException();
            }
        }

        return $result;
    }

    /**
     * @param Command $command
     * @param ArrayInput|null $args
     * @return CommandChain
     */
    public function pushCommand(Command $command, ArrayInput $args = null): CommandChain
    {
        $this->commandQueue[$command->getName()] = ['command' => $command, 'args' => $args];
        return $this;
    }
}