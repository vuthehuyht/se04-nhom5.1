<?php
if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_add_element')) {
	function cenote_add_element($field = array(), $value = '', $unique = '') {

		$output = '';
		$depend = '';
		$sub = (isset($field['sub'])) ? 'sub-' : '';
		$unique = (isset($unique)) ? $unique : '';
		$languages = cenote_language_defaults();
		$class = 'CENOTEFramework_Option_' . $field['type'];
		$wrap_class = (isset($field['wrap_class'])) ? ' ' . $field['wrap_class'] : '';
		$el_class = (isset($field['title'])) ? sanitize_title($field['title']) : 'no-title';
		$hidden = (isset($field['show_only_language']) && ($field['show_only_language'] != $languages['current'])) ? ' hidden' : '';
		$is_pseudo = (isset($field['pseudo'])) ? ' cenote-pseudo-field' : '';

		$dep_class = '';

		if (isset($field['dependency'])) {
			$hidden = ' hidden';
			$depend .= ' data-' . $sub . 'controller="' . $field['dependency'][0] . '"';
			$depend .= ' data-' . $sub . 'condition="' . $field['dependency'][1] . '"';
			$depend .= ' data-' . $sub . 'value="' . $field['dependency'][2] . '"';

			$dep_class = ' has-dep ';
		}

		$output .= '<div class="cenote-element cenote-element-' . $el_class . ' cenote-field-' . $field['type'] . $is_pseudo . $dep_class . $wrap_class . $hidden . '"' . $depend . '>';

		if (isset($field['title'])) {
			$field_desc = (isset($field['desc'])) ? '<p class="cenote-text-desc">' . $field['desc'] . '</p>' : '';
			$output .= '<div class="cenote-title"><h4>' . $field['title'] . '</h4>' . $field_desc . '</div>';
		}

		$output .= (isset($field['title'])) ? '<div class="cenote-fieldset">' : '';

		$value = (!isset($value) && isset($field['default'])) ? $field['default'] : $value;
		$value = (isset($field['value'])) ? $field['value'] : $value;

		if (class_exists($class)) {
			ob_start();
			$element = new $class($field, $value, $unique);
			$element->output();
			$output .= ob_get_clean();
		} else {
			$output .= '<p>' . __('This field class is not available!', 'cenote-helper') . '</p>';
		}

		$output .= (isset($field['title'])) ? '</div>' : '';
		$output .= '<div class="clear"></div>';
		$output .= '</div>';

		return $output;

	}
}

/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_encode_string')) {
	function cenote_encode_string($string) {
		return rtrim(strtr(call_user_func('base' . '64' . '_encode', addslashes(gzcompress(serialize($string), 9))), '+/', '-_'), '=');
	}
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_decode_string')) {
	function cenote_decode_string($string) {
		return unserialize(gzuncompress(stripslashes(call_user_func('base' . '64' . '_decode', rtrim(strtr($string, '-_', '+/'), '=')))));
	}
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_get_google_fonts')) {
	function cenote_get_google_fonts() {

		global $cenote_google_fonts;

		if (!empty($cenote_google_fonts)) {

			return $cenote_google_fonts;

		} else {

			ob_start();
			cenote_locate_template('fields/typography/google-fonts.json');
			$json = ob_get_clean();

			$cenote_google_fonts = json_decode($json);

			return $cenote_google_fonts;
		}

	}
}

/**
 *
 * Get icon fonts from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_get_icon_fonts')) {
	function cenote_get_icon_fonts($file, $type) {

		ob_start();

		if ($type) {
			cenote_locate_template($file);
		} else {
			load_template($file, true);
		}

		$json = ob_get_clean();

		return json_decode($json);

	}
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_array_search')) {
	function cenote_array_search($array, $key, $value) {

		$results = array();

		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value) {
				$results[] = $array;
			}

			foreach ($array as $sub_array) {
				$results = array_merge($results, cenote_array_search($sub_array, $key, $value));
			}

		}

		return $results;

	}
}

/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_get_var')) {
	function cenote_get_var($var, $default = '') {

		if (isset($_POST[$var])) {
			return $_POST[$var];
		}

		if (isset($_GET[$var])) {
			return $_GET[$var];
		}

		return $default;

	}
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_get_vars')) {
	function cenote_get_vars($var, $depth, $default = '') {

		if (isset($_POST[$var][$depth])) {
			return $_POST[$var][$depth];
		}

		if (isset($_GET[$var][$depth])) {
			return $_GET[$var][$depth];
		}

		return $default;

	}
}

/**
 *
 * Load options fields
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_load_option_fields')) {
	function cenote_load_option_fields() {

		$located_fields = array();

		foreach (glob(CENOTE_DIR . '/fields/*/*.php') as $cenote_field) {
			$located_fields[] = basename($cenote_field);
			cenote_locate_template(str_replace(CENOTE_DIR, '', $cenote_field));
		}

		$override_name = apply_filters('cenote_framework_override', 'cenote-framework-override');
		$override_dir = get_template_directory() . '/' . $override_name . '/fields';

		if (is_dir($override_dir)) {

			foreach (glob($override_dir . '/*/*.php') as $override_field) {

				if (!in_array(basename($override_field), $located_fields)) {

					cenote_locate_template(str_replace($override_dir, '/fields', $override_field));

				}

			}

		}

		do_action('cenote_load_option_fields');

	}
}

/**
 * Get the Kit details usign wp_remote_get.
 *
 * @param string $kit_id Typekit ID.
 * @since 1.0.0
 *
 */
if (!function_exists('cenote_get_custom_typekit_details')) {
	function cenote_get_custom_typekit_details($kit_id) {
		$typekit_info	= array();
		$typekit_uri	= 'https://typekit.com/api/v1/json/kits/' . $kit_id . '/published';

		$response = wp_remote_get(
			$typekit_uri,
			array(
				'timeout' => '30',
			)
		);

		if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
			$_POST['custom-typekit-id-notice'] = true;
			return $typekit_info;
		}

		$data		= json_decode(wp_remote_retrieve_body($response), true);
		$families	= $data['kit']['families'];

		foreach ($families as $family) {
			$family_name = str_replace(' ', '-', $family['name']);
			$typekit_info[$family_name]	= array(
				'family'	=> $family_name,
				'fallback'	=> str_replace('"', '', $family['css_stack']),
				'weights'	=> array(),
			);

			foreach ($family['variations'] as $variation) {
				$variations = str_split($variation);

				switch ($variations[0]) {
					case 'n':
						$style = 'normal';
						break;
					default:
						$style = 'normal';
						break;
				}

				$weight = $variations[1] . '00';

				if (!in_array($weight, $typekit_info[$family_name]['weights'])) {
					$typekit_info[$family_name]['weights'][] = $weight;
				}
			}

			$typekit_info[$family_name]['slug']			= $family['slug'];
			$typekit_info[$family_name]['css_names']	= $family['css_names'];
		}

		return $typekit_info;
	}
}
