<?php

namespace App\Domain\User\Exceptions;

use App\Application\User\ValueObjects\UserId;
use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    public function __construct(UserId $id)
    {
        $message = "User with id : " . $id->getValue() . 'does not exist!';
        parent::__construct($message);
    }
}
