<?php

namespace App\Domain\User\Exceptions;

use DomainException;

class UserNotVerifiedException extends DomainException
{
    public function __construct()
    {
        $message = 'This user is not verified!';
        parent::__construct($message);
    }
}
