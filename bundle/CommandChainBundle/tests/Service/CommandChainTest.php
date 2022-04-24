<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Exception\EmptyMasterCommandException;
use Ezi\CommandChainBundle\Service\CommandChain as CommandChainService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;

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
        $this->app->add(new CommandChain12());
        $this->app->add(new Command12());
        $this->app->add(new Command22());
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

    /**
     * @dataProvider commandsDataProvider
     */
    public function testSetMasterCommand(Command $master)
    {
        $this->commandChain->setMasterCommand($master);
        $this->assertEquals($master, $this->commandChain->getMasterCommand());
        $this->commandChain->setMasterCommand($master, new ArrayInput([]));
        $this->assertEquals($master, $this->commandChain->getMasterCommand());
    }

    /**
     * @dataProvider commandsDataProvider
     */
    public function testPushingCommandToChain(Command $master, Command $command1, Command $command2)
    {
        $this->commandChain->setMasterCommand($master);
        $this->commandChain->pushCommand($command1);
        $this->commandChain->pushCommand($command2);
        $this->assertCount(2, $this->commandChain->getCommandQueue());
    }

    /**
     * @dataProvider commandsDataProvider
     */
    public function testPushingCommandToChainWithEmptyMaster(Command $master, Command $command1, Command $command2)
    {
        $this->expectException(EmptyMasterCommandException::class);
        $this->expectExceptionMessage('Set master command before!');
        $this->commandChain->pushCommand($command1);
        $this->commandChain->pushCommand($command2);
    }

    /**
     * @dataProvider commandsDataProvider
     */
    public function testCommandQueueContainsCommandsAndArguments(Command $master, Command $command1, Command $command2)
    {
        $in = new ArrayInput([]);
        $this->commandChain->setMasterCommand($master);
        $this->commandChain->pushCommand($command1, $in);
        $this->commandChain->pushCommand($command2, $in);
        $this->assertEquals($this->commandChain->getCommandQueue()[0]['command'], $command1);
        $this->assertEquals($this->commandChain->getCommandQueue()[1]['command'], $command2);
        $this->assertEquals($this->commandChain->getCommandQueue()[0]['args'], $in);
        $this->assertEquals($this->commandChain->getCommandQueue()[1]['args'], $in);
    }

    /**
     * @return array[][]
     */
    private function commandsDataProvider()
    {
        $this->setUp();
        $mainCommand = $this->app->find('test:command:chain2');
        $command1 = $this->app->find('test:command12');
        $command2 = $this->app->find("test:command22");
        return [
            [$mainCommand, $command1, $command2]
        ];
    }

}

#[AsCommand(
    name: 'test:command:chain2',
)]
class CommandChain12 extends Command
{
}

#[AsCommand(
    name: 'test:command12',
)]
class Command12 extends Command
{
}

#[AsCommand(
    name: 'test:command22',
)]
class Command22 extends Command
{
}