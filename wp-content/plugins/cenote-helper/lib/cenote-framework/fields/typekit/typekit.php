<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: TypeKit
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_Typekit extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		echo $this->element_before();

		$value 			= wp_parse_args($this->element_value());

		$kitid			= $this->field['kitid'];
		$family_value 	= isset($value['family']) ? $value['family'] : '';
		$variant_value 	= isset($value['variant']) ? $value['variant'] : '';

		$is_variant 	= (isset($this->field['variant']) && $this->field['variant'] === false) ? false : true;
		$is_chosen 		= (isset($this->field['chosen']) && $this->field['chosen'] === false) ? '' : 'chosen ';

		$typekit_list 	= cenote_get_custom_typekit_details($kitid);

		$chosen_rtl 	= (is_rtl() && !empty($is_chosen)) ? 'chosen-rtl ' : '';

		if (!empty($typekit_list)) {
			$typekit_fonts = array();

			foreach ($typekit_list as $key => $font) {
				$typekit_fonts[$font['fallback']] = $font['weights'];
			}

			echo '<label class="cenote-typography-family">';
			echo '<select name="' . $this->element_name('[family]') . '" class="' . $is_chosen . $chosen_rtl . ' cenote-typo-family" data-atts="family">';

			foreach ($typekit_list as $key => $value) {
				if ($family_value == $value['fallback']) {
					$selected = 'selected';
				} else {
					$selected = '';
				}

				echo '<option value="' . $value['fallback'] . '" data-variants="' . implode('|', $value['weights']) . '" ' . $selected . '>' . $value['family'] . '</option>';
			}

			echo '</select>';
			echo '</label>';

			if (!empty($is_variant)) {

				$variants = $family_value ? $typekit_fonts[$family_value] : reset($typekit_fonts);

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

			echo __('Error! Can not get api.', 'cenote-helper');

		}

		echo $this->element_after();

	}
}
