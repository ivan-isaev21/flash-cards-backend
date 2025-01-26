<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class TooManyAttemptsRequestVerifyUserEmailException extends DomainException
{
    public function __construct()
    {
        $message = 'Too many attemps! Try later!';
        parent::__construct($message);
    }
}
