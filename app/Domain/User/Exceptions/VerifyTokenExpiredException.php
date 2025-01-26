<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class VerifyTokenExpiredException  extends DomainException
{
    public function __construct()
    {
        $message = 'Verify token is expired! Request new!';
        parent::__construct($message);
    }
}
