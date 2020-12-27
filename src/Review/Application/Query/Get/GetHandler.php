<?php
/**
 * GetHandler
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Application\Query\Get;

use HexagonalReviews\Review\Domain\Entity\Review;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Exception\ReviewNotFound;
use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Review\Domain\Repository\ReviewRepository;
use Ramsey\Uuid\Uuid;

/**
 * Class GetHandler
 *
 * @package HexagonalReviews\Review\Application\Query\Get
 */
final class GetHandler {

	/**
	 * Review repository.
	 *
	 * @var ReviewRepository $review_repository Repo.
	 */
	private $review_repository;

	/**
	 * GetHandler constructor.
	 *
	 * @param ReviewRepository $review_repository repo.
	 */
	public function __construct( ReviewRepository $review_repository ) {
		$this->review_repository = $review_repository;
	}

	/**
	 * Run.
	 *
	 * @param GetQuery $query query.
	 *
	 * @return Review
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws IncorrectStars IncorrectStars.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function run( GetQuery $query ): Review {
		return $this->review_repository->get( Uuid::fromString( $query->get_uuid() ) );
	}
}
