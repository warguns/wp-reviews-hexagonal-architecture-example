<?php

declare(strict_types=1);

namespace BetterReview\Average\Domain\Repository;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Shared\Domain\ValueObject\ProductId;

interface AverageRepository
{
    public function find(ProductId $postId): ?Average;

    public function insert(Average $average): bool;

    public function update(Average $average): bool;
}