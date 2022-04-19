<?php

namespace Ezi\CommandChainBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandChain implements CommandChainInterface
{
    private array $commandQueue;

    public function __construct(LoggerInterface $logger)
    {
        $this->commandQueue = [];
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        //@TODO Add functionality
        return Command::SUCCESS;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->commandQueue);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $returnValue = null;
        if ($this->offsetExists($offset)) {
            $returnValue = $this->commandQueue[$offset];
        }
        return $returnValue;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->commandQueue[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        if(!$this->offsetExists($offset)) {
            throw new \OutOfBoundsException('Key not exists');
        }
        unset($this->commandQueue[$offset]);
    }

    public function current(): mixed
    {
        return current($this->commandQueue);
    }

    public function next(): void
    {
        next($this->commandQueue);
    }

    public function key(): mixed
    {
        return key($this->commandQueue);
    }

    public function valid(): bool
    {
        return $this->offsetExists($this->key());
    }

    public function rewind(): void
    {
        reset($this->commandQueue);
    }
}