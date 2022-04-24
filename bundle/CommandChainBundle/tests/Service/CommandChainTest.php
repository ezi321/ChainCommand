<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Service\ChainBuilder;
use Ezi\CommandChainBundle\Service\CommandChain as CommandChainService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

class CommandChainTest extends KernelTestCase
{
    private $commandChain;
    private $logger;
    private $app;

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
    }

    /**
     * @return void
     */
    public function testChainCommandInstatntiable()
    {
        $this->assertInstanceOf(CommandChainService::class, $this->commandChain);
    }

    public function testSetMasterCommand()
    {
        $this->commandChain->setMasterCommand($this->app->find('test:command:chain'));
        $this->assertEquals($this->app->find('test:command:chain'), $this->commandChain->getMasterCommand());
    }

    public function testPushingCommandToChain()
    {
        
    }

    public function testPushingCommandToChainWithEmptyMaster()
    {

    }

    
}

#[AsCommand(
    name: 'test:command:chain',
    description: 'Console test:command:chain command',
)]
#[CommandChain(commands: ['test:command1' => [], 'test:command2' => []])]
class CommandChain1 extends Command
{
}

#[AsCommand(
    name: 'qwe',
    description: 'Console test:command:chain command',
)]
#[CommandChain(commands: ['test:command1' => [], 'test:command2' => []])]
class BadCommand1 extends Command
{
}

#[AsCommand(
    name: 'test:command1',
)]
class Command1 extends Command
{
}

#[AsCommand(
    name: 'test:command2',
)]
class Command2 extends Command
{
}