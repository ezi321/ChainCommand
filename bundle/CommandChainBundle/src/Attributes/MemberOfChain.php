<?php

namespace Ezi\CommandChainBundle\Attributes;

#[\Attribute]
class MemberOfChain
{

    public function __construct(string $chainName)
    {
    }

}