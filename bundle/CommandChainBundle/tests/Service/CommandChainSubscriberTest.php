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
}
