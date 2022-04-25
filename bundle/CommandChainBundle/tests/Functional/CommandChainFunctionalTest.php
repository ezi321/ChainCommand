<?php

namespace Ezi\CommandChainBundle\Tests\Functional;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Exception\NotExecutableCommandException;
use Ezi\CommandChainBundle\Service\CommandChain as CommandChainService;
use Ezi\CommandChainBundle\Service\ChainBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CommandChainFunctionalTest extends KernelTestCase
{
    private CommandChainService $commandChain;
    private ChainBuilder $builder;
    private Application $app;

    protected function setUp(): void
    {
        self::$kernel       = self::bootKernel();
        self::$container    = self::$kernel->getContainer();

        $this->app = new Application(self::$kernel);
        $this->app->add(new TestCommandChain());
        $this->app->add(new TestCommand1());
        $this->app->add(new TestCommand2());

        $logger             = $this->createMock(LoggerInterface::class);
        $this->commandChain = new CommandChainService($logger);
        $this->builder      = new ChainBuilder($this->commandChain, $this->getConfiguration());
    }

    /**
     * @return void
     */
    public function testChainCommandOutputContainsAllOutputsCommansFromChain()
    {
        $master = $this->app->get('test:command:chain');
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();
        $commandChain = $this->builder->build($master, $input);
        $commandChain->execute($output);
        $out = $output->fetch();
        $this->assertTrue(str_contains($out, 'test:command:chain'));
        $this->assertTrue(str_contains($out, 'test:command1'));
        $this->assertTrue(str_contains($out, 'test:command2'));
    }

    /**
     * @return void
     */
    public function testFailExecutionMasterThatContainsInChain()
    {
        $this->expectException(NotExecutableCommandException::class);
        $this->expectExceptionMessage(
            'Error: test:command1 command is a member of '.
            'test:command:chain command chain and cannot be executed on its own.'
        );

        $master = $this->app->get('test:command1');
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();
        $commandChain = $this->builder->build($master, $input);
        $commandChain->execute($output);
    }

    /**
     * @return \array[][][][]
     */
    private function getConfiguration(): array
    {
        return [
            "chains" => [
                "test:command:chain" => [
                    "commands" => [
                        "test:command1" => [],
                        "test:command2" => []
                   ]
                ]
           ]
        ];
    }
}

#[AsCommand(
    name: 'test:command:chain',
)]
#[CommandChain(commands: ['test:command1' => [], 'test:command2' => []])]
class TestCommandChain extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->write('test:command:chain');
        return Command::SUCCESS;
    }
}

#[AsCommand(
    name: 'test:command1',
)]
class TestCommand1 extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->write('test:command1');
        return Command::SUCCESS;
    }
}

#[AsCommand(
    name: 'test:command2',
)]
class TestCommand2 extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->write('test:command2');
        return Command::SUCCESS;
    }
}