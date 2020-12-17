<?php

declare(strict_types=1);

namespace BetterReview\Shared\Domain\ValueObject;

final class ProductId
{
    /** @var int */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id): self
    {
        return new static($id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}