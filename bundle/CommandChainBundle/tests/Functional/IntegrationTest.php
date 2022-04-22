<?php

namespace Ezi\CommandChainBundle\Tests\Functional;

use Ezi\CommandChainBundle\CommandChainBundle;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class IntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    public function testBundleWiring(): void
    {
        $bundle = self::$kernel->getBundle('CommandChainBundle');
        $this->assertInstanceOf(CommandChainBundle::class, $bundle);
//        $application = new Application($kernel);
//        $command = $application->find('command:chain');
//        $commandTester = new CommandTester($command);
//        $commandTester->execute([]);
//
//        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
//        $output = $commandTester->getDisplay();
//        $this->assertStringContainsString('Username: Wouter', $output);

    }
}