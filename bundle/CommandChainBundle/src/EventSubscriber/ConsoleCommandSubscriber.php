<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        dd($event->getInput()->getArguments());
        //$reflect = new \ReflectionClass($command::class);
        //dd($command::class);
    }

    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }
}
