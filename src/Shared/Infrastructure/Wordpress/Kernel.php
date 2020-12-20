<?php
/**
 * Kernel
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\Wordpress;

use BetterReview\UI\Wordpress\Admin\EditReviewController;
use BetterReview\UI\Wordpress\Admin\ListReviewsByPostController;
use BetterReview\UI\Wordpress\Admin\ListReviewsController;
use BetterReview\UI\Wordpress\Admin\SaveReviewController;
use BetterReview\UI\Wordpress\Front\GetReviewsBlockController;

/**
 * Kernel Inspired directly from JWT auth's plugin: https://github.com/Tmeister/wp-api-jwt-auth
 */
class Kernel {
	/**
	 * Review Loader.
	 *
	 * @var ReviewLoader
	 */
	protected $loader;

	/**
	 * Plugin Name.
	 *
	 * @var string
	 */
	protected $plugin_name = 'better-reviews';

	/**
	 * Version
	 *
	 * @var string
	 */
	protected $version = '1.0.0';

	/**
	 * Kernel constructor.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();
		$this->define_admin_hooks();
	}

	/**
	 * Dependencies Loader.
	 */
	private function load_dependencies(): void {
		$this->loader = new ReviewLoader();
	}

	/**
	 * Sets Default Locale.
	 */
	private function set_locale(): void {
		$plugin_i18n = new Translations();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Gets the Plugin Name.
	 *
	 * @return string
	 */
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * Define Public Hooks.
	 */
	private function define_public_hooks(): void {
		$post_reviews_controler = new GetReviewsBlockController();
		$post_reviews_controler->run();
	}

	/**
	 * Define Admin Hooks.
	 */
	private function define_admin_hooks(): void {
		$list_reviews_controller = new ListReviewsController();
		$this->loader->add_action( 'admin_menu', $list_reviews_controller, 'run' );
		$list_reviews_by_post_controller = new ListReviewsByPostController();
		$this->loader->add_action( 'admin_menu', $list_reviews_by_post_controller, 'run' );
		$edit_review_controller = new EditReviewController();
		$this->loader->add_action( 'admin_menu', $edit_review_controller, 'run' );
		$this->loader->add_action( 'admin_enqueue_scripts', $edit_review_controller, 'load_styles' );
		$save_review_controller = new SaveReviewController();
		$this->loader->add_action( 'admin_menu', $save_review_controller, 'run' );
	}

	/**
	 * Run.
	 */
	public function run(): void {
		$this->loader->run();
	}

	/**
	 * Gets the loader.
	 *
	 * @return ReviewLoader
	 */
	public function get_loader(): ReviewLoader {
		return $this->loader;
	}

	/**
	 * Gets the version.
	 *
	 * @return string
	 */
	public function get_version(): string {
		return $this->version;
	}
}
