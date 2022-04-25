<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Ezi\CommandChainBundle\Service\CommandChain;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Interface that represent commands chain builder
 */
interface ChainBuilderInterface
{
    /**
     * @param Command $mainCommand
     * @param InputInterface $input
     * @return CommandChainInterface|null
     */
    public function build(Command $mainCommand, InputInterface $input): ?CommandChainInterface;

    /**
     * @return array
     */
    public function getConfiguration(): array;
}