<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Event\ConsoleEvent;
use Ezi\CommandChainBundle\Service\CommandChain;

interface ChainBuilderInterface
{
    public function build(ConsoleEvent $event): CommandChain;
    public function getConfiguration(): array;
}