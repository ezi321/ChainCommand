<?php

namespace Ezi\CommandChainBundle\Exception;

/**
 * Exception that throws when tried to push command to chain when master command not set
 */
class EmptyMasterCommandException extends \Exception
{

}