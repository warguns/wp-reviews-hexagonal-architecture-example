<?php

/**
 * The plugin bootstrap file.
 *
 *
 * @link              https://cristianbargans.es
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Better Reviews
 * Plugin URI:        https://cristianbargans.es
 * Description:       The Wp Reviews you always desired
 * Version:           1.2.6
 * Author:            Cristian Bargans
 * Author URI:        https://cristianbargans.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       better-reviews
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

use BetterReview\Shared\Infrastructure\Wordpress\Activate;
use BetterReview\Shared\Infrastructure\Wordpress\Uninstall;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';


function activate() {
    Activate::activate();
}
register_activation_hook( __FILE__, 'activate' );

function uninstall() {
    Uninstall::uninstall();
}
register_uninstall_hook( __FILE__, 'uninstall' );


function run_better_reviews()
{
    $plugin = new BetterReview\Shared\Infrastructure\Wordpress\Kernel();
    $plugin->run();
}

run_better_reviews();