<?php
/**
 * Activate
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace HexagonalReviews\Shared\Infrastructure\Wordpress;

use HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration\MigrationInterface;

/**
 * Class Activate
 *
 * @package HexagonalReviews\Shared\Infrastructure\Wordpress
 */
final class Activate {

	/**
	 * OPTION name
	 *
	 * @var string
	 */
	private const HEXAGONAL_REVIEWS_VERSION = 'hexagonal-reviews-version';

	/**
	 * Default Version
	 *
	 * @var string
	 */
	private const DEFAULT = '0.0.0';

	/**
	 * Activation.
	 */
	public static function activate() {

		$old_version = get_option( self::HEXAGONAL_REVIEWS_VERSION, self::DEFAULT );

		$dir = new \DirectoryIterator( __DIR__ . '/../Persistence/Wordpress/Migration' );
		foreach ( $dir as $file_info ) {
			if ( ! $file_info->isDot() && 'MigrationInterface.php' !== $file_info->getFilename() ) {
				self::apply_migration( $file_info->getFilename(), $old_version );
			}
		}

		update_option( self::HEXAGONAL_REVIEWS_VERSION, Kernel::VERSION );
	}

	/**
	 * Instantiates migration class
	 *
	 * @param string $file_name migration file.
	 *
	 * @return mixed
	 */
	private static function get_migration_instance( string $file_name ): MigrationInterface {
		$class = 'HexagonalReviews\\Shared\\Infrastructure\\Persistence\\Wordpress\\Migration\\' . str_replace( '.php', '', $file_name );
		return new $class();
	}

	/**
	 * Applies Migration if it's newer than the previous version
	 *
	 * @param string $file_name migration file.
	 * @param string $old_version version name.
	 */
	private static function apply_migration( string $file_name, string $old_version ): void {
		$migration = self::get_migration_instance( $file_name );
		if ( self::version_to_number( $old_version ) < self::version_to_number( $migration->get_version() ) ) {
			$migration->run();
		}
	}

	/**
	 * Converts a version to a number
	 *
	 * @param string $version semantic version to change.
	 *
	 * @return int
	 */
	private static function version_to_number( string $version ): int {
		$parts = explode( '.', $version );
		return (int) $parts[0] * 1000000 + (int) $parts[1] * 1000 + (int) $parts[2];
	}
}
