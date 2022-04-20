<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ChainBuilderInterface $chainBuilder;
    private array $configuration;

    public function __construct(LoggerInterface $logger, ChainBuilderInterface $chainBuilder, array $configuration)
    {
        $this->logger = $logger;
        $this->chainBuilder = $chainBuilder;
        $this->configuration = $configuration;
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $app = $command->getApplication();

        $this->mergeConfiguration($command);

        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        //$command = $reflect->newInstance();
//        $command->run($event->getInput(), $output);
//        $foo = $app->find('bar:command');
//        $bar = $app->find('foo:command');
//
//        $foo->run($input, $output);
//        $bar->run($input, $output);
//
//
//        dd($output->fetch());



//        $input = new ArrayInput([
//            'bar'
//        ]);
//        $app->run($input, $output);
//        $event->disableCommand();



//        foreach ($attributes as $attribute) {
//            $chain = $attribute->newInstance();
//            dd($chain);
//        }
        //dd($attributes);
    }

    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }

    private function mergeConfiguration(Command $command)
    {
        $reflect = new \ReflectionClass($command::class);
        $attributes = $reflect->getAttributes(CommandChain::class);

        if(!empty($attributes)){
            $attribute = array_shift($attributes);
            if($attribute instanceof \ReflectionAttribute) {
                $config = [
                    $command->getName() => $attribute->newInstance()->getConfiguration()
                ];
                $this->configuration = array_merge($config, $this->configuration);
            }
        }
    }
}
