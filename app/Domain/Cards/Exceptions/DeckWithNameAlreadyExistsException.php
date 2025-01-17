<?php

namespace App\Domain\Cards\Exceptions;

use RuntimeException;

class DeckWithNameAlreadyExistsException extends RuntimeException
{
    public function __construct(string $name)
    {
        $message = "Deck with name : " . $name . 'already exists!';
        parent::__construct($message);
    }
}
