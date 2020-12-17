<?php

declare(strict_types=1);

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Command\Delete\DeleteCommand;
use BetterReview\Review\Application\Command\Delete\DeleteHandler;
use BetterReview\Review\Application\Query\All\ListHandler;
use BetterReview\Review\Application\Query\All\ListQuery;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use BetterReview\Shared\Infrastructure\Wordpress\ReviewsAdminTable;

class ListReviewsController
{
    /** @var ListHandler */
    private $listHandler;

    /** @var DeleteHandler */
    private $deleteHandler;

    public function __construct()
    {
        $this->listHandler = Container::resolve(ListHandler::class);
        $this->deleteHandler = Container::resolve(DeleteHandler::class);
    }

    public function run(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'adminStyle']);
        \add_menu_page('Reviews', 'Reviews', 'manage_options', 'reviews', [$this, 'load'], 'dashicons-chart-pie',999);
    }

    function adminStyle(): void
    {
        wp_enqueue_style( 'starability', plugins_url( '/../Front/assets/stars.css', __FILE__ ), array(), '20201212', 'all');
        wp_enqueue_style( 'better-review-style', plugins_url( '/../Front/assets/style.css', __FILE__ ), array(), '20201212', 'all');
    }

    public function load(): void
    {

        $this->bulkDelete();


        $reviewsResponse = $this->listHandler->run(new ListQuery(
            $_REQUEST['s'] ?? null,
            ReviewRepository::LIMIT,
            (((int) isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1) - 1)  * ReviewRepository::LIMIT,
            $_REQUEST['orderby'] ?? null,
            $_REQUEST['order'] ?? null
        ));

        $query = new \WP_Query(['post__in' => $reviewsResponse->getReviewCollection()->getPostIds()]);
        $postNames = [];
        /** @var \WP_Post $post */
        foreach ($query->posts as $post) {
            $postNames[$post->ID] = $post->post_name;
        }


        $table = new ReviewsAdminTable($reviewsResponse->getReviewCollection()->toArray(), $reviewsResponse->getTotals(), $postNames, [
            'singular' => __( 'Review', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Reviews', 'sp' ), //plural name of the listed records
            'ajax'     => false //should this table support ajax?

        ]);

        $this->render($table);
    }

    private function render(ReviewsAdminTable $table): void
    {
        include('templates/ListReviews.php');
    }

    private function verifyNonce(): void
    {
        if ( ! check_admin_referer('listreviews', 'listreviews')) {
            die( 'Go get a life script kiddies' );
        }
    }

    protected function bulkDelete(): void
    {
        if ((isset($_POST['action']) && $_POST['action'] === 'bulk-delete')) {
            $this->verifyNonce();

            $deleteUuids = esc_sql($_REQUEST['bulk-delete']);

            // loop over the array of record IDs and delete them
            foreach ($deleteUuids as $uuid) {
                $this->deleteHandler->run(new DeleteCommand($uuid));
            }
        }
    }
}