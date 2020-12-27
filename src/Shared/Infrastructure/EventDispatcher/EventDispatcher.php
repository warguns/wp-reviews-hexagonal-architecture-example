<?php
/**
 * EventDispatcher
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace HexagonalReviews\Shared\Infrastructure\EventDispatcher;

use HexagonalReviews\Shared\Domain\Service\EventDispatcher as SharedEventDispatcher;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Container;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Exception\DependencyNotFound;
use HexagonalReviews\Shared\Infrastructure\EventDispatcher\Exception\EventNotFound;

/**
 * Class EventDispatcher
 *
 * @package HexagonalReviews\Shared\Infrastructure\EventDispatcher
 */
final class EventDispatcher implements SharedEventDispatcher {

	/**
	 * Dispatchers.
	 *
	 * @var array dispatchers.
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
	 * Dispatcher.
	 *
	 * @param object $event event to dispatch.
	 *
	 * @return object
	 * @throws EventNotFound When event is not found.
	 * @throws DependencyNotFound When a container dependency is not found.
	 */
	public function dispatch( $event ) {
		if ( ! isset( $this->dispatchers[ get_class( $event ) ] ) ) {
			throw new EventNotFound( get_class( $event ) );
		}

		$container = new Container();
		foreach ( $this->dispatchers[ get_class( $event ) ] as $dispatcher ) {
			$dispatcher = $container->get( $dispatcher );
			$event      = $dispatcher( $event );
		}

		return $event;
	}
}
