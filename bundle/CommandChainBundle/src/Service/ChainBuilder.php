<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Exception\NonExistentChainException;
use Ezi\CommandChainBundle\Exception\NotExecutableCommandException;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;

class ChainBuilder implements ChainBuilderInterface
{
    private CommandChainInterface $commandChain;

    private array $configuration;

    public function __construct(CommandChainInterface $commandChain, array $configuration)
    {
        $this->commandChain = $commandChain;
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return CommandChainInterface
     */
    public function getCommandChain(): CommandChainInterface
    {
        return $this->commandChain;
    }

    public function build(Command $mainCommand, InputInterface $mainInput): ?CommandChainInterface
    {
        $config = $this->getConfiguration();
        $this->mergeConfiguration($mainCommand);

        if($this->isCommandInChain($mainCommand->getName())) {
            throw new NotExecutableCommandException();
        }

        if(array_key_exists($mainCommand->getName(), $this->configuration['chains'])) {

            $chainName = $mainCommand->getName();

            if (!array_key_exists($chainName, $config['chains'])) {
                throw new NonExistentChainException();
            }

            return $this->pushCommands($mainCommand, $mainInput)->getCommandChain();

        } else {
            return null;
        }
    }

    private function pushCommands(Command $mainCommand, InputInterface $mainInput)
    {
        $chainName = $mainCommand->getName();
        $config    = $this->getConfiguration();
        $app       = $mainCommand->getApplication();

        $this->commandChain->pushCommand($mainCommand, $mainInput);

        foreach ($config['chains'][$chainName]['commands'] as $commandName => $args) {
            $command = $app->find($commandName);
            $arrayInput = new ArrayInput($args);
            $this->commandChain->pushCommand($command, $arrayInput);
        }

        return $this;
    }

    private function getCommandAttributes(Command $command): ?\ReflectionAttribute
    {
        $reflect = new \ReflectionClass($command::class);
        $attributes = $reflect->getAttributes(CommandChain::class);

        return array_shift($attributes);
    }

    /**
     * @param Command $command
     * @return void
     * @throws \ReflectionException
     */
    private function mergeConfiguration(Command $command)
    {
        $attribute = $this->getCommandAttributes($command);

        if($attribute instanceof \ReflectionAttribute) {
            $config = [
                'chains' => [
                    $command->getName() => $attribute->newInstance()->getConfiguration()
                ]
            ];
            $this->configuration = array_merge($config, $this->configuration);
        }
    }

    private function isCommandInChain(string $name)
    {
        $configuration = $this->configuration['chains'];
        foreach($configuration as $chain => $value) {
            if(array_key_exists($name, $value['commands'])) {
                return true;
            }
        }
        return false;
    }
}