<?php
/**
 * Activate
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\Wordpress;

/**
 * Class Activate
 *
 * @package BetterReview\Shared\Infrastructure\Wordpress
 */
final class Activate {

	/**
	 * Activation.
	 */
	public static function activate() {
		global $wpdb;

		$wpdb->query(
			"CREATE TABLE `wp_better_review` (
                              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                              `uuid` varchar(300) NOT NULL DEFAULT '',
                              `post_id` bigint(20) unsigned DEFAULT NULL,
                              `status` varchar(255) DEFAULT NULL,
                              `title` varchar(255) DEFAULT NULL,
                              `content` mediumtext CHARACTER SET latin1 COLLATE latin1_bin,
                              `author` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
                              `stars` float NOT NULL,
                              `email` varchar(255) DEFAULT NULL,
                              `created_at` varchar(255) NOT NULL DEFAULT '',
                              `updated_at` varchar(255) NOT NULL DEFAULT '',
                              PRIMARY KEY (`id`),
                              KEY `uuid` (`uuid`),
                              KEY `relations` (`post_id`),
                              CONSTRAINT `relations` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`)
                            ) ENGINE=InnoDB"
		);

		$wpdb->query(
			"CREATE TABLE `wp_better_review_average` (
                              `post_id` bigint(20) unsigned NOT NULL,
                              `review_count` int(11) DEFAULT '0',
                              `total_review` int(11) DEFAULT '0',
                              PRIMARY KEY (`post_id`),
                              CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`)
                            ) ENGINE=InnoDB"
		);
	}
}
