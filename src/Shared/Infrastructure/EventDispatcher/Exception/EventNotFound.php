<?php

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\EventDispatcher\Exception;

use Exception;

/**
 * Class EventNotFound
 *
 * @package BetterReview\Shared\Infrastructure\EventDispatcher\Exception
 */
class EventNotFound extends Exception {

	/**
	 * EventNotFound constructor.
	 *
	 * @param string $event_name event.
	 */
	public function __construct( string $event_name ) {
		parent::__construct( "Dispatcher Not found for $event_name", 500 );
	}
}
