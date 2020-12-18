<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Entity;

use BetterReview\Review\Domain\ValueObject\Email;
use BetterReview\Review\Domain\ValueObject\Status;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use BetterReview\Review\Domain\ValueObject\Stars;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Review
{

    /** @var UuidInterface */
    private $uuid;

    /** @var ProductId */
    private $postId;

    /** @var Status */
    private $status;

    /** @var string */
    private $author;

    /** @var string */
    private $content;

    /** @var string */
    private $title;

    /** @var Email */
    private $email;

    /** @var Stars */
    private $stars;

    /** @var \DateTime */
    private $createdAt;

    /** @var \DateTime */
    private $updatedAt;

    private function __construct(UuidInterface $uuid, ProductId $postId, Status $status, string $author, string $content, string $title, Email $email, Stars $stars, \DateTime $createdAt, \DateTime $updatedAt)
    {
        $this->uuid = $uuid;
        $this->postId = $postId;
        $this->status = $status;
        $this->author = $author;
        $this->content = $content;
        $this->title = $title;
        $this->email = $email;
        $this->stars = $stars;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    public static function build(UuidInterface $uuid, ProductId $postId, Status $status, string $author, string $content, string $title, Email $email, Stars $stars, string $updatedAt = 'now', string $createdAt = 'now'): self
    {
        return new self(
            $uuid,
            $postId,
            $status,
            $author,
            $content,
            $title,
            $email,
            $stars,
            new \DateTime($updatedAt),
            new \DateTime($createdAt)
        );
    }

    public static function fromResult(array $result)
    {
        return new self(
            Uuid::fromString($result['uuid']),
            ProductId::fromInt((int) $result['post_id']),
            Status::fromStatus($result['status']),
            $result['author'],
            $result['content'],
            $result['title'],
            Email::fromString($result['email']),
            Stars::fromResult((float) $result['stars']),
            \DateTime::createFromFormat(DATE_ATOM, $result['updated_at']),
            \DateTime::createFromFormat(DATE_ATOM, $result['created_at'])
        );
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return ProductId
     */
    public function getPostId(): ProductId
    {
        return $this->postId;
    }

    public function getStatus(): Status
    {
        return $this->status;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return Stars
     */
    public function getStars(): Stars
    {
        return $this->stars;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'post_id' => $this->postId->getId(),
            'status'=> $this->status->getStatus(),
            'author' => $this->getAuthor(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'email' => $this->getEmail()->getEmail(),
            'stars' => $this->stars->getStars(),
            'created_at' => $this->getCreatedAt()->format(DATE_ATOM),
            'updated_at' => $this->getUpdatedAt()->format(DATE_ATOM)
        ];
    }

}