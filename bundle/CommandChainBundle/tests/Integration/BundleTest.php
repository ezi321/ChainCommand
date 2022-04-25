<?php

namespace Ezi\CommandChainBundle\Tests\Integration;

use Ezi\CommandChainBundle\CommandChainBundle;
use Ezi\CommandChainBundle\EventSubscriber\ConsoleCommandSubscriber;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BundleTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testBundleExists(): void
    {
        $bundle = self::$kernel->getBundle('CommandChainBundle');
        $this->assertInstanceOf(CommandChainBundle::class, $bundle);
    }

    public function testChainBuilderWiring(): void
    {
        $builder = self::$container->get("ezi.command_chain_builder");
        $this->assertInstanceOf(ChainBuilderInterface::class, $builder);
    }

    public function testCommandChainWiring(): void
    {
        $subscriber = self::$container->get("ezi.command_chain");
        $this->assertInstanceOf(CommandChainInterface::class, $subscriber);
    }

    public function testCommandChainSubscriberWiring(): void
    {
        $subscriber = self::$container->get("ezi.command_chain_subscriber");
        $this->assertInstanceOf(ConsoleCommandSubscriber::class, $subscriber);
    }
}