<?php

declare( strict_types=1 );

namespace BetterReview\Shared\Domain\Service;

use BetterReview\Shared\Domain\Event\Event;

/**
 * Interface EventDispatcher
 *
 * @package BetterReview\Shared\Domain\Service
 */
interface EventDispatcher {

	/**
	 * Dispatcher.
	 *
	 * @param Event $event event.
	 */
	public function dispatch( Event $event ): void;
}
