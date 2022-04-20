<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Exception\CommandExecutionException;
use Ezi\CommandChainBundle\Exception\UnsupportedTypeException;
use PHPUnit\Util\Exception;
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
        $result = Command::SUCCESS;
        foreach ($this as $command) {
            $result = $command->execute($input, $output);
            if ($result !== Command::SUCCESS) {
                throw new CommandExecutionException();
            }
        }

        return $result;
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

    /**
     * @param mixed $offset
     * @param Command $value
     * @return void
     * @throws UnsupportedTypeException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if( !$value instanceof Command) {
            throw new UnsupportedTypeException();
        }
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