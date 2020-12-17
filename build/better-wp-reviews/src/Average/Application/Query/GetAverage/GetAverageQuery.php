<?php

declare(strict_types=1);

namespace BetterReview\Average\Application\Query\GetAverage;

final class GetAverageQuery
{
    /** @var int */
    private $postId;

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }
}