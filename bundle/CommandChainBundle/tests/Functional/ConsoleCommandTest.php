<?php

namespace Ezi\CommandChainBundle\Tests\Functional;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Tester\CommandTester;

class ConsoleCommandTest extends KernelTestCase
{
    private static Application $app;

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
        self::$app = new Application(self::$kernel);
        self::$app->add(new TestCommand());
        self::$app->add(new TestCommand1());
        self::$app->add(new TestCommand2());
    }

    public function testBundleWiring(): void
    {
//        $command = self::$app->find('command:chain');
//        $commandTester = new CommandTester($command);
//        $commandTester->execute([]);
//
//        $commandTester->assertCommandIsSuccessful();
//
//        // the output of the command in the console
//        $output = $commandTester->getDisplay();
//        $this->assertStringContainsString('Command chain', $output);
        $this->markTestSkipped('Move console command from bundle');
    }

    private function configurationProvider()
    {
        return [[
            'configuration' => [

            ]
        ]];
    }
}

#[AsCommand(
    name: 'test:command:chain',
    description: 'Console chain command',
)]
#[CommandChain(commands: ['foo:command' => [], 'bar:command' => []])]
class TestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Command chain');

        return Command::SUCCESS;
    }
}

#[AsCommand(
    name: 'test:command1',
    description: 'Console chain command',
)]
#[CommandChain(commands: ['foo:command' => [], 'bar:command' => []])]
class TestCommand1 extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('TestCommand1');

        return Command::SUCCESS;
    }
}

#[AsCommand(
    name: 'test:command2',
    description: 'Console chain command',
)]
#[CommandChain(commands: ['foo:command' => [], 'bar:command' => []])]
class TestCommand2 extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('TestCommand2');

        return Command::SUCCESS;
    }
}