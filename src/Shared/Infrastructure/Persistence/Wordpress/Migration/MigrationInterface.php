<?php


namespace HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration;


interface MigrationInterface {
	/**
	 * Gets the version
	 */
	public function get_version(): string;

	/**
	 * Runs the migration
	 */
	public function run(): void;
}