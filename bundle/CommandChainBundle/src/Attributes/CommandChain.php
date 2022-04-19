<?php

namespace Ezi\CommandChainBundle\Attributes;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Event\ConsoleEvent;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CommandChain
{
    private Application $application;

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

    public function __construct(array $chains)
    {
        $this->initialize($chains);
    }

    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
}