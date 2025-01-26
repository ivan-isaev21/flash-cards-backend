<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class InvalidVerifyTokenException  extends DomainException
{
    public function __construct()
    {
        $message = 'Verify token is invalid! Request new!';
        parent::__construct($message);
    }
}
