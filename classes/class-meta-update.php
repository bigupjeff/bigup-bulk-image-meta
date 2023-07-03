<?php
namespace Bigup\Plugin_Bootstrap;

/**
 * Bigup Plugin Bootstrap - Utility Functions.
 *
 * A class of utility functions for use throughout the plugin.
 *
 * @package bigup_plugin_bootstrap
 * @author Jefferson Real <me@jeffersonreal.uk>
 * @copyright Copyright (c) 2023, Jefferson Real
 * @license GPL3+
 * @link https://jeffersonreal.uk
 */
class Meta_Update {

	/**
	 * Get all images as attachment posts.
	 */
	public static function get_all_images() {
		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		);
		return new WP_Query( $args );
	}


	/**
	 * Output a list of images that are missing meta data.
	 */
	public static function output_list_missing_meta() {

		$attachments = self::get_all_images();

		while ( $attachments->have_posts() ) :
			$attachments->the_post();

			$image_id      = get_the_id();
			$image_alt     = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$image_title   = get_the_title( $image_id );
			$image_caption = wp_get_attachment_caption( $image_id );

			if ( $image_alt === '' || $image_caption === '' ) {
				echo '<pre>';
				echo 'MISSING META' . "\n";
				echo 'ID: ' . $image_id . "\n";
				echo 'Title: ' . $image_title . "\n";
				echo 'Caption: ' . $image_caption . "\n";
				echo 'Alt: ' . $image_alt . "\n";
				echo '</pre>';
			}

		endwhile;
	}


	/**
	 * Output a list of images with current meta data.
	 */
	public static function output_list_current_meta() {

		$attachments = self::get_all_images();

		while ( $attachments->have_posts() ) :
			$attachments->the_post();

			$image_id      = get_the_id();
			$image_alt     = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$image_title   = get_the_title( $image_id );
			$image_caption = wp_get_attachment_caption( $image_id );

			echo '<pre>';
			echo 'CURRENT META' . "\n";
			echo 'ID: ' . $image_id . "\n";
			echo 'Title: ' . $image_title . "\n";
			echo 'Caption: ' . $image_caption . "\n";
			echo 'Alt: ' . $image_alt . "\n";
			echo "\n";
			echo '</pre>';

		endwhile;
	}


	/**
	 * Output a list of images with proposed meta data.
	 */
	public static function output_list_proposed_meta() {

		$attachments = self::get_all_images();

		while ( $attachments->have_posts() ) :
			$attachments->the_post();

			// Get meta from DB.
			$image_id      = get_the_id();
			$image_alt     = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$image_title   = get_the_title( $image_id );
			$image_caption = wp_get_attachment_caption( $image_id );

			// Clean the title.
			$no_hyphen_title  = str_replace( '-', ' ', $image_title );
			$no_special_title = preg_replace( '/[^a-zA-Z0-9 ]+/', '', $no_hyphen_title );
			$clean_title      = ucfirst( $no_special_title );

			// Clean the caption.
			$no_hyphen_cap  = str_replace( '-', ' ', $image_caption );
			$no_special_cap = preg_replace( '/[^a-zA-Z0-9 ]+/', '', $no_hyphen_cap );
			$clean_cap      = ucfirst( $no_special_cap );

			if ( $image_alt === '' && $image_caption !== '' ) {
				$new_alt = $clean_cap;
			} elseif ( $image_alt === '' && $image_title !== '' ) {
				$new_alt = $clean_title;
			} else {
				$new_alt = $image_alt;
			}

			echo '<pre>';
			echo '# CURRENT META' . "\n";
			echo 'ID: ' . $image_id . "\n";
			echo 'Title: ' . $image_title . "\n";
			echo 'Caption: ' . $image_caption . "\n";
			echo 'Alt: ' . $image_alt . "\n";
			echo "\n";
			echo '# PROPOSED META' . "\n";
			echo 'Clean title: ' . $clean_title . "\n";
			echo 'Clean caption: ' . $clean_cap . "\n";
			echo 'New Alt: ' . $new_alt . "\n";
			echo "\n";
			echo '</pre>';

		endwhile;
	}


	/**
	 * Generate the missing meta data for all images.
	 */
	public static function generate_missing_meta() {

		$attachments = self::get_all_images();

		while ( $attachments->have_posts() ) :
			$attachments->the_post();

			// Get meta from DB.
			$image_id          = get_the_id();
			$old_alt         = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$old_title       = get_the_title( $image_id );
			$old_description = get_the_content( $image_id );
			$old_caption     = wp_get_attachment_caption( $image_id );

			// Clean the title.
			$no_hyphen_title  = str_replace( '-', ' ', $old_title );
			$no_special_title = preg_replace( '/[^a-zA-Z0-9 ]+/', '', $no_hyphen_title );
			$clean_title      = ucfirst( $no_special_title );

			// Clean the caption.
			$no_hyphen_cap  = str_replace( '-', ' ', $old_caption );
			$no_special_cap = preg_replace( '/[^a-zA-Z0-9 ]+/', '', $no_hyphen_cap );
			$clean_cap      = ucfirst( $no_special_cap );

			if ( $old_alt === '' && $old_caption !== '' ) {
				$new_alt = $clean_cap;
			} elseif ( $old_alt === '' && $old_title !== '' ) {
				$new_alt = $clean_title;
			} else {
				$new_alt = $old_alt; // Leave unchanged.
			}

			// Set alt.
			update_post_meta( $image_id, '_wp_attachment_image_alt', $new_alt );
			// Set meta (e.g. title, excerpt, content).
			$new_image_meta = array(
				'ID'           => $image_id,    // Specify the image ID to be updated.
				'post_title'   => $clean_title, // Set image title to cleaned title.
				'post_excerpt' => $new_alt,     // Set image caption (excerpt) to new alt text.
				'post_content' => $new_alt,     // Set image description (content) to new alt text.
			);
			wp_update_post( $new_image_meta );

			// Get updated meta from DB.
			$updated_alt         = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$updated_title       = get_the_title( $image_id );
			$updated_description = get_the_content( $image_id );
			$updated_caption     = wp_get_attachment_caption( $image_id );

			// Output results to front end.
			echo '<pre>';
			echo '# OLD META' . "\n";
			echo 'ID: ' . $image_id . "\n";
			echo 'Title: ' . $old_title . "\n";
			echo 'Description: ' . $old_description . "\n";
			echo 'Caption: ' . $old_caption . "\n";
			echo 'Alt: ' . $old_alt . "\n";
			echo "\n";
			echo '# NEW META' . "\n";
			echo 'Clean title: ' . $updated_title . "\n";
			echo 'Description: ' . $updated_description . "\n";
			echo 'Clean caption: ' . $updated_caption . "\n";
			echo 'New Alt: ' . $updated_alt . "\n";
			echo "\n";
			if ( is_wp_error( $image_id ) ) {
				echo 'Error!' . "\n";
				foreach ( $errors as $error ) {
					echo $error . "\n";
				}
			}
			echo '</pre>';

		endwhile;
	}
}
