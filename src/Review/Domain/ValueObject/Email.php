<?php
/**
 * Email
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\ValueObject;

/**
 * Class Email
 *
 * @package HexagonalReviews\Review\Domain\ValueObject
 */
final class Email {
	/**
	 * Email
	 *
	 * @var string email
	 */
	private $email;

	/**
	 * Email constructor.
	 *
	 * @param string $email email.
	 */
	public function __construct( string $email ) {
		$this->email = $email;
	}

	/**
	 * Generates From string.
	 *
	 * @param string $email email.
	 *
	 * @return Email
	 */
	public static function from_string( string $email ): Email {
		return new static( $email );
	}

	/**
	 * Email.
	 *
	 * @return string email.
	 */
	public function get_email(): string {
		return $this->email;
	}
}
