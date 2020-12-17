<?php

declare(strict_types=1);

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Command\Create\CreateCommand;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Command\Update\UpdateCommand;
use BetterReview\Review\Application\Command\Update\UpdateHandler;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;

class SaveReviewController
{
    /** @var CreateHandler */
    private $createReviewHandler;

    /** @var UpdateHandler */
    private $updateReviewHandler;

    public function __construct()
    {
        $this->createReviewHandler = Container::resolve(CreateHandler::class);
        $this->updateReviewHandler = Container::resolve(UpdateHandler::class);
    }

    public function run()
    {
        if (isset($_POST['page']) && $_POST['page'] === 'save-review') {
            $this->save();

            wp_redirect(admin_url('/admin.php?page=reviews', 'admin'), 301);
            exit;
        }

    }

    private function save(): void
    {
        if (!empty($_POST['uuid'])) {
            $this->updateReviewHandler->run(new UpdateCommand(
                esc_attr($_POST['uuid']),
                (int) esc_attr($_POST['post_id']),
                esc_attr($_POST['status']),
                esc_attr($_POST['author']),
                esc_attr($_POST['title']),
                esc_attr($_POST['content']),
                esc_attr($_POST['email']),
                (float) esc_attr($_POST['stars'])
            ));
            return;
        }

        $this->createReviewHandler->run(new CreateCommand(
            (int) esc_attr($_POST['post_id']),
            esc_attr($_POST['author']),
            esc_attr($_POST['title']),
            esc_attr($_POST['content']),
            esc_attr($_POST['email']),
            (float) esc_attr($_POST['stars'])
        ));
    }

}