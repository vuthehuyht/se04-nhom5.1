<?php
if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * Field: Background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_background extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {

		echo $this->element_before();

		$value_defaults = array(
			'image' => '',
			'repeat' => '',
			'position' => '',
			'attachment' => '',
			'size' => '',
			'color' => '',
		);

		$this->value = wp_parse_args($this->element_value(), $value_defaults);

		if (isset($this->field['settings'])) {
			extract($this->field['settings']);
		}

		$upload_type = (isset($upload_type)) ? $upload_type : 'image';
		$button_title = (isset($button_title)) ? $button_title : __('Upload', 'cenote-helper');
		$frame_title = (isset($frame_title)) ? $frame_title : __('Upload', 'cenote-helper');
		$insert_title = (isset($insert_title)) ? $insert_title : __('Use Image', 'cenote-helper');

		$preview = '';
		$hidden  = (empty($this->value['image'])) ? ' hidden' : '';
		if(! empty($this->value['image'])) {
		  $attachment = wp_get_attachment_image_src($this->value['image'], 'thumbnail');
		  $preview    = $attachment[0];
		}

		$image_value = ($this->value['image'] != '') ? $this->value['image'] : '';

		echo cenote_add_element(array(
			'type' => 'image',
			'name' => $this->element_name('[image]'),
			'value' => $image_value
		));

		// background attributes
		echo '<fieldset>';
		echo cenote_add_element(array(
			'pseudo' => true,
			'type' => 'select',
			'class'	=> 'chosen',
			'name' => $this->element_name('[repeat]'),
			'options' => array(
				'' => 'repeat',
				'repeat-x' => 'repeat-x',
				'repeat-y' => 'repeat-y',
				'no-repeat' => 'no-repeat',
				'inherit' => 'inherit',
			),
			'attributes' => array(
				'data-atts' => 'repeat',
			),
			'value' => $this->value['repeat']
		));
		echo cenote_add_element(array(
			'pseudo' => true,
			'type' => 'select',
			'class'	=> 'chosen',
			'name' => $this->element_name('[position]'),
			'options' => array(
				'' => 'left top',
				'left center' => 'left center',
				'left bottom' => 'left bottom',
				'right top' => 'right top',
				'right center' => 'right center',
				'right bottom' => 'right bottom',
				'center top' => 'center top',
				'center center' => 'center center',
				'center bottom' => 'center bottom'
			),
			'attributes' => array(
				'data-atts' => 'position',
			),
			'value' => $this->value['position']
		));
		echo cenote_add_element(array(
			'pseudo' => true,
			'type' => 'select',
			'class'	=> 'chosen',
			'name' => $this->element_name('[attachment]'),
			'options' => array(
				'' => 'scroll',
				'fixed' => 'fixed',
			),
			'attributes' => array(
				'data-atts' => 'attachment',
			),
			'value' => $this->value['attachment']
		));
		echo cenote_add_element(array(
			'pseudo' => true,
			'type' => 'select',
			'class'	=> 'chosen',
			'name' => $this->element_name('[size]'),
			'options' => array(
				'' => 'size',
				'cover' => 'cover',
				'contain' => 'contain',
				'inherit' => 'inherit',
				'initial' => 'initial',
			),
			'attributes' => array(
				'data-atts' => 'size',
			),
			'value' => $this->value['size']
		));
		echo cenote_add_element(array(
			'pseudo' => true,
			'id' => $this->field['id'] . '_color',
			'type' => 'color_picker',
			'name' => $this->element_name('[color]'),
			'attributes' => array(
				'data-atts' => 'bgcolor',
			),
			'value' => $this->value['color'],
			'default' => (isset($this->field['default']['color'])) ? $this->field['default']['color'] : '',
			'rgba' => (isset($this->field['rgba']) && $this->field['rgba'] === false) ? false : '',
		));
		echo '</fieldset>';

		echo $this->element_after();

	}
}
