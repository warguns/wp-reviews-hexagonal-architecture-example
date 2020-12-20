<?php
/**
 * EventDispatcher
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Domain\Service;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Interface EventDispatcher
 *
 * @package BetterReview\Shared\Domain\Service
 */
interface EventDispatcher extends EventDispatcherInterface {

	/**
	 * Dispatcher.
	 *
	 * @param object $event event.
	 */
	public function dispatch( object $event );
}
