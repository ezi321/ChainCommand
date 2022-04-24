<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Ezi\CommandChainBundle\Service\CommandChainInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    /**
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
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }
}
