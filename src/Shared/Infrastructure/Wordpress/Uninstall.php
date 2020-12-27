<?php
/**
 * Uninstall
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace HexagonalReviews\Shared\Infrastructure\Wordpress;

/**
 * Class Uninstall
 *
 * @package HexagonalReviews\Shared\Infrastructure\Wordpress
 */
final class Uninstall {
	/**
	 * Uninstallation
	 */
	public static function uninstall() {
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS `wp_hexagonal_review`;' );
		$wpdb->query( 'DROP TABLE IF EXISTS `wp_hexagonal_review_average`;' );
	}
}
