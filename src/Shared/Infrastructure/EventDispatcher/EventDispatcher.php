<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\EventDispatcher;

use BetterReview\Shared\Domain\Event\Event;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use BetterReview\Shared\Infrastructure\EventDispatcher\Exception\EventNotFound;
use BetterReview\Shared\Domain\Service\EventDispatcher as SharedEventDispatcher;

final class EventDispatcher implements SharedEventDispatcher
{
    /** @var array */
    private $dispatchers;

    public function __construct(array $dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    public function dispatch(Event $event): void
    {
        if (!isset($this->dispatchers[get_class($event)])) {
            throw new EventNotFound(get_class($event));
        }

        foreach ($this->dispatchers[get_class($event)] as $dispatcher) {
            $dispatcher = Container::resolve($dispatcher);
            $dispatcher($event);
        }
    }
}