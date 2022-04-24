<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Ezi\CommandChainBundle\Exception\EmptyMasterCommandException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function getMasterCommand(): ?Command
    {
        return $this->master['command'];
    }

    /**
     * @return array
     */
    public function getCommandQueue(): array
    {
        return $this->commandQueue;
    }

    private function executeMasterCommand(OutputInterface $output): OutputInterface
    {
        $command = $this->master['command'];
        $name = $command->getName();
        $args = $this->master['args'];
        $this->logger->info("Executing {$name} command itself first");
        return $this->run($command, $args, $output);
    }

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