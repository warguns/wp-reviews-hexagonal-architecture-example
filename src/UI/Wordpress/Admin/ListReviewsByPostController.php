<?php

declare(strict_types=1);

namespace BetterReview\UI\Wordpress\Admin;

class ListReviewsByPostController
{
    public function run()
    {
        \add_menu_page('Reviews by Post', 'Reviews by Post', 'manage_options', 'reviews-by-post', [$this, 'load'], 'dashicons-chart-pie',999);
    }

    public function load()
    {

        $this->render();
    }

    private function render():void
    {
        include('templates/ListReviewsByPost.php');
    }
}