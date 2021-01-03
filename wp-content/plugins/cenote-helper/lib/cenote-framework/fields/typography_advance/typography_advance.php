<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: Typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_Typography_Advance extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		echo $this->element_before();

		$defaults_value = array(
			'family' => 'ABeeZee',
			'variant' => 'regular',
		);

		$default_variants = apply_filters('cenote_websafe_fonts_variants', array(
			'regular',
			'italic',
			'700',
			'700italic',
			'inherit'
		));

		$value 			= wp_parse_args($this->element_value(), $defaults_value);
		$family_value 	= $value['family'];
		$variant_value 	= $value['variant'];
		$is_variant 	= (isset($this->field['variant']) && $this->field['variant'] === false) ? false : true;
		$is_chosen 		= (isset($this->field['chosen']) && $this->field['chosen'] === false) ? '' : 'chosen ';
		$google_json 	= cenote_get_google_fonts();
		$chosen_rtl 	= (is_rtl() && !empty($is_chosen)) ? 'chosen-rtl ' : '';

		if (is_object($google_json)) {
			$googlefonts = array();

			foreach ($google_json->items as $key => $font) {
				$googlefonts[$font->family] = $font->variants;
			}

			echo '<label class="cenote-typography-family">';
			echo '<select name="' . $this->element_name('[family]') . '" class="' . $is_chosen . $chosen_rtl . ' cenote-typo-family" data-atts="family">';

			foreach ($googlefonts as $google_key => $google_value) {
				if ($family_value == $google_key) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				echo '<option value="' . $google_key . '" data-variants="' . implode('|', $google_value) . '" data-type="google" ' . $selected . '>' . $google_key . '</option>';
			}

			echo '</select>';
			echo '</label>';

			if (!empty($is_variant)) {

				$variants = $googlefonts[$family_value];

				echo '<label class="cenote-typography-variant">';
				echo '<h4>' . esc_html__('Font Weight', 'cenote-helper') . '</h4>';
				echo '<select name="' . $this->element_name('[variant]') . '" class="' . $is_chosen . $chosen_rtl . 'cenote-typo-variant" data-atts="variant">';
				foreach ($variants as $variant) {
					echo '<option value="' . $variant . '"' . $this->checked($variant_value, $variant, 'selected') . '>' . $variant . '</option>';
				}
				echo '</select>';
				echo '</label>';

			}
		} else {

			echo __('Error! Can not load json file.', 'cenote-helper');

		}

		echo $this->element_after();

	}
}
