<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
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

            $this->logger->info($name. ' run', [date('Y-M-D h:i:s')]);

            $result = $arr['command']?->run($arr['args'], $output);
            $out = $output->fetch();

            $this->logger->info($out, [date('Y-M-D h:i:s')]);
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


//[2016-01-29 09:08:31] foo:hello — главная команда цепочки команд, в которой зарегистрированы команды-члены
//[2016-01-29 09:08:31] bar:hi зарегистрирован как член цепочки команд foo:hello
//[2016-01-29 09:08:31] Сначала выполняется сама команда foo:hello:
//[2016-01-29 09:08:32] Привет от Фу!
//[2016-01-29 09:08:32] Выполнение членов цепочки foo:hello:
//[2016-01-29 09:08:32] Привет из Бара!
//[2016-01-29 09:08:32] Выполнение цепочки foo:hello завершено.

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