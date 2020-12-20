<?php
/**
 * Container
 *
 * @package Shared
 */

namespace BetterReview\Shared\Infrastructure\DependencyInjection\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DependencyNotFound
 *
 * @package BetterReview\Shared\Infrastructure\DependencyInjection\Exception
 */
class DependencyNotFound extends Exception implements NotFoundExceptionInterface {

	/**
	 * DependencyNotFound constructor.
	 *
	 * @param string $id dependency.
	 */
	public function __construct( string $id ) {
		parent::__construct( "Dependency not found: $id", 500 );
	}
}