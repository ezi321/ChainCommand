<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber to console.error event to console error logging
 */
class ConsoleErrorSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Event subscriber method that called when dispatch console.error
     * @param ConsoleErrorEvent $event
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $this->logger->error(
            $event->getError()->getTraceAsString(),
            [
                $event?->getCommand()?->getName() => $event?->getExitCode()
            ]
        );
    }

    /**
     * Method that return subscribed events array
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            'console.error' => 'onConsoleError',
        ];
    }
}
