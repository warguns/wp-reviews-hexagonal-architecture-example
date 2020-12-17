<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\EventDispatcher\Exception;


use Throwable;

class EventNotFound extends \Exception
{
    public function __construct($eventName, Throwable $previous = null)
    {
        parent::__construct("Dispatcher Not found for $eventName", 500, $previous);
    }

}