<?php
/**
 * ListHandler
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Application\Query\All;

use HexagonalReviews\Review\Domain\DTO\ListReviewsResponse;
use HexagonalReviews\Review\Domain\Repository\ReviewRepository;

/**
 * Class ListHandler
 *
 * @package HexagonalReviews\Review\Application\Query\All
 */
final class ListHandler {

	/**
	 * Repo
	 *
	 * @var ReviewRepository $review_repository Repo.
	 */
	private $review_repository;

	/**
	 * ListHandler constructor.
	 *
	 * @param ReviewRepository $review_repository Repo.
	 */
	public function __construct( ReviewRepository $review_repository ) {
		$this->review_repository = $review_repository;
	}

	/**
	 * Execution.
	 *
	 * @param ListQuery $query query dto.
	 *
	 * @return ListReviewsResponse
	 */
	public function run( ListQuery $query ): ListReviewsResponse {
		return new ListReviewsResponse(
			$this->review_repository->all(
				$query->get_limit(),
				$query->get_offset(),
				$query->get_order_by(),
				$query->get_search()
			),
			$this->review_repository->count_all( $query->get_search() )
		);
	}
}
