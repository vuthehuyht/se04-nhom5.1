<?php if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: Select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_slider extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		$args = wp_parse_args($this->field, array(
			'max'  => 100,
			'min'  => 0,
			'step' => 1,
			'unit' => '',
		));

		echo $this->element_before();

		echo '<div class="cenote-slider">';
		echo '<div class="cenote-nowrap">';
		echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_attributes() .' data-max="'. $args['max'] .'" data-min="'. $args['min'] .'" data-step="'. $args['step'] .'" class="cenote-number" />';
		echo (!empty($args['unit'])) ? '<em>'. $args['unit'] .'</em>' : '';
		echo '</div>';
		echo '<div class="cenote-slider-ui"></div>';
		echo '<a href="javascript:void(0)" class="reset-to-default" data-default="'. $this->element_value() .'"><span class="fa fa-refresh"></span></a>';
		echo '</div>';

		echo $this->element_after();

	}

}
