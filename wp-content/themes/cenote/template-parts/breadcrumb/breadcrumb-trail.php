<?php
/**
 * Shows breadcrumb
 *
 * @package cenote
 */

// If we are front page or blog page, return.
if ( is_front_page() || is_home() ) {
	return;
}

// If file is not already loaded, loaded it now.
if ( ! function_exists( 'breadcrumb_trail' ) ) {
	include get_template_directory() . '/inc/compatibility/breadcrumb.php';
}
?>
