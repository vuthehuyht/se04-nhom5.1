<?php
if (!defined('ABSPATH')) {
	return;
}
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_validate_email')) {
	function cenote_validate_email($value, $field) {

		if (!sanitize_email($value)) {
			return __('Please write a valid email address!', 'cenote-helper');
		}

	}

	add_filter('cenote_validate_email', 'cenote_validate_email', 10, 2);
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_validate_numeric')) {
	function cenote_validate_numeric($value, $field) {

		if (!is_numeric($value)) {
			return __('Please write a numeric data!', 'cenote-helper');
		}

	}

	add_filter('cenote_validate_numeric', 'cenote_validate_numeric', 10, 2);
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_validate_required')) {
	function cenote_validate_required($value) {
		if (empty($value)) {
			return __('Fatal Error! This field is required!', 'cenote-helper');
		}
	}

	add_filter('cenote_validate_required', 'cenote_validate_required');
}
