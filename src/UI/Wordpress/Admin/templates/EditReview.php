<?php
/** @var \BetterReview\Review\Domain\Entity\Review $review */

use BetterReview\Review\Domain\ValueObject\Status;

?>
<div id="reviews" class="wrap">

    <h1><?php echo esc_html( get_admin_page_title() ); ?> </h1>

    <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page=save-review' ) ); ?>">
        <input type="hidden" name="page" value="save-review">
        <div id="universal-message-container">
            <h2><?php _e('Edit Review','better-reviews'); ?></h2>
            <input type="hidden" name="uuid" value="<?php echo (isset($review)) ? $review->getUuid() : '' ?>">
            <div class="form-group">
                <label for="post_id"><?php _e('Post ID:','better-reviews'); ?></label>
                <input type="text" class="form-control" id="post_id" name="post_id" aria-describedby="titleHelp" placeholder="<?php _e('Post ID:','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getPostId()->getId() : '' ?>">
            </div>
            <div class="form-group">
                <label for="status"><?php _e('Status:','better-reviews'); ?></label>
                <select name="status">
                    <option value="<?php echo Status::PENDING ?>" <?php echo (isset($review) && $review->getStatus()->getStatus() === Status::PENDING) ? 'selected="selected"' :'' ?>><?php echo ucfirst(__(Status::PENDING,'better-reviews')); ?></option>
                    <option value="<?php echo Status::PUBLISHED; ?>" <?php echo (isset($review) && $review->getStatus()->getStatus() === Status::PUBLISHED) ? 'selected="selected"' :'' ?>><?php echo ucfirst(__(Status::PUBLISHED,'better-reviews')); ?></option>
                    <option value="<?php echo Status::REJECTED ?>" <?php echo (isset($review) && $review->getStatus()->getStatus() === Status::REJECTED) ? 'selected="selected"' :'' ?>><?php echo ucfirst(__(Status::REJECTED,'better-reviews')); ?></option>
                </select>
            </div>
            <div class="form-group">
                <label for="author"><?php _e('Author','better-reviews'); ?></label>
                <input type="text" class="form-control" id="author" name="author" aria-describedby="authorHelp" placeholder="<?php _e('Author','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getAuthor() : '' ?>">
            </div>
            <div class="form-group">
                <label for="stars"><?php _e('Stars','better-reviews'); ?></label>
                <div class="rating">
                    <?php for ($i=5;$i>0;$i--) { ?>
                        <input type="radio" name="stars" id="rating-<?php echo $i;?>" value="<?php echo $i;?>" required="required" <?php echo  (isset($review)) ? (((int) $review->getStars()->getStars() === $i)? 'checked="checked"': '') :''; ?>>
                        <label for="rating-<?php echo $i;?>"></label>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email"><?php _e('Email','better-reviews'); ?></label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="<?php _e('Email','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getEmail()->getEmail() : '' ?>">
            </div>
            <div class="form-group">
                <label for="title"><?php _e('Title','better-reviews'); ?></label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="textHelp" placeholder="<?php _e('Title','better-reviews'); ?>" value="<?php echo (isset($review)) ? $review->getTitle() : '' ?>">
            </div>

            <div class="form-group">
                <label for="content"><?php _e('Content','better-reviews'); ?></label>
                <textarea name="content" id="content"><?php echo (isset($review)) ? $review->getContent() : ''; ?></textarea>
            </div>


            <?php
                wp_nonce_field( 'edit-review', 'edit-review' );
                submit_button();
            ?>
    </form>

</div><!-- .wrap -->