<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Service\CommandChain as CommandChainService;
use Ezi\CommandChainBundle\Service\ChainBuilder;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;

class CommandChainBuilderTest extends KernelTestCase
{
    private $commandChain;
    private $builder;
    private $logger;
    private static $app;

    protected function setUp(): void
    {
        self::$kernel       = self::bootKernel();
        self::$container    = self::$kernel->getContainer();
        self::$app = new Application(self::$kernel);
        self::$app->add(new TestCommandChain());
        self::$app->add(new TestCommand1());
        self::$app->add(new TestCommand2());
        self::$app->add(new TestBadCommand());
        $this->logger       = $this->createMock(LoggerInterface::class);
        $this->commandChain = new CommandChainService($this->logger);
        $this->builder      = new ChainBuilder($this->commandChain, $this->getConfiguration());
    }

    /**
     * @return void
     */
    public function testChainBuilderInstatntiable()
    {
        $this->assertInstanceOf(ChainBuilder::class, $this->builder);
    }

    public function testGetConfigurationFromBuilder()
    {
        $this->assertEquals($this->getConfiguration(), $this->builder->getConfiguration());
    }

    public function testBuldChain()
    {
        $commandChain = $this->builder->build(self::$app->find("test:command:chain"), new ArrayInput([]));
        $this->assertInstanceOf(CommandChainInterface::class, $commandChain);
    }

    public function testBuildChainLength()
    {
        $commandChain = $this->builder->build(self::$app->find("test:command:chain"), new ArrayInput([]));
        $this->assertCount(3, $commandChain?->getCommandQueue());
    }

    /**
     * @param Command $mainCommand
     * @param Command $command1
     * @param Command $command2
     * @return void
     * @throws \Ezi\CommandChainBundle\Exception\NotExecutableCommandException
     * @throws \ReflectionException
     * @dataProvider commandsDataProvider
     */
    public function testIsAllCommandContainsInChain(Command $mainCommand, Command $command1, Command $command2)
    {
        $commandChain = $this->builder->build($mainCommand, new ArrayInput([]));

        $commands = $commandChain->getCommandQueue();

        $this->assertEquals($mainCommand->getName(), array_shift($commands)['command']->getName());
        $this->assertEquals($command1->getName(), array_shift($commands)['command']->getName());
        $this->assertEquals($command2->getName(), array_shift($commands)['command']->getName());
    }

    /**
     * @param Command $mainCommand
     * @param Command $command1
     * @param Command $command2
     * @return void
     * @throws \Ezi\CommandChainBundle\Exception\NotExecutableCommandException
     * @throws \ReflectionException
     * @dataProvider badCommandDataProvider
     */
    public function testIsNotChainCommandNotInChain(Command $mainCommand, Command $badCommand)
    {
        $commandChain = $this->builder->build($mainCommand, new ArrayInput([]));
        $commands = $commandChain->getCommandQueue();

        foreach ($commands as $item) {
            $this->assertFalse(in_array($badCommand->getName(), $item));
        }
    }

    private function commandsDataProvider()
    {
        $this->setUp();
        $mainCommand = self::$app->find("test:command:chain");
        $command1 = self::$app->find("test:command1");
        $command2 = self::$app->find("test:command2");
        return [
            [$mainCommand, $command1, $command2]
        ];
    }

    /**
     * @return void
     * @dataProvider badCommandDataProvider
     */
    public function testCommandNotFoundExcetion(Command $mainCommand)
    {
        $this->expectExceptionMessage("Command not found");
        $this->builder = new ChainBuilder($this->commandChain, $this->getBadConfiguration());
        $this->builder->build($mainCommand, new ArrayInput([]));
    }

    private function badCommandDataProvider()
    {
        $this->setUp();
        $mainCommand = self::$app->find("test:command:chain");
        $badCommand = self::$app->find("qwe");
        return [
            [$mainCommand, $badCommand]
        ];
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

    /**
     * @return \array[][][][]
     */
    private function getBadConfiguration(): array
    {
        return [
            "chains" => [
                "test:command:chain" => [
                    "commands" => [
                        "bar:command" => [],
                        "foo:command" => []
                    ]
                ]
            ]
        ];
    }
}

#[AsCommand(
    name: 'test:command:chain',
    description: 'Console test:command:chain command',
)]
#[CommandChain(commands: ['test:command1' => [], 'test:command2' => []])]
class TestCommandChain extends Command
{
}

#[AsCommand(
    name: 'qwe',
    description: 'Console test:command:chain command',
)]
#[CommandChain(commands: ['test:command1' => [], 'test:command2' => []])]
class TestBadCommand extends Command
{
}

#[AsCommand(
    name: 'test:command1',
)]
class TestCommand1 extends Command
{
}

#[AsCommand(
    name: 'test:command2',
)]
class TestCommand2 extends Command
{
}