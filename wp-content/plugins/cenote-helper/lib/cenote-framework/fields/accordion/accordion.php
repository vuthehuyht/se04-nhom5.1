<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_accordion extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		echo $this->element_before();

		$fields = array_values($this->field['fields']);
		$values = $this->field['values'];

		echo '<div class="cenote-field-group">';

		echo '<div class="cenote-groups cenote-accordion">';

		if (!empty($values)) {

			foreach ($values as $key => $value) {

				$title = isset($value['title']) ? $value['title'] : '';

				if (is_array($title) && isset($this->multilang)) {
					$lang = cenote_language_defaults();
					$title = $title[$lang['current']];
					$title = is_array($title) ? $title[0] : $title;
				}

				echo '<div class="cenote-group cenote-group-' . ($key + 1) . '">';
				echo '<h4 class="cenote-group-title">' . $title . '</h4>';
				echo '<div class="cenote-group-content">';

				foreach ($fields as $field) {

					$field['sub'] 	= true;
					$unique 		= $this->unique . '[' . $this->field['id'] . '][' . $key . ']';
					$default_value	= (isset($field['id']) && isset($value[$field['id']])) ? $value[$field['id']] : '';
					$x				= $default_value;

					if (!empty($this->element_value())) {
						foreach ($this->element_value() as $k => $v) {
							if ($k == $key) {
								$x = $v[$field['id']];
							}

						}
					}

					echo cenote_add_element($field, $x, $unique);
				}

				echo '</div>';
				echo '</div>';

			}

		}

		echo '</div>';
		echo '</div>';

		echo $this->element_after();

	}

}
