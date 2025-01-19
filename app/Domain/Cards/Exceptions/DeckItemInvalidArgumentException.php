<?php

namespace App\Domain\Cards\Exceptions;

use App\Application\Cards\ValueObjects\DeckItemId;
use RuntimeException;

class DeckItemInvalidArgumentException extends RuntimeException {}
