<?php

use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Shared\Infrastructure\Gravatar\GravatarService;

/**
 * Review collection
 *
 * @var ReviewCollection $review_collection review collection.
 */

/**
 * Review Stats
 *
 * @var ReviewStats $review_stats stats.
 */

/**
 * Post
 *
 * @var WP_Post $post post.
 */
?>
<div id="reviews" class="alignwide">
	<div class="total">
		<?php _e( 'Average:', 'better-reviews' ); ?><?php echo number_format( $review_stats->get_average(), 2 ); ?><?php _e( 'of', 'better-reviews' ); ?><?php echo $review_stats->get_review_count(); ?><?php _e( 'Reviews', 'better-reviews' ); ?>
	</div>
	<form class="alignwide average" action="?p=<?php echo $_REQUEST['p']; ?>" method="post" class="review-form">
		<h4><?php _e( 'Review product', 'better-reviews' ); ?></h4>
		<span><?php _e( 'Share your opinion with other clients', 'better-reviews' ); ?></span>
		<?php if ( isset( $_REQUEST['submit-opinion'] ) ) { ?>
			<p class="alert">
				<?php _e( 'Thank you for your review! Your review is on approval process, once is approved your review will be published!', 'better-reviews' ); ?>
			</p>
		<?php } else { ?>
			<p class="form-group">
				<label for="author"><?php _e( 'Name', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control" id="author" name="author" aria-describedby="authorHelp"
					   placeholder="<?php _e( 'Name', 'better-reviews' ); ?>"
					   value="<?php echo ( isset( $review ) ) ? $review->getAuthor() : ''; ?>" required="required">
			</p>
			<p class="form-group">
				<label for="stars"><?php _e( 'Rating', 'better-reviews' ); ?> <span class="required">*</span></label>
			<div class="rating">
				<?php for ( $i = 5; $i > 0; $i -- ) { ?>
					<input type="radio" name="rating" id="rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
						   required="required"/>
					<label for="rating-<?php echo $i; ?>"></label>
				<?php } ?>
			</div>
			</p>
			<p class="form-group">
				<label for="email"><?php _e( 'Email', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
					   placeholder="<?php _e( 'Insert your Email', 'better-reviews' ); ?>"
					   value="<?php echo ( isset( $review ) ) ? $review->getEmail()->getEmail() : ''; ?>"
					   required="required">
			</p>
			<p class="form-group">
				<label for="title"><?php _e( 'Title', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control" id="title" name="title" aria-describedby="textHelp"
					   placeholder="<?php _e( 'What was the most important?', 'better-reviews' ); ?>"
					   value="<?php echo ( isset( $review ) ) ? $review->getTitle() : ''; ?>" required="required">
			</p>

			<p class="form-group">
				<label for="content"><?php _e( 'Content', 'better-reviews' ); ?></label>
				<textarea id="content" name="content" required="required"
						  placeholder="<?php _e( 'What did liked you and what not? Express your opinion.', 'better-reviews' ); ?>"><?php echo ( isset( $review ) ) ? $review->getContent() : ''; ?></textarea>
			</p>

			<p class="form-group">
				<input type="checkbox" class="checkbox" id="validate" name="validate" required="required"
					   value="1"/><label for="validate"
										 class="checkbox"><?php _e( 'I have read the Terms & Conditions and the Privacy Policy', 'better-reviews' ); ?></label>
			</p>
			<p class="form-submit">
				<input name="submit-opinion" type="submit" id="submit" class="submit"
					   value="<?php _e( 'Publish my opinion', 'better-reviews' ); ?>">
				<?php wp_nonce_field( 'addreview', 'addreview' ); ?>
				<input type="hidden" name="uuid" value="<?php echo ( isset( $review ) ) ? $review->getUuid() : ''; ?>">
				<input type="hidden" name="post_id" value="<?php echo $_REQUEST['p']; ?>">
			</p>
		<?php } ?>
	</form>
	<script>
		var DateTime = luxon.DateTime;
	</script>
	<?php
	/** @var Review $review */
	foreach ( $review_collection as $review ) {
		?>
		<div class="grid-container alignwide">
			<div class="grid-image"><img
						src="<?php echo GravatarService::image( $review->get_email()->get_email(), 150 ); ?>"></div>
			<div class="grid-title"><?php echo $review->get_title(); ?></div>
			<div class="grid-author"><span class="author"><?php echo $review->get_author(); ?></span> <span
						id="time-<?php echo $review->get_uuid(); ?>" class="time"></span>
				<script>
					document.getElementById('time-<?php echo $review->get_uuid(); ?>').innerHTML = DateTime.fromSeconds(<?php echo $review->get_created_at()->getTimestamp(); ?>).toRelative();
				</script>
			</div>
			<div class="grid-content"><?php echo $review->get_content(); ?></div>
			<div class="grid-stars">
				<div class="rating">
					<?php for ( $i = 5; $i > 0; $i -- ) { ?>
						<input type="radio" name="rating<?php echo $review->get_uuid(); ?>"
							   id="rating-<?php echo $i; ?>"
							   value="<?php echo $i; ?>" required="required"
							   disabled="disabled" <?php echo ( (int) $review->get_stars()->get_stars() === $i ) ? 'checked="checked"' : ''; ?>>
						<label for="rating-<?php echo $i; ?>"></label>
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
			"image": "<?php echo get_the_post_thumbnail_url( $post->ID ); ?>",
			"aggregateRating": {
				"@type": "AggregateRating",
				"ratingValue": "<?php echo number_format( $review_stats->get_average(), 2 ); ?>",
				"reviewCount": "<?php echo $review_stats->get_review_count(); ?>"
			},
			"review": [
			<?php
			$last = $review_collection->count() - 1;
			foreach ( $review_collection as $key => $review ) {
				?>
				{
					"@type": "Review",
					"author": "<?php echo $review->get_author(); ?>",
					"datePublished": "<?php echo $review->get_created_at()->format( 'Y-m-d' ); ?>",
					"reviewBody": "<?php echo esc_html( $review->get_content() ); ?>",
					"name": "<?php echo $review->get_title(); ?>",
					"reviewRating": {
						"@type": "Rating",
						"bestRating": "5",
						"ratingValue": "<?php echo $review->get_stars()->get_stars(); ?>",
						"worstRating": "1"
					}
				} <?php echo ( $last !== $key ) ? ',' : ''; ?>
				<?php
			}
			?>
			]
		}


	</script>
</div>
