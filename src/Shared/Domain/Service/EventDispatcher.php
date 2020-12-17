<?php

declare(strict_types=1);

namespace BetterReview\Shared\Domain\Service;

use BetterReview\Shared\Domain\Event\Event;

interface EventDispatcher
{
    public function dispatch(Event $event): void;
}