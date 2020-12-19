<?php

declare( strict_types=1 );

namespace BetterReview\Review\Application\Query\Get;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use Ramsey\Uuid\Uuid;

/**
 * Class GetHandler
 *
 * @package BetterReview\Review\Application\Query\Get
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
