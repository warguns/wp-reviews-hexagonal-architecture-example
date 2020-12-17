<?php

declare(strict_types=1);

namespace BetterReview\Average\Infrastructure\Wordpress\Persistence;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Shared\Domain\ValueObject\ProductId;

final class WpAverageRepository implements AverageRepository
{
    /** @var \wpdb */
    private $wpdb;

    /** @var string */
    private $prefix;

    public function __construct(\wpdb $wpdb, string $prefix)
    {
        $this->wpdb = $wpdb;
        $this->prefix = $prefix;
    }

    public function find(ProductId $postId): ?Average
    {
        $result = $this->wpdb->get_row('SELECT * FROM ' . $this->prefix . 'better_review_average WHERE post_id = "' . $postId->getId()  . '"', ARRAY_A);

        if (null === $result) {
            return null;
        }

        return Average::fromResult($result);
    }

    public function insert(Average $average): bool
    {
        return (bool) $this->wpdb->insert($this->prefix . 'better_review_average', $average->toArray());
    }

    public function update(Average $average): bool
    {
        return (bool) $this->wpdb->update($this->prefix .'better_review_average', $average->toArray(), ['post_id' => $average->getPostId()->getId()]);
    }
}