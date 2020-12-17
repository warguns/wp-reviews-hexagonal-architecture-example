<?php

declare(strict_types=1);

namespace BetterReview\Review\Infrastructure\Wordpress\Persistence;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use Ramsey\Uuid\UuidInterface;

final class WpReviewRepository implements ReviewRepository
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

    /**
     * @throws ReviewNotFound
     */
    public function get(UuidInterface $reviewUuid): Review
    {
        $result = $this->wpdb->get_row('SELECT * FROM ' . $this->prefix . 'better_review WHERE uuid = "' . $reviewUuid->toString()  . '"', ARRAY_A);

        if (null === $result) {
            throw new ReviewNotFound($reviewUuid);
        }

        return Review::fromResult($result);
    }

    public function insert(Review $review): bool
    {
       return (bool) $this->wpdb->insert($this->prefix . 'better_review', $review->toArray());
    }

    public function update(Review $review): bool
    {
       return (bool) $this->wpdb->update($this->prefix .'better_review', $review->toArray(), ['uuid' => $review->getUuid()->toString()]);
    }

    public function delete(UuidInterface $reviewUuid): bool
    {
        return (bool) $this->wpdb->delete($this->prefix .'better_review', ['uuid' => $reviewUuid->toString()]);
    }

    public function findByPost(ProductId $postId, int $limit = self::LIMIT, int $offset = self::OFFSET): ReviewCollection
    {
        $sql = 'SELECT * FROM ' . $this->prefix . 'better_review WHERE status = "published" and post_id = ' . $postId->getId() ;

        if ($limit > 0) {
            $sql .= " LIMIT " . esc_sql($limit);
            if ($offset > 0) {
                $sql .= " OFFSET " . esc_sql($offset);
            }
        }

        $sql .= ' ORDER BY created_at DESC';

        $results = $this->wpdb->get_results($sql, ARRAY_A);

        return ReviewCollection::fromResults($results);
    }

    public function countByPost(ProductId $postId): int
    {
        $count = $this->wpdb->get_results('SELECT COUNT(*) as counter  FROM ' . $this->prefix . 'better_review WHERE post_id = ' . $postId->getId() . ' ORDER BY created_at DESC', ARRAY_A);

        return $count['counter'];
    }

    public function all(int $limit = self::LIMIT, int $offset = self::OFFSET, array $orderby = [], string $search = null): ReviewCollection
    {
        $sql = 'SELECT * FROM ' . $this->prefix . 'better_review r INNER JOIN ' . $this->prefix . 'posts p on r.post_id = p.id ';

        if (null !== $search) {
            $sql .= 'WHERE title LIKE "%' . esc_sql($search) . '%" OR content LIKE "%' . esc_sql($search) . '%" OR status LIKE "%'. esc_sql($search) .'%" ';
        }

        if (!empty($orderby)) {
            $sql .= 'ORDER BY ';
            $orders = [];
            foreach ($orderby as $field => $sort) {
                $orders[] = esc_sql($field) . ' ' . esc_sql($sort);
            }
            $sql .= implode(',', $orders);
        }

        if ($limit > 0) {
            $sql .= " LIMIT " . esc_sql($limit);
            if ($offset > 0) {
                $sql .= " OFFSET " . esc_sql($offset);
            }
        }

        return ReviewCollection::fromResults($this->wpdb->get_results($sql, ARRAY_A));
    }

    public function countAll(string $search = null): int
    {

        $sql = 'SELECT COUNT(*) as counter FROM ' . $this->prefix . "better_review ";

        if (null !== $search) {
            $sql .= 'WHERE title LIKE "%' . esc_sql($search) . '%" OR content LIKE "%' . esc_sql($search) . '%" OR status LIKE "%'. esc_sql($search) .'%" ';
        }

        $count = $this->wpdb->get_row($sql, ARRAY_A);

        return (int) $count['counter'];
    }
}