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
    private CommandChainService $commandChain;
    private ChainBuilder $builder;
    private $logger;
    private Application $app;

    protected function setUp(): void
    {
        self::$kernel       = self::bootKernel();
        self::$container    = self::$kernel->getContainer();
        $this->app = new Application(self::$kernel);
        $this->app->add(new TestCommandChain());
        $this->app->add(new TestCommand1());
        $this->app->add(new TestCommand2());
        $this->app->add(new TestBadCommand());
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
        $commandChain = $this->builder->build($this->app->find("test:command:chain"), new ArrayInput([]));
        $this->assertInstanceOf(CommandChainInterface::class, $commandChain);
    }

    public function testBuildChainLength()
    {
        $commandChain = $this->builder->build($this->app->find("test:command:chain"), new ArrayInput([]));
        $this->assertCount(2, $commandChain?->getCommandQueue());
    }

    /**
     * @dataProvider commandsDataProvider
     */
    public function testIsMasterCommandIsEqMaster(Command $masterCommand)
    {
        $commandChain = $this->builder->build($masterCommand, new ArrayInput([]));

        $this->assertEquals($commandChain->getMasterCommand(), $masterCommand);
    }

    /**
     * @dataProvider commandsDataProvider
     */
    public function testIsAllCommandContainsInChain(Command $masterCommand, Command $command1, Command $command2)
    {
        $commandChain = $this->builder->build($masterCommand, new ArrayInput([]));

        $commands = $commandChain->getCommandQueue();

        $this->assertEquals($masterCommand->getName(), $commandChain->getMasterCommand()->getName());
        $this->assertEquals($command1->getName(), array_shift($commands)['command']->getName());
        $this->assertEquals($command2->getName(), array_shift($commands)['command']->getName());
    }

    /**
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

    /**
     * @dataProvider badCommandDataProvider
     */
    public function testCommandNotFoundExcetion(Command $mainCommand)
    {
        $this->expectExceptionMessage("Command not found");
        $this->builder = new ChainBuilder($this->commandChain, $this->getBadConfiguration());
        $this->builder->build($mainCommand, new ArrayInput([]));
    }

    /**
     * @return array[][]
     */
    private function commandsDataProvider()
    {
        $this->setUp();
        $mainCommand = $this->app->find("test:command:chain");
        $command1 = $this->app->find("test:command1");
        $command2 = $this->app->find("test:command2");
        return [
            [$mainCommand, $command1, $command2]
        ];
    }

    /**
     * @return array[][]
     */
    private function badCommandDataProvider()
    {
        $this->setUp();
        $mainCommand = $this->app->find("test:command:chain");
        $badCommand = $this->app->find("qwe");
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