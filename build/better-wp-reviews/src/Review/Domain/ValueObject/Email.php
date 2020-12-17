<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\ValueObject;

final class Email
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): Email
    {
        return new static($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}