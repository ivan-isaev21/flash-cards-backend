<?php

namespace App\Domain\Cards\Exceptions;

use App\Application\Cards\ValueObjects\CardId;
use RuntimeException;

class CardNotFoundException extends RuntimeException
{
    public function __construct(CardId $id)
    {
        $message = "Card with id : " . $id->getValue() . 'does not exist!';
        parent::__construct($message);
    }
}
