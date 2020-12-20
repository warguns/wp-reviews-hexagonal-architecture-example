<?php
/**
 * EventDispatcher
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\EventDispatcher;

use BetterReview\Shared\Domain\Event\Event;
use BetterReview\Shared\Domain\Service\EventDispatcher as SharedEventDispatcher;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use BetterReview\Shared\Infrastructure\EventDispatcher\Exception\EventNotFound;

/**
 * Class EventDispatcher
 *
 * @package BetterReview\Shared\Infrastructure\EventDispatcher
 */
final class EventDispatcher implements SharedEventDispatcher {
	/**
	 * Dispatchers.
	 *
	 * @var array
	 */
	private $dispatchers;

	/**
	 * EventDispatcher constructor.
	 *
	 * @param array $dispatchers dispatchers.
	 */
	public function __construct( array $dispatchers ) {
		$this->dispatchers = $dispatchers;
	}

	/**
	 * Event.
	 *
	 * @param Event $event event.
	 *
	 * @throws EventNotFound EventNotFound.
	 */
	public function dispatch( Event $event ): void {
		if ( ! isset( $this->dispatchers[ get_class( $event ) ] ) ) {
			throw new EventNotFound( get_class( $event ) );
		}

		foreach ( $this->dispatchers[ get_class( $event ) ] as $dispatcher ) {
			$dispatcher = Container::resolve( $dispatcher );
			$dispatcher( $event );
		}
	}
}
