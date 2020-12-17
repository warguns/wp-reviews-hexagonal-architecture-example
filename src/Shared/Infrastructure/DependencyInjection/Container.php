<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\DependencyInjection;

use BetterReview\Average\Application\Event\OnReviewAddedRecalculateAverage;
use BetterReview\Average\Application\Event\OnReviewDeletedRecalculateAverage;
use BetterReview\Average\Application\Event\OnReviewUpdatedRecalculateAverage;
use BetterReview\Average\Application\Query\GetAverage\GetAverageHandler;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Command\Delete\DeleteHandler;
use BetterReview\Review\Application\Command\Update\UpdateHandler;
use BetterReview\Review\Application\Query\All\ListHandler;
use BetterReview\Review\Application\Query\Get\GetHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostHandler;
use BetterReview\Average\Domain\Service\AverageCalculator;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\Event\ReviewUpdated;
use BetterReview\Shared\Infrastructure\EventDispatcher\EventDispatcher;
use BetterReview\Average\Infrastructure\Wordpress\Persistence\WpAverageRepository;
use BetterReview\Review\Infrastructure\Wordpress\Persistence\WpReviewRepository;

final class Container
{
    /**
     * @return array
     */
    public static function resolve(string $className)
    {
        global $wpdb;

        $reviewRepo = new WpReviewRepository($wpdb, $wpdb->prefix);
        $averageRepo = new WpAverageRepository($wpdb, $wpdb->prefix);
        $eventDispatcher = new EventDispatcher([
            ReviewAdded::class => [OnReviewAddedRecalculateAverage::class],
            ReviewUpdated::class => [OnReviewUpdatedRecalculateAverage::class],
            ReviewDeleted::class => [OnReviewDeletedRecalculateAverage::class],
        ]);

        $createCommandHandler = new CreateHandler($reviewRepo, $eventDispatcher);
        $updateCommandHandler = new UpdateHandler($reviewRepo, $eventDispatcher);
        $deleteCommandHandler = new DeleteHandler($reviewRepo, $eventDispatcher);

        $listReviewsHandler = new ListHandler($reviewRepo);
        $getPostReviews = new GetByPostHandler($reviewRepo);
        $getReviews = new GetHandler($reviewRepo);


        $calculator = new AverageCalculator();
        $getAverageHandler = new GetAverageHandler($averageRepo, $calculator);

        $onReviewAddedRecalculateAverage = new OnReviewAddedRecalculateAverage($averageRepo);
        $onReviewUpdatedRecalculateAverage = new OnReviewUpdatedRecalculateAverage($averageRepo);
        $onReviewDeletedRecalculateAverage = new OnReviewDeletedRecalculateAverage($averageRepo);

        $container = [
            CreateHandler::class => $createCommandHandler,
            UpdateHandler::class => $updateCommandHandler,
            DeleteHandler::class => $deleteCommandHandler,
            ListHandler::class => $listReviewsHandler,
            GetHandler::class => $getReviews,
            GetByPostHandler::class => $getPostReviews,
            OnReviewAddedRecalculateAverage::class => $onReviewAddedRecalculateAverage,
            OnReviewUpdatedRecalculateAverage::class => $onReviewUpdatedRecalculateAverage,
            OnReviewDeletedRecalculateAverage::class => $onReviewDeletedRecalculateAverage,
            GetAverageHandler::class => $getAverageHandler,
        ];

        return $container[$className];
    }
}