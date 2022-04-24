<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Ezi\CommandChainBundle\Attributes\CommandChain;

#[AsCommand(
    name: 'command:chain',
    description: 'Console chain command',
)]
#[CommandChain(commands: ['foo:command' => [], 'bar:command' => []])]
class CommandChainCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Command chain');

        return Command::SUCCESS;
    }
}
