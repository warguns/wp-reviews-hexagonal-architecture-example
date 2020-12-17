<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\Wordpress;

use BetterReview\UI\Wordpress\Admin\EditReviewController;
use BetterReview\UI\Wordpress\Admin\ListReviewsByPostController;
use BetterReview\UI\Wordpress\Admin\ListReviewsController;
use BetterReview\UI\Wordpress\Admin\SaveReviewController;
use BetterReview\UI\Wordpress\Front\GetReviewsBlockController;

/**
 * Kernel Inspired directly from JWT auth's plugin: https://github.com/Tmeister/wp-api-jwt-auth
 */
class Kernel
{
    /**
     * @var ReviewLoader
     */
    protected $loader;

    /**
     * @var string
     */
    protected $pluginName = 'better-reviews';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function __construct()
    {
        $this->loadDependencies();
        $this->setLocale();
        $this->definePublicHooks();
        $this->defineAdminHooks();
    }

    private function loadDependencies()
    {
        $this->loader = new ReviewLoader();
    }

    private function setLocale()
    {
        $plugin_i18n = new Translations();
        $plugin_i18n->set_domain($this->getPluginName());
        $this->loader->addAction('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function definePublicHooks()
    {
        $post_reviews_controler = new GetReviewsBlockController();
        $post_reviews_controler->run();
    }

    private function defineAdminHooks()
    {
        $list_reviews_controller = new ListReviewsController();
        $this->loader->addAction('admin_menu', $list_reviews_controller, 'run');
        $list_reviews_by_post_controller = new ListReviewsByPostController();
        $this->loader->addAction('admin_menu', $list_reviews_by_post_controller, 'run');
        $edit_review_controller = new EditReviewController();
        $this->loader->addAction('admin_menu', $edit_review_controller, 'run');
        $this->loader->addAction('admin_enqueue_scripts', $edit_review_controller, 'loadStyles');
        $save_review_controller = new SaveReviewController();
        $this->loader->addAction('admin_menu', $save_review_controller, 'run');
    }

    public function run()
    {
        $this->loader->run();
    }

    public function getPluginName()
    {
        return $this->pluginName;
    }

    public function get_loader()
    {
        return $this->loader;
    }

    public function get_version()
    {
        return $this->version;
    }
}