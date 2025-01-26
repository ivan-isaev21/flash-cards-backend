<?php

namespace App\Domain\User\Exceptions;

use App\Application\User\ValueObjects\UserId;
use InvalidArgumentException;
use RuntimeException;

class UserInvalidArgumentException  extends InvalidArgumentException
{

}
