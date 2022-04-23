<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Ezi\CommandChainBundle\Service\CommandChain;
use Symfony\Component\Console\Input\InputInterface;

interface ChainBuilderInterface
{
    public function build(Command $mainCommand, InputInterface $input): ?CommandChainInterface;
    public function getConfiguration(): array;
}