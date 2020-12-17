<?php

declare(strict_types=1);

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Query\Get\GetHandler;
use BetterReview\Review\Application\Query\Get\GetQuery;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;

class EditReviewController
{
    /** @var GetHandler */
    private $getHandler;

    public function __construct()
    {
        $this->getHandler = Container::resolve(GetHandler::class);
    }

    public function run()
    {
        \add_submenu_page(null, __('Edit Review', 'better-reviews'), __('Edit Review', 'better-reviews'), 'manage_options', 'edit-review', [$this, 'load'],999);
    }

    public function load()
    {
        if (isset($_REQUEST['uuid'])) {
            $review = $this->getHandler->run(new GetQuery(
                esc_attr($_REQUEST['uuid'])
            ));
        }

        include('templates/EditReview.php');
    }

    public function loadStyles()
    {
        wp_enqueue_style('admin-styles', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    }
}