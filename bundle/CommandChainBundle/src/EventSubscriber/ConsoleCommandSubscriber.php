<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * Event subscriber to console.command event in which the command chain builder is called
 */
class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    /**
     *
     * @var ChainBuilderInterface
     */
    private ChainBuilderInterface $chainBuilder;

    /**
     * @param ChainBuilderInterface $chainBuilder
     */
    public function __construct(ChainBuilderInterface $chainBuilder)
    {
        $this->chainBuilder = $chainBuilder;
    }

    /**
     * Event subscriber method that called when dispatch console.command
     * @param ConsoleCommandEvent $event
     * @return void
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $chain = $this->chainBuilder->build(
            $event->getCommand(),
            $event->getInput()
        );

        if($chain instanceof CommandChainInterface) {
            $output = new BufferedOutput();
            $event->disableCommand();
            $chain->execute($output);

            $event->getOutput()
                  ->write($output->fetch());
        }
    }

    /**
     * Method that return subscribed events array
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }
}
