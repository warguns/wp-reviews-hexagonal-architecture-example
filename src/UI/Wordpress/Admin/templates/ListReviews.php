<?php
/**
 * ListReview
 *
 * @package UI
 */

	use HexagonalReviews\Shared\Infrastructure\Wordpress\ReviewsAdminTable;
	/**
	 * Reviews Admin table.
	 *
	 * @var ReviewsAdminTable $table table.
	 */
?>
<div class="wrap">
	<h1>
		<?php esc_html_e( 'List Reviews', 'hexagonal-reviews' ); ?>
		<a class="button action create" href='admin.php?page=edit-review'><?php esc_html_e( 'Create New', 'hexagonal-reviews' ); ?></a>
	</h1>

	<?php if ( isset( $_POST['action'], $_REQUEST['listreviews'] ) && 'bulk-publish' === $_POST['action'] && check_admin_referer( 'listreviews', 'listreviews' ) && wp_verify_nonce( sanitize_key( $_REQUEST['listreviews'] ), 'listreviews' ) ) { ?>
		<div id="reviews">
			<h2 class="alert"> <?php esc_html_e( 'Option only for PRO Users', 'hexagonal-reviews' ); ?> </h2>
		</div>
	<?php } ?>

	<p>
		<?php esc_html_e( 'You can show your reviews using the shortcut:', 'hexagonal-reviews' ); ?> <code>[hexagonal-reviews
			post_id=1]</code>
	</p>

	<form action="" method="post">
		<?php $table->search_box( __( 'Search' ), 'search-box-id' ); ?>
		<input type="hidden" name="page" value="reviews"/>
		<?php $table->prepare_items(); ?>
		<?php $table->display(); ?>
		<?php wp_nonce_field( 'listreviews', 'listreviews' ); ?>
	</form>
</div>
