<?php

declare(strict_types=1);

namespace BetterReview\UI\Wordpress\Front;

use BetterReview\Average\Application\Query\GetAverage\GetAverageHandler;
use BetterReview\Average\Application\Query\GetAverage\GetAverageQuery;
use BetterReview\Review\Application\Command\Create\CreateCommand;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostQuery;
use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;

class GetReviewsBlockController
{
    /** @var GetByPostHandler */
    private $getByPostHandler;

    /** @var GetAverageHandler */
    private $getAverageHandler;

    /** @var CreateHandler */
    private $createPostReview;

    public function __construct()
    {
        $this->getByPostHandler = Container::resolve(GetByPostHandler::class);
        $this->getAverageHandler = Container::resolve(GetAverageHandler::class);
        $this->createPostReview = Container::resolve(CreateHandler::class);
    }

    public function run()
    {
        add_action( 'wp_enqueue_scripts',  [$this, 'loadScripts']  );
        add_shortcode( 'better-reviews', [$this, 'load'] );

    }

    public function loadScripts()
    {
        wp_enqueue_script('luxon','//moment.github.io/luxon/global/luxon.min.js');
        wp_enqueue_style( 'better-review-stars', plugins_url( '/assets/stars.css', __FILE__ ), array(), '20201213', 'all');
        wp_enqueue_style( 'better-review-style', plugins_url( '/assets/style.css', __FILE__ ), array(), '20201213', 'all');
    }

    public function load($attr)
    {
        $post_id = 0;
        $limit = 0;
        $offset = 0;

        extract(shortcode_atts( [
            'post_id' => 0,
            'limit' => 0,
            'offset' => 0
        ], $attr ),EXTR_OVERWRITE);


        if (isset($_POST['submit-opinion']) && $_POST['submit-opinion']) {
            $this->verifyNonce();

            $this->createPostReview->run(new CreateCommand(
                (int) esc_attr($post_id),
                sanitize_text_field( $_POST["author"] ),
                sanitize_text_field( $_POST["title"] ),
                esc_textarea( $_POST["content"] ),
                sanitize_email( $_POST["email"] ),
                (float) sanitize_text_field($_POST["rating"])
            ));
        }

        $reviewStats = $this->getAverageHandler->run(new GetAverageQuery(
            (int) esc_attr($post_id)
        ));

        $reviewCollection = $this->getByPostHandler->run(new GetByPostQuery(
            (int) esc_attr($post_id),
            (int) esc_attr($limit),
            (int) esc_attr($offset)
        ));

        $post = \WP_Post::get_instance((int) esc_attr($post_id));

        return $this->render($reviewCollection, $reviewStats, $post);
    }

    private function render(ReviewCollection $reviewCollection, ReviewStats $reviewStats, \WP_Post $post)
    {
        ob_start();
        include('templates/GetReviewsBlock.php');
        return ob_get_clean();
    }

    private function verifyNonce(): void
    {
        if (! wp_verify_nonce($_POST['addreview'], 'addreview')) {
            die('Go get a life script kiddies');
        }
    }
}