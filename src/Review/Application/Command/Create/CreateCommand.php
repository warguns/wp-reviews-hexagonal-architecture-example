<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Create;

final class CreateCommand
{
    /** @var int */
    private $postId;

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

    public function __construct(int $postId, string $author, string $title, string $content, string $email, float $stars)
    {
        $this->postId = $postId;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->email = $email;
        $this->stars = $stars;
    }


    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return float
     */
    public function getStars(): float
    {
        return $this->stars;
    }
}