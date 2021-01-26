<?php
/**
 * This is the plugin bootstrap file.
 * Tip: Go to src/UI/Wordpress/Front/assets to edit public css.
 *
 * @link              https://github.com/warguns/wp-reviews-hexagonal-architecture-example
 * @since             1.0.0
 * @package HexagonalReviews
 *
 * @wordpress-plugin
 * Plugin Name:       Hexagonal Reviews
 * Plugin URI:        https://github.com/warguns/wp-reviews-hexagonal-architecture-example
 * Description:       The Wp Reviews you always desired
 * Version:           1.2.11
 * Author:            Warguns
 * Author URI:        https://github.com/warguns
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hexagonal-reviews
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

use HexagonalReviews\Shared\Infrastructure\Wordpress\Activate;
use HexagonalReviews\Shared\Infrastructure\Wordpress\Uninstall;

require plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * Activate Hexagonal Reviews
 */
function hexrev_activate() {
	Activate::activate();
}
register_activation_hook( __FILE__, 'hexrev_activate' );

/**
 * Deactivate Hexagonal Reviews
 */
function hexrev_uninstall() {
	Uninstall::uninstall();
}
register_uninstall_hook( __FILE__, 'hexrev_uninstall' );

/**
 * Run Hexagonal Reviews
 */
function run_hexagonal_reviews() {
	$plugin = new HexagonalReviews\Shared\Infrastructure\Wordpress\Kernel();
	$plugin->run();
}

run_hexagonal_reviews();
