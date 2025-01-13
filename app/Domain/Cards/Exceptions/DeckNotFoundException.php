<?php

namespace App\Domain\Cards\Exceptions;

use App\Application\Cards\ValueObjects\DeckId;
use RuntimeException;

class DeckNotFoundException extends RuntimeException
{
    public function __construct(DeckId $id)
    {
        $message = "Deck with id : " . $id->getValue() . 'does not exist!';
        parent::__construct($message);
    }
}
