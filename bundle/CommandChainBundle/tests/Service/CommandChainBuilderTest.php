<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Service\CommandChain as CommandChainService;
use Ezi\CommandChainBundle\Service\ChainBuilder;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Ezi\CommandChainBundle\Tests\Functional\TestCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    /**
     * @return \array[][][][][][]
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

    private function getBadConfiguration(): array
    {
        return [
            "chains" => [
                "test2" => [
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
class TestExeptionCommand extends Command
{
}

#[AsCommand(
    name: 'test:command1',
    description: 'Console test:command1 command',
)]
class TestCommand1 extends Command
{
}

#[AsCommand(
    name: 'test:command2',
    description: 'Console test:command2 command',
)]
class TestCommand2 extends Command
{
}