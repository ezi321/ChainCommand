<?php

namespace Ezi\CommandChainBundle\Service;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandChainInterface extends \ArrayAccess, \Iterator
{
    public function execute(InputInterface $input, OutputInterface $output): int;
}