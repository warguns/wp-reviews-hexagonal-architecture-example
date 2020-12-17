<?php
/** @var \BetterReview\Review\Domain\ValueObject\ReviewCollection $reviewCollection */

/** @var \BetterReview\Average\Domain\DTO\ReviewStats $reviewStats */

/** @var WP_Post $post */

use \BetterReview\Shared\Infrastructure\Gravatar\GravatarService;

?>
<div id="reviews" class="alignwide">
    <div class="total">
        <?php _e('Average:','better-reviews'); ?> <?php echo number_format($reviewStats->getAverage(), 2); ?> <?php _e('of','better-reviews'); ?> <?php echo $reviewStats->getReviewCount(); ?> <?php _e('Reviews','better-reviews'); ?>
    </div>
    <form class="alignwide average" action="?p=<?php echo $_REQUEST['p']; ?>" method="post" class="review-form">
            <h4><?php _e('Review product','better-reviews'); ?></h4>
            <span><?php _e('Share your opinion with other clients','better-reviews'); ?></span>
            <?php if (isset($_REQUEST['submit-opinion'])) { ?>
                <p class="alert">
                    <?php _e('Thank you for your review! Your review is on approval process, once is approved your review will be published!','better-reviews') ?>
                </p>
            <?php } else { ?>
                <p class="form-group">
                    <label for="author"><?php _e('Name','better-reviews'); ?> <span class="required">*</span></label>
                    <input type="text" class="form-control" id="author" name="author" aria-describedby="authorHelp" placeholder="<?php _e('Name','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getAuthor() : '' ?>" required="required">
                </p>
                <p class="form-group">
                    <label for="stars"><?php _e('Rating','better-reviews'); ?> <span class="required">*</span></label>
                    <div class="rating">
                        <?php for ($i=5;$i>0;$i--) { ?>
                            <input type="radio" name="rating" id="rating-<?php echo $i;?>" value="<?php echo $i;?>" required="required" >
                            <label for="rating-<?php echo $i;?>"></label>
                        <?php } ?>
                    </div>
                </p>
                <p class="form-group">
                    <label for="email"><?php _e('Email','better-reviews'); ?> <span class="required">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="<?php _e('Insert your Email','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getEmail()->getEmail() : '' ?>" required="required">
                </p>
                <p class="form-group">
                    <label for="title"><?php _e('Title','better-reviews'); ?> <span class="required">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="textHelp" placeholder="<?php _e('What was the most important?','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getTitle() : '' ?>" required="required">
                </p>

                <p class="form-group">
                    <label for="content"><?php _e('Content','better-reviews'); ?></label>
                    <textarea id="content" name="content" required="required" placeholder="<?php _e('What did liked you and what not? Express your opinion.','better-reviews'); ?>"><?php echo (isset($review)) ? $review->getContent() : ''; ?></textarea>
                </p>

                <p class="form-group">
                    <input type="checkbox" class="checkbox" id="validate" name="validate" required="required" value="1"/><label for="validate" class="checkbox"><?php _e('I have read the Terms & Conditions and the Privacy Policy','better-reviews'); ?></label>
                </p>
                <p class="form-submit">
                    <input name="submit-opinion" type="submit" id="submit" class="submit" value="<?php _e('Publish my opinion','better-reviews'); ?>">
                    <?php wp_nonce_field( 'addreview', 'addreview'); ?>
                    <input type="hidden" name="uuid" value="<?php echo (isset($review)) ? $review->getUuid() : '' ?>">
                    <input type="hidden" name="post_id" value="<?php echo $_REQUEST['p']; ?>">
                </p>
            <?php } ?>
    </form>
    <script>
        var DateTime = luxon.DateTime;
    </script>
    <?php
    /** @var \BetterReview\Review\Domain\Entity\Review $review */
    foreach($reviewCollection as $review) {
        ?>
        <div class="grid-container alignwide">
            <div class="grid-image"><img src="<?php echo GravatarService::image($review->getEmail()->getEmail(), 150); ?>"></div>
            <div class="grid-title"><?php echo $review->getTitle() ?></div>
            <div class="grid-author"><span class="author"><?php echo $review->getAuthor(); ?></span> <span id="time-<?php echo $review->getUuid(); ?>" class="time"></span>
                <script>
                    document.getElementById('time-<?php echo $review->getUuid(); ?>').innerHTML = DateTime.fromSeconds(<?php echo $review->getCreatedAt()->getTimestamp(); ?>).toRelative();
                </script>
            </div>
            <div class="grid-content"><?php echo $review->getContent(); ?></div>
            <div class="grid-stars">
                <div class="rating">
                    <?php for ($i=5;$i>0;$i--) { ?>
                        <input type="radio" name="rating<?php echo $review->getUuid(); ?>" id="rating-<?php echo $i;?>" value="<?php echo $i;?>" required="required" disabled="disabled" <?php echo ((int) $review->getStars()->getStars() === $i)? 'checked="checked"': ''; ?>>
                        <label for="rating-<?php echo $i;?>"></label>
                    <?php } ?>
                </div>
            </div>
        </div>

    <?php } ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "<?php echo $post->post_title; ?>",
            "description": "<?php echo $post->post_content; ?>",
            "image": "<?php echo get_the_post_thumbnail_url($post->ID); ?>",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "<?php echo number_format($reviewStats->getAverage(), 2); ?>",
                "reviewCount": "<?php echo $reviewStats->getReviewCount(); ?>"
            },
            "review": [
            <?php
                $last = $reviewCollection->count() - 1;
                foreach($reviewCollection as $key => $review) {
            ?>
                {
                    "@type": "Review",
                    "author": "<?php echo $review->getAuthor(); ?>",
                    "datePublished": "<?php echo $review->getCreatedAt()->format('Y-m-d'); ?>",
                    "reviewBody": "<?php echo esc_html($review->getContent()); ?>",
                    "name": "<?php echo $review->getTitle(); ?>",
                    "reviewRating": {
                        "@type": "Rating",
                        "bestRating": "5",
                        "ratingValue": "<?php echo $review->getStars()->getStars(); ?>",
                        "worstRating": "1"
                    }
                } <?php echo ($last !== $key)? ',':''; ?>
            <?php
                }
            ?>
            ]
        }
    </script>
</div>
