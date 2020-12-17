<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\Wordpress;

final class Uninstall
{
    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS `wp_better_review`;");
        $wpdb->query("DROP TABLE IF EXISTS `wp_better_review_average`;");
    }
}