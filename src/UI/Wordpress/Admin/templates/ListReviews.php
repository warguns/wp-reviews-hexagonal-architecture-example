<?php
/** @var \BetterReview\Shared\Infrastructure\Wordpress\ReviewsAdminTable $table */
?>
<div class="wrap">
    <h1><?php _e('List Reviews','better-reviews'); ?> <a class="button action create" href='admin.php?page=edit-review'><?php _e('Create New','better-reviews'); ?></a></h1>

    <?php if ((isset($_POST['action']) && $_POST['action'] === 'bulk-publish')) { ?>
            <div id="reviews">
                <h2 class="alert"> <?php _e('Option only for PRO Users', 'better-reviews'); ?> </h2>
            </div>
    <?php } ?>

    <p>
        <?php _e('You can show your reviews using the shortcut:','better-reviews'); ?> <code>[better-reviews post_id=1]</code>
    </p>

    <form action="admin.php?page=reviews" method="post">
        <?php $table->search_box( __( 'Search' ), 'search-box-id' ); ?>
        <input type="hidden" name="page" value="reviews"/>
        <?php $table->prepare_items() ?>
        <?php $table->display() ?>
        <?php wp_nonce_field( 'listreviews' , 'listreviews'); ?>
    </form>
</div>
