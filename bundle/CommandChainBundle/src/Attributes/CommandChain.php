<?php

namespace Ezi\CommandChainBundle\Attributes;

use Symfony\Component\Console\Event\ConsoleEvent;

#[\Attribute]
class CommandChain
{
    private \SplPriorityQueue $commandQueue;

    /**
     * @return \SplPriorityQueue
     */
    public function getCommandQueue(): \SplPriorityQueue
    {
        return $this->commandQueue;
    }

    /**
     * @param array $chains
     * @return void
     */
    protected function initialize(array $chains): void
    {
        $this->commandQueue = new \SplPriorityQueue();
        foreach ($chains as $commandChain => $priority) {
            $this->commandQueue->insert($commandChain['command'], $commandChain['priority']);
        }
    }

    public function __construct(ConsoleEvent $event, array $chains)
    {
        $chains = $event->getInput()->getArguments();
        $this->initialize($chains);
    }

}