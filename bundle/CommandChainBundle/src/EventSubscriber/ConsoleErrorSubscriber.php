<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleErrorSubscriber implements EventSubscriberInterface
{
    public function onConsoleError(ConsoleErrorEvent $event)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            'console.error' => 'onConsoleError',
        ];
    }
}
