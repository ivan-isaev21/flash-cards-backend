<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class UserInvalidCredentialsException extends DomainException
{
    public function __construct()
    {
        $message = 'Invalid credentials';
        parent::__construct($message);
    }
}
