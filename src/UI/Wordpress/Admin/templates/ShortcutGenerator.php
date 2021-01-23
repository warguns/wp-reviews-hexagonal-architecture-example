<?php
/**
 * ShortcutGenerator
 *
 * @package UI
 */

?>
<div id="reviews" class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?> </h1>
	<form id="shortcut-generator" method="post" action="">
		<div id="universal-message-container">
			<h2><?php esc_html_e( 'Shortcut Generator', 'hexagonal-reviews' ); ?></h2>
			<div class="form-group">
				<label for="post_id"><?php esc_html_e( 'Post ID:', 'hexagonal-reviews' ); ?></label>
				<input type="text" class="form-control" id="post_id" name="post_id" aria-describedby="titleHelp" placeholder="<?php esc_html_e( 'Post ID:', 'hexagonal-reviews' ); ?>" value="1">
			</div>
			<div class="form-group">
				<label for="form_visible"><?php esc_html_e( 'Form:', 'hexagonal-reviews' ); ?></label>
				<select id="form_visible" name="form_visible">
					<option value="1" selected="selected">
						<?php esc_html_e( 'Show', 'hexagonal-reviews' ); ?>
					</option>
					<option value="0">
						<?php esc_html_e( 'Hide', 'hexagonal-reviews' ); ?>
					</option>
				</select>
			</div>
			<div class="form-group">
				<label for="avg_visible"><?php esc_html_e( 'Average:', 'hexagonal-reviews' ); ?></label>
				<select id="avg_visible" name="avg_visible">
					<option value="1" selected="selected">
						<?php esc_html_e( 'Show', 'hexagonal-reviews' ); ?>
					</option>
					<option value="0">
						<?php esc_html_e( 'Hide', 'hexagonal-reviews' ); ?>
					</option>
				</select>
			</div>
			<div class="form-group">
				<label for="reviews_visible"><?php esc_html_e( 'Reviews:', 'hexagonal-reviews' ); ?></label>
				<select id="reviews_visible" name="reviews_visible">
					<option value="1" selected="selected">
						<?php esc_html_e( 'Show', 'hexagonal-reviews' ); ?>
					</option>
					<option value="0">
						<?php esc_html_e( 'Hide', 'hexagonal-reviews' ); ?>
					</option>
				</select>
			</div>
			<div class="form-group">
				<label for="shortcut"><?php esc_html_e( 'WordPress Shortcut', 'hexagonal-reviews' ); ?></label>
				<textarea name="shortcut" id="shortcut">[hexgonal-reviews post_id=1]</textarea>
			</div>
	</form>
	<script>
		function changeForm() {
			let post_id = document.getElementById("post_id").value;
			let form_visible = document.getElementById("form_visible").value;
			let avg_visible = document.getElementById("avg_visible").value;
			let reviews_visible = document.getElementById("reviews_visible").value;

			document.getElementById("shortcut").value = `[hexagonal-reviews post_id=${post_id} form_visible=${form_visible} avg_visible=${avg_visible} reviews_visible=${reviews_visible}]`;
			document.getElementById("shortcut").select();
		}

		document.getElementById("post_id").addEventListener("change", changeForm);
		document.getElementById("form_visible").addEventListener("change", changeForm);
		document.getElementById("avg_visible").addEventListener("change", changeForm);
		document.getElementById("reviews_visible").addEventListener("change", changeForm);
	</script>
</div><!-- .wrap -->
