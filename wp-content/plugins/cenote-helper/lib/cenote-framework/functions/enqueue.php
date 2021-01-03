<?php
if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_framework_admin_enqueue_scripts')) {
	function cenote_framework_admin_enqueue_scripts() {
		// admin utilities
		wp_enqueue_media();

		// wp core styles
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('wp-jquery-ui-dialog');

		// framework core styles
		wp_enqueue_style('datetimepicker', CENOTE_URI . '/assets/css/jquery.datetimepicker.css', array(), '1.0.0', 'all');
		wp_enqueue_style('spectrum', CENOTE_URI . '/assets/css/spectrum.css', array(), '1.0.0', 'all');
		wp_enqueue_style('cenote-helper', CENOTE_URI . '/assets/css/cenote-framework.css', array(), '1.0.0', 'all');
		wp_enqueue_style('font-awesome', CENOTE_URI . '/assets/css/font-awesome.css', array(), '4.2.0', 'all');
		wp_enqueue_style('font-themify', CENOTE_URI . '/assets/vendor/themify-icons/themify-icons.css', array(), '1.0.0', 'all');
		wp_enqueue_style('cenote-customize', CENOTE_URI . '/assets/css/customize.css', array(), '1.0.0', 'all');

		if (CENOTE_ACTIVE_LIGHT_THEME) {
			wp_enqueue_style('cenote-light-theme', CENOTE_URI . '/assets/css/cenote-theme-light.css', array(), "1.0.0", 'all');
		}

		if (is_rtl()) {
			wp_enqueue_style('cenote-framework-rtl', CENOTE_URI . '/assets/css/cenote-framework-rtl.css', array(), '1.0.0', 'all');
		}

		// wp core scripts
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-slider');

		// framework core scripts
		wp_enqueue_script('datetimepicker', CENOTE_URI . '/assets/js/vendor/jquery.datetimepicker.js', array(), '1.0.0', true);
		wp_enqueue_script('spectrum', CENOTE_URI . '/assets/js/vendor/spectrum.js', array(), '1.8.0', true);
		wp_enqueue_script('cenote-plugins', CENOTE_URI . '/assets/js/cenote-plugins.js', array(), '1.0.0', true);
		wp_enqueue_script('cenote-helper', CENOTE_URI . '/assets/js/cenote-framework.js', array('jquery'), '1.0.0', true);

        wp_localize_script(
            'cenote-helper', 'acsL10n', array(
                'delete_sidebar_area'   => esc_html__('Are you sure you want to delete this sidebar?', 'cenote-helper'),
           )
       );

	}

	add_action('admin_enqueue_scripts', 'cenote_framework_admin_enqueue_scripts');
}
