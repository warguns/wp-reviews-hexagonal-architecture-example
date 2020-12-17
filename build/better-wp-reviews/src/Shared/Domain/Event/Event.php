<?php

declare(strict_types=1);

namespace BetterReview\Shared\Domain\Event;

interface Event
{
    public function getParentUuid(): string;
    public function getCorrelationUuid(): string;
}