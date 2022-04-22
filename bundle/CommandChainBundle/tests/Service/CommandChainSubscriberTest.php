<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\EventSubscriber\ConsoleCommandSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommandChainSubscriberTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testCommandChainSubscriberWiring(): void
    {
        $subscriber = self::$container->get("ezi.command_chain_subscriber");
        $this->assertInstanceOf(ConsoleCommandSubscriber::class, $subscriber);
    }

    public function testOnConsoleCommandSubscriber()
    {
        $this->markTestSkipped('Write later');
    }

    private function testGettingCommandAttributes()
    {
        $this->markTestSkipped('Write later');
    }

    private function testMergeConfiguration()
    {
        $this->markTestSkipped('Write later');
    }

    public function testIsCommandIsInChain()
    {
        $this->markTestSkipped('Write later');
    }
}
