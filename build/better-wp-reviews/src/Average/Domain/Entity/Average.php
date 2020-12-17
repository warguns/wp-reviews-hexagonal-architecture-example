<?php

declare(strict_types=1);

namespace BetterReview\Average\Domain\Entity;

use BetterReview\Shared\Domain\ValueObject\ProductId;

final class Average
{
    /** @var ProductId */
    private $postId;

    /** @var int */
    private $reviewCount;

    /** @var float */
    private $totalReview;

    public function __construct(ProductId $postId, int $reviewCount, float $totalReview)
    {
        $this->postId = $postId;
        $this->reviewCount = $reviewCount;
        $this->totalReview = $totalReview;
    }

    public static function fromResult(array $result): self
    {
        return new static(
            ProductId::fromInt((int) $result['post_id']),
            (int) $result['review_count'],
            (float) $result['total_review']
        );
    }

    public function getPostId(): ProductId
    {
        return $this->postId;
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    public function getTotalReview(): float
    {
        return $this->totalReview;
    }

    public function toArray(): array
    {
        return [
            'post_id' => $this->getPostId()->getId(),
            'review_count' => $this->getReviewCount(),
            'total_review' => $this->getTotalReview(),
        ];
    }
}