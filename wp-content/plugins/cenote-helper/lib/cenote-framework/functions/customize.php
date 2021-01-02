<?php
if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * WP Customize custom controls
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class WP_Customize_cenote_field_Control extends WP_Customize_Control {

	public $unique = '';
	public $type = 'cenote_field';
	public $options = array();

	public function render_content() {

		$this->options['id'] = $this->id;
		$this->options['default'] = $this->setting->default;
		$this->options['attributes']['data-customize-setting-link'] = $this->settings['default']->id;

		$complex 	= array('accordion', 'group', 'fieldset', 'background', 'typography_advance', 'typekit');
		$is_complex	= in_array($this->options['type'], $complex) ? true : false;
		$class		= $is_complex ? ' cenote-customize-complex' : '';

		echo '<div class="' . $class . '" data-unique-id="' . $this->unique . '" data-option-id="' . $this->id . '">';
		echo cenote_add_element($this->options, $this->value(), $this->unique);
		echo '</div>';

	}

}
