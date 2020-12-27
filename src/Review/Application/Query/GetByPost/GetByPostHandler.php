<?php
/**
 * GetByPostHandler
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Application\Query\GetByPost;

use HexagonalReviews\Review\Domain\Repository\ReviewRepository;
use HexagonalReviews\Review\Domain\ValueObject\ReviewCollection;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;

/**
 * Class GetByPostHandler
 *
 * @package HexagonalReviews\Review\Application\Query\GetByPost
 */
final class GetByPostHandler {
	/**
	 * Repo.
	 *
	 * @var ReviewRepository repo.
	 */
	private $review_repository;

	/**
	 * GetByPostHandler constructor.
	 *
	 * @param ReviewRepository $review_repository Repo.
	 */
	public function __construct( ReviewRepository $review_repository ) {
		$this->review_repository = $review_repository;
	}

	/**
	 * Execution.
	 *
	 * @param GetByPostQuery $query Query dto.
	 *
	 * @return ReviewCollection
	 */
	public function run( GetByPostQuery $query ): ReviewCollection {
		return $this->review_repository->find_by_post( ProductId::from_int( $query->get_id() ), $query->get_limit(), $query->get_offset() );
	}
}
