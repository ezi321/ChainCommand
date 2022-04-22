<?php

namespace Ezi\CommandChainBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ConsoleCommandTest extends KernelTestCase
{
    private static Application $app;

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        self::$container = self::$kernel->getContainer();
        self::$app = new Application(self::$kernel);
    }

    public function testBundleWiring(): void
    {
        $command = self::$app->find('command:chain');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Command chain', $output);
    }
}