<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandChainInterface
{
    /**
     * @param OutputInterface $output
     * @return int
     */
    public function execute(OutputInterface $output): int;

    /**
     * @param Command $command
     * @param ArrayInput|null $input
     * @return CommandChainInterface
     */
    public function pushCommand(Command $command, ArrayInput $input = null): CommandChainInterface;
}