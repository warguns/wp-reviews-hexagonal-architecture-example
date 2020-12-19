<?php

declare( strict_types=1 );

namespace BetterReview\Review\Domain\Entity;

use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\ValueObject\Email;
use BetterReview\Review\Domain\ValueObject\Stars;
use BetterReview\Review\Domain\ValueObject\Status;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Review
 *
 * @package BetterReview\Review\Domain\Entity
 */
final class Review {

	/**
	 * Uuid.
	 *
	 * @var UuidInterface uuid
	 */
	private $uuid;

	/**
	 * Product id.
	 *
	 * @var ProductId product id.
	 */
	private $product_id;

	/**
	 * Status.
	 *
	 * @var Status status.
	 */
	private $status;

	/**
	 * Author.
	 *
	 * @var string author.
	 */
	private $author;

	/**
	 * Content.
	 *
	 * @var string content
	 */
	private $content;

	/**
	 * Title.
	 *
	 * @var string title.
	 */
	private $title;

	/**
	 * Email.
	 *
	 * @var Email email.
	 */
	private $email;

	/**
	 * Stars
	 *
	 * @var Stars stars.
	 */
	private $stars;

	/**
	 * Created At
	 *
	 * @var DateTime datetime.
	 */
	private $created_at;

	/**
	 * Updated At
	 *
	 * @var DateTime updated_at.
	 */
	private $updated_at;

	/**
	 * Review constructor.
	 *
	 * @param UuidInterface $uuid uuid.
	 * @param ProductId     $product_id product id.
	 * @param Status        $status status.
	 * @param string        $author author.
	 * @param string        $content content.
	 * @param string        $title title.
	 * @param Email         $email email.
	 * @param Stars         $stars stars.
	 * @param DateTime      $created_at created at.
	 * @param DateTime      $updated_at upadted at.
	 */
	private function __construct( UuidInterface $uuid, ProductId $product_id, Status $status, string $author, string $content, string $title, Email $email, Stars $stars, DateTime $created_at, DateTime $updated_at ) {
		$this->uuid       = $uuid;
		$this->product_id = $product_id;
		$this->status     = $status;
		$this->author     = $author;
		$this->content    = $content;
		$this->title      = $title;
		$this->email      = $email;
		$this->stars      = $stars;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}


	/**
	 * Builder.
	 *
	 * @param UuidInterface $uuid uuid.
	 * @param ProductId     $product_id product id.
	 * @param Status        $status status.
	 * @param string        $author author.
	 * @param string        $content content.
	 * @param string        $title title.
	 * @param Email         $email email.
	 * @param Stars         $stars stars.
	 * @param string        $updated_at udpated.
	 * @param string        $created_at created.
	 *
	 * @return static
	 * @throws \Exception When there's an error.
	 */
	public static function build( UuidInterface $uuid, ProductId $product_id, Status $status, string $author, string $content, string $title, Email $email, Stars $stars, string $updated_at = 'now', string $created_at = 'now' ): self {
		return new self(
			$uuid,
			$product_id,
			$status,
			$author,
			$content,
			$title,
			$email,
			$stars,
			new DateTime( $updated_at ),
			new DateTime( $created_at )
		);
	}

	/**
	 * From Database Result.
	 *
	 * @param array $result result.
	 *
	 * @return Review
	 * @throws IncorrectStars When incorrect stars.
	 * @throws StatusNotFound Status not found.
	 */
	public static function from_result( array $result ): Review {
		return new self(
			Uuid::fromString( $result['uuid'] ),
			ProductId::from_int( (int) $result['post_id'] ),
			Status::from_status( $result['status'] ),
			$result['author'],
			$result['content'],
			$result['title'],
			Email::from_string( $result['email'] ),
			Stars::from_result( (float) $result['stars'] ),
			DateTime::createFromFormat( DATE_ATOM, $result['updated_at'] ),
			DateTime::createFromFormat( DATE_ATOM, $result['created_at'] )
		);
	}

	/**
	 * Uuid.
	 *
	 * @return UuidInterface uuid.
	 */
	public function get_uuid(): UuidInterface {
		return $this->uuid;
	}

	/**
	 * Product id.
	 *
	 * @return ProductId product id.
	 */
	public function get_product_id(): ProductId {
		return $this->product_id;
	}

	/**
	 * Status.
	 *
	 * @return Status
	 */
	public function get_status(): Status {
		return $this->status;
	}

	/**
	 * Stars.
	 *
	 * @return Stars
	 */
	public function get_stars(): Stars {
		return $this->stars;
	}

	/**
	 * Converts to Array.
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'uuid'       => $this->uuid->toString(),
			'post_id'    => $this->product_id->get_id(),
			'status'     => $this->status->get_status(),
			'author'     => $this->get_author(),
			'title'      => $this->get_title(),
			'content'    => $this->get_content(),
			'email'      => $this->get_email()->get_email(),
			'stars'      => $this->stars->get_stars(),
			'created_at' => $this->get_created_at()->format( DATE_ATOM ),
			'updated_at' => $this->get_updated_at()->format( DATE_ATOM ),
		);
	}

	/**
	 * Autor
	 *
	 * @return string
	 */
	public function get_author(): string {
		return $this->author;
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * Content.
	 *
	 * @return string
	 */
	public function get_content(): string {
		return $this->content;
	}

	/**
	 * Email.
	 *
	 * @return Email
	 */
	public function get_email(): Email {
		return $this->email;
	}

	/**
	 * Created At.
	 *
	 * @return DateTime
	 */
	public function get_created_at(): DateTime {
		return $this->created_at;
	}

	/**
	 * Updated at.
	 *
	 * @return DateTime
	 */
	public function get_updated_at(): DateTime {
		return $this->updated_at;
	}
}
