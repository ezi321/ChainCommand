<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommandChainTest extends KernelTestCase
{
    public function commandChainProvider()
    {

    }

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testCommandChainWiring(): void
    {
        $subscriber = self::$container->get("ezi.command_chain");
        $this->assertInstanceOf(CommandChainInterface::class, $subscriber);
    }

    public function testCommandChainInitialize()
    {
        $this->markTestSkipped('Write later');
    }

    public function testExecution()
    {
        $this->markTestSkipped('Write later');
    }

    public function testPushingCommand()
    {
        $this->markTestSkipped('Write later');
    }
}
