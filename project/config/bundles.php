<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Ezi\CommandChainBundle\CommandChainBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Ezi\BarBundle\BarBundle::class => ['dev' => true, 'test' => true],
    Ezi\FooBundle\FooBundle::class => ['dev' => true, 'test' => true],
];
