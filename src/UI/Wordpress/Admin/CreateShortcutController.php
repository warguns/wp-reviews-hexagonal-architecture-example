<?php
/**
 * CreateShortcutController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace HexagonalReviews\UI\Wordpress\Admin;

use function add_menu_page;

/**
 * Class CreateShortcutController
 *
 * @package HexagonalReviews\UI\Wordpress\Admin
 */
class CreateShortcutController {
	/**
	 * Run
	 */
	public function run() {
		add_submenu_page( 'reviews', 'Reviews Shortcut Generator', 'Reviews Shortcut Generator', 'manage_options', 'reviews-shortcut-generator', array( $this, 'load' ), 999 );
	}

	/**
	 * Load
	 */
	public function load() {
		$this->render();
	}

	/**
	 * Render
	 */
	private function render(): void {
		include 'templates/ShortcutGenerator.php';
	}
}
