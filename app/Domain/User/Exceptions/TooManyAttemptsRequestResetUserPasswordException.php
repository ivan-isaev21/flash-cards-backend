<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class TooManyAttemptsRequestResetUserPasswordException extends DomainException
{
    public function __construct()
    {
        $message = 'Too many attemps! Try later!';
        parent::__construct($message);
    }
}
