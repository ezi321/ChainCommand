<?php

namespace Ezi\CommandChainBundle\EventSubscriber;

use Ezi\CommandChainBundle\Attributes\CommandChain;
use Ezi\CommandChainBundle\Service\ChainBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ChainBuilderInterface
     */
    private ChainBuilderInterface $chainBuilder;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @param LoggerInterface $logger
     * @param ChainBuilderInterface $chainBuilder
     * @param array $configuration
     */
    public function __construct(LoggerInterface $logger, ChainBuilderInterface $chainBuilder, array $configuration)
    {
        $this->logger        = $logger;
        $this->chainBuilder  = $chainBuilder;
        $this->configuration = $configuration;
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if(array_key_exists($command->getName(), $this->configuration['chains'])) {
            $this->mergeConfiguration($command);
            $output = new BufferedOutput();

            $chain = $this->chainBuilder->build($event);
            $event->disableCommand();
            $chain->execute($output);
            $event->getOutput()->write($output->fetch());
        }
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
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
}
