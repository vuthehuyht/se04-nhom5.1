<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: Image Select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_select_image extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		$input_type = (!empty($this->field['checkbox'])) ? 'checkbox' : 'radio';
		$input_attr = (!empty($this->field['multi_select'])) ? '[]' : '';

		echo $this->element_before();
		echo (empty($input_attr)) ? '<div class="cenote-field-image-dropdown">' : '';

		if (isset($this->field['options'])) {
			$options 		= $this->field['options'];
			$url_checked	= '';

			foreach ($options as $key => $value) {
				if ($this->checked($this->element_value(), $key)) {
					$url_checked = $value;
				}
			}

			echo '<div class="show-option">';
			echo '<span><img src="' . $url_checked . '" /></span>';
			echo '</div>';

			echo '<div class="list-options"><ul>';

			foreach ($options as $key => $value) {
				echo '<li><label>';
				echo '<span><img src="' . $value . '" /></span>';
				echo '<input type="' . $input_type . '" name="' . $this->element_name($input_attr) . '" value="' . $key . '"' . $this->element_class() . $this->element_attributes($key) . $this->checked($this->element_value(), $key) . '/>';
				echo '</label></li>';
			}

			echo '</ul></div>';
		}

		echo (empty($input_attr)) ? '</div>' : '';
		echo $this->element_after();

	}

}
