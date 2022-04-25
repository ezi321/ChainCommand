<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Ezi\CommandChainBundle\CommandChainBundle::class => ['dev' => true, 'test' => true],
    Ezi\BarBundle\BarBundle::class => ['dev' => true, 'test' => true],
    Ezi\FooBundle\FooBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
];
