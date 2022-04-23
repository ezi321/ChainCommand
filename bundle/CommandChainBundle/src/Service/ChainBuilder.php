<?php

namespace Ezi\CommandChainBundle\Service;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Exception\NonExistentChainException;
use Ezi\CommandChainBundle\Exception\NotExecutableCommandException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;

class ChainBuilder implements ChainBuilderInterface
{
    /**
     * @var CommandChainInterface
     */
    private CommandChainInterface $commandChain;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @param CommandChainInterface $commandChain
     * @param array $configuration
     */
    public function __construct(CommandChainInterface $commandChain, array $configuration)
    {
        $this->commandChain  = $commandChain;
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

    /**
     * @param Command $mainCommand
     * @param InputInterface $mainInput
     * @return CommandChainInterface|null
     * @throws NotExecutableCommandException
     * @throws \ReflectionException
     */
    public function build(Command $mainCommand, InputInterface $mainInput): ?CommandChainInterface
    {
        $this->mergeConfiguration($mainCommand);

        if($this->isCommandInChain($mainCommand->getName())) {
            throw new NotExecutableCommandException();
        }

        return $this->pushChildCommands($mainCommand, $mainInput)->getCommandChain();
    }

    /**
     * @param Command $mainCommand
     * @param InputInterface $mainInput
     * @return $this
     */
    private function pushChildCommands(Command $mainCommand, InputInterface $mainInput): self
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

    /**
     * @param Command $command
     * @return \ReflectionAttribute|null
     * @throws \ReflectionException
     */
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

    /**
     * @param string $name
     * @return bool
     */
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