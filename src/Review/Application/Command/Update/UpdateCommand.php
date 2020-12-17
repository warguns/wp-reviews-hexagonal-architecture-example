<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Update;

final class UpdateCommand
{
    /** @var string */
    private $uuid;

    /** @var int */
    private $postId;

    /** @var string */
    private $status;

    /** @var string */
    private $author;

    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var string */
    private $email;

    /** @var float */
    private $stars;

    public function __construct(string $uuid, int $postId, string $status, string $author, string $title, string $content, string $email, float $stars)
    {
        $this->uuid = $uuid;
        $this->postId = $postId;
        $this->status = $status;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->email = $email;
        $this->stars = $stars;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStars(): float
    {
        return $this->stars;
    }
}