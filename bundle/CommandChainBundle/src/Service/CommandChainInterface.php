<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface that represent command chain
 */
interface CommandChainInterface
{
    /**
     * @param OutputInterface $output
     * @return int
     */
    public function execute(OutputInterface $output): int;

    /**
     * @param Command $command
     * @param ArrayInput|InputInterface|null $input
     * @return CommandChainInterface
     */
    public function pushCommand(Command $command, ArrayInput|InputInterface $input = null): CommandChainInterface;

    public function setMasterCommand(Command $command, ArrayInput|InputInterface $input = null): CommandChainInterface;
}