<?php

namespace Ezi\CommandChainBundle\Tests\Service;

use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandChainBuilderTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testChainBuilderWiring(): void
    {
        $builder = self::$container->get("ezi.command_chain_builder");
        $this->assertInstanceOf(ChainBuilderInterface::class, $builder);
    }

    public function testChainBuilding()
    {
        $this->markTestSkipped('Write later');
    }
}
