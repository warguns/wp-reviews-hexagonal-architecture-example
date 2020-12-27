<?php
/**
 * EditReview
 *
 * @package UI
 */

	use HexagonalReviews\Review\Domain\Entity\Review;
	use HexagonalReviews\Review\Domain\ValueObject\Status;
	/**
	 * Review
	 *
	 * @var Review $review review.
	 */
?>
<div id="reviews" class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?> </h1>

	<form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page=save-review' ) ); ?>">
		<input type="hidden" name="page" value="save-review">
		<div id="universal-message-container">
			<h2><?php esc_html_e( 'Edit Review', 'hexagonal-reviews' ); ?></h2>
			<input type="hidden" name="uuid" value="<?php echo ( isset( $review ) ) ? esc_html( $review->get_uuid() ) : null; ?>">
			<div class="form-group">
				<label for="post_id"><?php esc_html_e( 'Post ID:', 'hexagonal-reviews' ); ?></label>
				<input type="text" class="form-control" id="post_id" name="post_id" aria-describedby="titleHelp" placeholder="<?php esc_html_e( 'Post ID:', 'hexagonal-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->get_product_id()->get_id() ) : null; ?>">
			</div>
			<div class="form-group">
				<label for="status"><?php esc_html_e( 'Status:', 'hexagonal-reviews' ); ?></label>
				<select name="status">
					<option value="<?php echo esc_html( Status::PENDING ); ?>" <?php ( isset( $review ) && $review->get_status()->get_status() === Status::PENDING ) ? esc_html_e( 'selected="selected"' ) : null; ?>>
						<?php echo esc_html( ucfirst( __( 'pending', 'hexagonal-reviews' ) ) ); ?>
					</option>
					<option value="<?php echo esc_html( Status::PUBLISHED ); ?>" <?php ( isset( $review ) && $review->get_status()->get_status() === Status::PUBLISHED ) ? esc_html_e( 'selected="selected"' ) : null; ?>>
						<?php echo esc_html( ucfirst( __( 'published', 'hexagonal-reviews' ) ) ); ?>
					</option>
					<option value="<?php echo esc_html( Status::REJECTED ); ?>" <?php ( isset( $review ) && $review->get_status()->get_status() === Status::REJECTED ) ? esc_html_e( 'selected="selected"' ) : null; ?>>
						<?php echo esc_html( ucfirst( __( 'rejected', 'hexagonal-reviews' ) ) ); ?>
					</option>
				</select>
			</div>
			<div class="form-group">
				<label for="author"><?php esc_html_e( 'Author', 'hexagonal-reviews' ); ?></label>
				<input type="text" class="form-control" id="author" name="author" aria-describedby="authorHelp" placeholder="<?php esc_html_e( 'Author', 'hexagonal-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->get_author() ) : ''; ?>">
			</div>
			<div class="form-group">
				<label for="stars"><?php esc_html_e( 'Stars', 'hexagonal-reviews' ); ?></label>
				<div class="rating">
					<?php for ( $i = 5; $i > 0; $i -- ) { ?>
						<input type="radio" name="stars" id="rating-<?php echo esc_html( $i ); ?>" value="<?php echo esc_html( $i ); ?>" required="required" <?php echo ( isset( $review ) ) ? ( ( (int) $review->get_stars()->get_stars() === $i ) ? 'checked="checked"' : '' ) : ''; ?>>
						<label for="rating-<?php echo esc_html( $i ); ?>"></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label for="email"><?php esc_html_e( 'Email', 'hexagonal-reviews' ); ?></label>
				<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="<?php esc_html_e( 'Email', 'hexagonal-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->get_email()->get_email() ) : ''; ?>">
			</div>
			<div class="form-group">
				<label for="title"><?php esc_html_e( 'Title', 'hexagonal-reviews' ); ?></label>
				<input type="text" class="form-control" id="title" name="title" aria-describedby="textHelp" placeholder="<?php esc_html_e( 'Title', 'hexagonal-reviews' ); ?>" value="<?php echo ( isset( $review ) ) ? esc_html( $review->get_title() ) : ''; ?>">
			</div>

			<div class="form-group">
				<label for="content"><?php esc_html_e( 'Content', 'hexagonal-reviews' ); ?></label>
				<textarea name="content" id="content"><?php echo ( isset( $review ) ) ? esc_html( $review->get_content() ) : null; ?></textarea>
			</div>


			<?php
			wp_nonce_field( 'edit-review', 'edit-review' );
			submit_button();
			?>
	</form>

</div><!-- .wrap -->
