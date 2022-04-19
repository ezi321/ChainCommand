<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleErrorSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $this->logger->error(
            $event->getError()->getTraceAsString(),
            [
                $event->getCommand()->getName() => $event->getExitCode()
            ]
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            'console.error' => 'onConsoleError',
        ];
    }
}
