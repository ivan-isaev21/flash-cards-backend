<?php
namespace App\Application\Shared\ValueObjects;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use InvalidArgumentException;

class Id
{
    private UuidInterface $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID format.");
        }
        $this->value = Uuid::fromString($value);
    }

    public static function next(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value->toString();
    }

    public function equals(Id $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->value->toString();
    }
}
