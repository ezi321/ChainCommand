<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Ezi\CommandChainBundle\Exception\EmptyMasterCommandException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Implementation of command chain interface
 * @see CommandChainInterface
 */
class CommandChain implements CommandChainInterface
{
    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @var array $commandQueue
     */
    private array $commandQueue;

    /**
     * @var array $master
     */
    private array $master;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->commandQueue = [];
    }

    /**
     * Method that simplify executing and logging chain commands,
     * as chain is one composite command
     *
     * @param OutputInterface $output
     * @return int
     * @throws CommandExecutionException
     */
    public function execute(OutputInterface $output): int
    {
        $result = Command::SUCCESS;

        $output = $this->executeMasterCommand($output);
        $this->executeCommands($output);

        $this->logger->info(
            reset($this->commandQueue)['command']->getName() .
            ' end executing'
        );

        return $result;
    }

    /**
     * Method that push child command to chain
     *
     * @param Command $command
     * @param ArrayInput|InputInterface|null $args
     * @return CommandChain
     * @throws EmptyMasterCommandException
     */
    public function pushCommand(Command $command, ArrayInput|InputInterface $args = null): CommandChain
    {
        if(!isset($this->master['command'])) {
            throw new EmptyMasterCommandException(
                'Set master command before!'
            );
        }

        $name       = $command->getName();
        $masterName = $this->master['command']->getName();

        $this->logger->info(
            "{$name} registered as a member of ".
            "{$masterName} command chain"
        );
        $this->commandQueue[] = ['command' => $command, 'args' => $args];

        return $this;
    }

    /**
     * Method that set master command that executing first
     * @param Command $command
     * @param ArrayInput|InputInterface|null $args
     * @return $this
     */
    public function setMasterCommand(Command $command, ArrayInput|InputInterface $args = null): CommandChain
    {
        $name = $command->getName();
        $this->logger->info(
            "{$name} is a master command of "     .
            "a command chain that has registered ".
            "member commands"
        );
        $this->master = ['command' => $command, 'args' => $args];
        return $this;
    }

    /**
     * Getter to master command field
     * @return Command|null
     */
    public function getMasterCommand(): ?Command
    {
        return $this->master['command'];
    }

    /**
     * Getter to command chain array[Command, InputInterface]
     * @return array<Command, InputInterface>
     */
    public function getCommandQueue(): array
    {
        return $this->commandQueue;
    }

    /**
     * Method that execute master command
     * @param OutputInterface $output
     * @return OutputInterface
     * @throws CommandExecutionException
     */
    private function executeMasterCommand(OutputInterface $output): OutputInterface
    {
        $command = $this->master['command'];
        $name = $command->getName();
        $args = $this->master['args'];
        $this->logger->info("Executing {$name} command itself first");
        return $this->run($command, $args, $output);
    }

    /**
     * Method that execute child chain commands
     * @param OutputInterface $output
     * @return OutputInterface
     * @throws CommandExecutionException
     */
    private function executeCommands(OutputInterface $output): OutputInterface
    {
        $chainName = $this->master['command']->getName();
        
        $this->logger->info("Executing {$chainName} chain members:");
        foreach ($this->commandQueue as $name => $commandWithArgs) {

            $command = $commandWithArgs['command'];
            $args = $commandWithArgs['args'];
            $this->run($command, $args, $output);
        }
        $this->logger->info("Execution of {$chainName} chain completed.");
        return $output;
    }

    /**
     * Method that run one command and return output buffer
     * @param Command $command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return OutputInterface
     * @throws CommandExecutionException
     */
    private function run(Command $command, InputInterface $input, OutputInterface $output): OutputInterface
    {
        $result = $command->run($input, $output);
        $out = $output->fetch();

        $this->logger->info(trim($out));

        $output->write($out);

        if ($result !== Command::SUCCESS) {
            throw new CommandExecutionException();
        }
        
        return $output;
    }
}