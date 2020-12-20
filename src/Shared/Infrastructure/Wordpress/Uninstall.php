<?php
/**
 * Uninstall
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\Wordpress;

/**
 * Class Uninstall
 *
 * @package BetterReview\Shared\Infrastructure\Wordpress
 */
final class Uninstall {
	/**
	 * Uninstallation
	 */
	public static function uninstall() {
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS `wp_better_review`;' );
		$wpdb->query( 'DROP TABLE IF EXISTS `wp_better_review_average`;' );
	}
}
