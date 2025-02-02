<?php

namespace App\Domain\User\Exceptions;

use App\Application\User\ValueObjects\UserId;
use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    public function __construct()
    {
        $message = "User does not exist!";
        parent::__construct($message);
    }
}
