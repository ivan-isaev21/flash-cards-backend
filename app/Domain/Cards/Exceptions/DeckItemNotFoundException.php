<?php

namespace App\Domain\Cards\Exceptions;

use App\Application\Cards\ValueObjects\DeckItemId;
use RuntimeException;

class DeckItemNotFoundException extends RuntimeException
{
    public function __construct(DeckItemId $id)
    {
        $message = "DeckItem with id : " . $id->getValue() . 'does not exist!';
        parent::__construct($message);
    }
}
