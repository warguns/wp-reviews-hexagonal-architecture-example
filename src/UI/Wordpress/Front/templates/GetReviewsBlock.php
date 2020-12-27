<?php
/**
 * GetReviewsBlock
 *
 * @package UI
 */

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
 * @var bool    $submitted check if is the form submitted.
 */
?>
<div id="reviews" class="alignwide">
	<div class="total">
		<?php esc_html_e( 'Average:', 'better-reviews' ); ?>
		<?php echo esc_html( number_format( $review_stats->get_average(), 2 ) ); ?> <?php esc_html_e( 'of', 'better-reviews' ); ?> <?php echo esc_html( $review_stats->get_review_count() ); ?><?php esc_html_e( 'Reviews', 'better-reviews' ); ?>
	</div>
	<form class="alignwide average" action="?p=<?php echo esc_html( $post->ID ); ?>" method="post" class="review-form">
		<h4><?php esc_html_e( 'Review product', 'better-reviews' ); ?></h4>
		<span><?php esc_html_e( 'Share your opinion with other clients', 'better-reviews' ); ?></span>
		<?php if ( $submitted ) { ?>
			<p class="alert">
				<?php esc_html_e( 'Thank you for your review! Your review is on approval process, once is approved your review will be published!', 'better-reviews' ); ?>
			</p>
		<?php } else { ?>
			<p class="form-group">
				<label for="author"><?php esc_html_e( 'Name', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control" id="author" name="author" aria-describedby="authorHelp" placeholder="<?php esc_html_e( 'Name', 'better-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->getAuthor() ) : null; ?>" required="required">
			</p>
			<p class="form-group">
				<label for="stars"><?php esc_html_e( 'Rating', 'better-reviews' ); ?> <span class="required">*</span></label>
			<div class="rating">
				<?php for ( $i = 5; $i > 0; $i -- ) { ?>
					<input type="radio" name="rating" id="rating-<?php echo esc_html( $i ); ?>" value="<?php echo esc_html( $i ); ?>" required="required"/>
					<label for="rating-<?php echo esc_html( $i ); ?>"></label>
				<?php } ?>
			</div>
			</p>
			<p class="form-group">
				<label for="email"><?php esc_html_e( 'Email', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="<?php esc_html_e( 'Insert your Email', 'better-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->getEmail()->getEmail() ) : null; ?>" required="required">
			</p>
			<p class="form-group">
				<label for="title"><?php esc_html_e( 'Title', 'better-reviews' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control" id="title" name="title" aria-describedby="textHelp" placeholder="<?php esc_html_e( 'What was the most important?', 'better-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->getTitle() ) : null; ?>" required="required">
			</p>

			<p class="form-group">
				<label for="content"><?php esc_html_e( 'Content', 'better-reviews' ); ?></label>
				<textarea id="content" name="content" required="required"placeholder="<?php esc_html_e( 'What did liked you and what not? Express your opinion.', 'better-reviews' ); ?>"><?php echo ( isset( $review ) ) ? esc_html( $review->getContent() ) : ''; ?></textarea>
			</p>

			<p class="form-group">
				<input type="checkbox" class="checkbox" id="validate" name="validate" required="required" value="1"/><label for="validate" class="checkbox"><?php esc_html_e( 'I have read the Terms & Conditions and the Privacy Policy', 'better-reviews' ); ?></label>
			</p>
			<p class="form-submit">
				<input name="submit-opinion" type="submit" id="submit" class="submit" value="<?php esc_html_e( 'Publish my opinion', 'better-reviews' ); ?>">
				<?php wp_nonce_field( 'addreview', 'addreview' ); ?>
				<input type="hidden" name="uuid" value="<?php echo ( isset( $review ) ) ? esc_html( $review->getUuid() ) : ''; ?>">
				<input type="hidden" name="post_id" value="<?php echo esc_html( $post->ID ); ?>">
			</p>
		<?php } ?>
	</form>
	<script>
		var DateTime = luxon.DateTime;
	</script>
	<?php
	/**
	 * Review object.
	 *
	 * @var Review $review review.
	 */
	foreach ( $review_collection as $review ) {
		?>
		<div class="grid-container alignwide">
			<div class="grid-image"><img
						src="<?php echo esc_html( GravatarService::image( $review->get_email()->get_email(), 150 ) ); ?>"></div>
			<div class="grid-title"><?php echo esc_html( $review->get_title() ); ?></div>
			<div class="grid-author"><span class="author"><?php echo esc_html( $review->get_author() ); ?></span> <span
						id="time-<?php echo esc_html( $review->get_uuid() ); ?>" class="time"></span>
				<script>
					document.getElementById('time-<?php echo esc_html( $review->get_uuid() ); ?>').innerHTML = DateTime.fromSeconds(<?php echo esc_html( $review->get_created_at()->getTimestamp() ); ?>).toRelative();
				</script>
			</div>
			<div class="grid-content"><?php echo esc_html( $review->get_content() ); ?></div>
			<div class="grid-stars">
				<div class="rating">
					<?php for ( $i = 5; $i > 0; $i -- ) { ?>
						<input type="radio" name="rating<?php echo esc_html( $review->get_uuid() ); ?>" id="rating-<?php echo esc_html( $i ); ?>" value="<?php echo esc_html( $i ); ?>" required="required" disabled="disabled" <?php echo ( (int) $review->get_stars()->get_stars() === $i ) ? esc_html( 'checked="checked"' ) : null; ?>>
						<label for="rating-<?php echo esc_html( $i ); ?>"></label>
					<?php } ?>
				</div>
			</div>
		</div>

	<?php } ?>
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "Product",
			"name": "<?php echo esc_html( $post->post_title ); ?>",
			"description": "<?php echo esc_html( $post->post_content ); ?>",
			"image": "<?php echo esc_html( get_the_post_thumbnail_url( $post->ID ) ); ?>",
			"aggregateRating": {
				"@type": "AggregateRating",
				"ratingValue": "<?php echo esc_html( number_format( $review_stats->get_average(), 2 ) ); ?>",
				"reviewCount": "<?php echo esc_html( $review_stats->get_review_count() ); ?>"
			},
			"review": [
			<?php
			$last = $review_collection->count() - 1;
			foreach ( $review_collection as $key => $review ) {
				?>
				{
					"@type": "Review",
					"author": "<?php echo esc_html( $review->get_author() ); ?>",
					"datePublished": "<?php echo esc_html( $review->get_created_at()->format( 'Y-m-d' ) ); ?>",
					"reviewBody": "<?php echo esc_html( $review->get_content() ); ?>",
					"name": "<?php echo esc_html( $review->get_title() ); ?>",
					"reviewRating": {
						"@type": "Rating",
						"bestRating": "5",
						"ratingValue": "<?php echo esc_html( $review->get_stars()->get_stars() ); ?>",
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
