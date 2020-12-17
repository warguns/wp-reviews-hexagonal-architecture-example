<?php

declare(strict_types=1);

namespace BetterReview\Average\Application\Query\GetAverage;

use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Average\Domain\Service\AverageCalculator;
use BetterReview\Shared\Domain\ValueObject\ProductId;

final class GetAverageHandler
{
    /** @var AverageRepository */
    private $reviewRepository;

    /** @var AverageCalculator */
    private $calculator;

    public function __construct(AverageRepository $reviewRepository, AverageCalculator $calculator)
    {
        $this->reviewRepository = $reviewRepository;
        $this->calculator = $calculator;
    }

    public function run(GetAverageQuery $query): ReviewStats
    {
        $average = $this->reviewRepository->find(ProductId::fromInt($query->getPostId()));

        return $this->calculator->calculate($average);
    }
}