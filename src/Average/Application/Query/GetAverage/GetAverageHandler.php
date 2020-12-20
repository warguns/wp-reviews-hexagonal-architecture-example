<?php
/**
 * GetAverageHandler
 *
 * @package Average
 */

declare( strict_types=1 );

namespace BetterReview\Average\Application\Query\GetAverage;

use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Average\Domain\Service\AverageCalculator;
use BetterReview\Shared\Domain\ValueObject\ProductId;

/**
 * Class GetAverageHandler
 *
 * @package BetterReview\Average\Application\Query\GetAverage
 */
final class GetAverageHandler {
	/**
	 * Repo.
	 *
	 * @var AverageRepository rpo.
	 */
	private $average_repository;

	/**
	 * Calculator.
	 *
	 * @var AverageCalculator calculator.
	 */
	private $calculator;

	/**
	 * GetAverageHandler constructor.
	 *
	 * @param AverageRepository $average_repository repo.
	 * @param AverageCalculator $calculator calculator.
	 */
	public function __construct( AverageRepository $average_repository, AverageCalculator $calculator ) {
		$this->average_repository = $average_repository;
		$this->calculator         = $calculator;
	}

	/**
	 * Run.
	 *
	 * @param GetAverageQuery $query query.
	 *
	 * @return ReviewStats
	 */
	public function run( GetAverageQuery $query ): ReviewStats {
		$average = $this->average_repository->find( ProductId::from_int( $query->get_product_id() ) );

		return $this->calculator->calculate( $average );
	}
}
