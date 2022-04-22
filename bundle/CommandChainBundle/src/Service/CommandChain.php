<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
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

            $this->logger->info($name. ' run');

            $result = $arr['command']?->run($arr['args'], $output);
            $out = $output->fetch();

            $this->logger->info(trim($out));
            $output->write($out);

            if ($result !== Command::SUCCESS) {
                throw new CommandExecutionException();
            }
        }

        $this->logger->info(
            reset($this->commandQueue)['command']->getName() . ' end executing',
            [date('Y-M-D h:i:s')]
        );

        return $result;
    }

    /**
     * @param Command $command
     * @param ArrayInput|InputInterface|null $args
     * @return CommandChain
     */
    public function pushCommand(Command $command, ArrayInput|InputInterface $args = null): CommandChain
    {
        $this->logger->info($command->getName() . ' registred', [date('Y-M-D h:i:s')]);
        $this->commandQueue[] = ['command' => $command, 'args' => $args];
        return $this;
    }
}