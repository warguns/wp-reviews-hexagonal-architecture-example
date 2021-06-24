<?php
/**
 * Migration
 *
 * @package Shared
 */

namespace HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration;

/**
 * Class Migration0000000000
 *
 * @package HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration
 */
final class Migration0000000000 implements MigrationInterface {

	/**
	 * Gets the version
	 */
	public function get_version(): string {
		return '0.0.1';
	}

	/**
	 * Legacy Migration.
	 */
	public function run(): void {
		global $wpdb;

		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `wp_hexagonal_review` (
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
			"CREATE TABLE IF NOT EXISTS `wp_hexagonal_review_average` (
                              `post_id` bigint(20) unsigned NOT NULL,
                              `review_count` int(11) DEFAULT '0',
                              `total_review` int(11) DEFAULT '0',
                              PRIMARY KEY (`post_id`),
                              CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`)
                            ) ENGINE=InnoDB"
		);
	}
}