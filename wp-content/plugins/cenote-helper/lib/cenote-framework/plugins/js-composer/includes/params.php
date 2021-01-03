<?php
defined('ABSPATH') or die;


// Switcher
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_on_off')) {
	function vc_cenote_on_off($settings, $value) {
		$checked    = $value == 1 ? ' switch-active' : '';
		$label      = isset($settings['label']) ? '<span class="cenote-text-desc">' . $settings['label'] . '</span>' : '';

		$output = '<div class="cenote_field cenote_field_on_off">';
		$output .= '<div class="vc_switch switch' . $checked . '"><span class="switch-label" data-on="ON" data-off="OFF"></span><span class="switch-handle"></span>';
		$output .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value vc_cenote_on_off ' . $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '"/>';
		$output .= '</div>';
		$output .= $label;
		$output .= '</div>';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_on_off', 'vc_cenote_on_off');
}
// Textfield
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_textfield')) {
	function vc_cenote_textfield($settings, $value) {
		$placeholder    = isset($settings['placeholder']) ? ' placeholder="' . $settings['placeholder'] . '"' : '';

		return '<input type="text" name="' . $settings['param_name'] . '" class="wpb_vc_param_value vc_cenote_textfield ' . $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '"' . $placeholder . '/>';
	}

	vc_add_shortcode_param('vc_cenote_textfield', 'vc_cenote_textfield');
}

// Icon
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_icon')) {
	function vc_cenote_icon($settings, $value) {
		$hidden = empty($value) ? ' hidden' : '';
		$icon   = ! empty($value) ? ' class="' . $value . '"' : '';

		$output = '<div class="cenote_field cenote_field_icon">';
		$output .= '<div class="cenote-icon-select">';
		$output .= '<span class="cenote-icon-preview' . $hidden . '"><span' . $icon . '></span></span>';
		$output .= '<button class="button button-primary cenote-icon-add">Add Icon</button>';
		$output .= '<button class="button cenote-button-remove cenote-icon-remove' . $hidden . '">Remove Icon</button>';
		$output .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value vc_cenote_icon icon-value ' . $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '"/>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_icon', 'vc_cenote_icon');
}

// Image Select
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_image_select')) {
	function vc_cenote_image_select($settings, $value) {
		$output = '<ul class="vc_image_select">';

		if (isset($settings['options'])) {
			$options    = $settings['options'];

			foreach ($options as $key => $img) {
				$selected   = ($value == $key) ? ' class="selected"' : '';
				$output     .= '<li data-value="' . $key . '"' . $selected . '><img src="' . $img . '" alt="' . $key . '" /></li>';
			}
		}

		$output .= '</ul>';
		$output .= '<input type="hidden" class="wpb_vc_param_value vc_cenote_image_select ' . $settings['param_name'] . ' ' . $settings['type'] . '" name="' . $settings['param_name'] . '" value="' . $value . '" />';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_image_select', 'vc_cenote_image_select');
}

// Content
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_content')) {
	function vc_cenote_content($settings, $value) {
		return $settings['content'] . '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden">';
	}

	vc_add_shortcode_param('vc_cenote_content', 'vc_cenote_content');
}

// Upload
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_upload')) {
	function vc_cenote_upload($settings, $value) {
		if (isset($settings['settings'])) {
			extract($settings['settings']);
		}

		$return_as          = isset($settings['return_id']) ? 'id' : 'url';

		// set defaults
		$upload_type        = isset($upload_type) ? $upload_type : 'image';
		$button_title       = isset($button_title) ? $button_title : 'Upload';
		$frame_title        = isset($frame_title) ? $frame_title : 'Upload';
		$insert_title       = isset($insert_title) ? $insert_title : 'Use Image';
		$input_as           = isset($settings['preview']) ? 'hidden' : 'text';
		$remove_media_class = empty($value) ? ' hidden' : '';

		$output = '<div class="cenote_field cenote_field_upload">';
		$output .= '<div class="cenote-uploader">';

		// upload media source
		$output .= '<input type="' . $input_as . '" name="' . $settings['param_name'] . '" value="' . $value . '" class="media-attachment wpb_vc_param_value ' . $settings['param_name'] . ' ' . $settings['type'] . '_field"/>';

		if (isset($settings['preview'])) {
			$output .= '<div class="cenote-upload-preview">';

			// value check
			if (! empty($value)) {
				// if value is numeric, use wp_get_attachment_image for thumbnail.
				if (is_numeric($value)) {
					$output .= '<a href="' . wp_get_attachment_url($value) . '" target="_blank">' . wp_get_attachment_image($value, 'thumbnail') . '</a>';
				} else {
					$output .= '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="" /></a>';
				}
			}

			$output .= '</div>';

		}

		$output .= '<a href="#" class="button cenote-add-media" data-frame-title="' . $frame_title . '" data-upload-type="' . $upload_type . '" data-return="' . $return_as . '" data-insert-title="' . $insert_title . '">' . $button_title . '</a>';
		$output .= '&nbsp;';

		if (isset($settings['preview'])) {
			$output .= '<a href="#" class"button cenote-button-remove' . $remove_media_class . '"> Remove </a>';
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_upload', 'vc_cenote_upload');
}


// Exploded Textarea
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_exploded_textarea')) {
	function vc_cenote_exploded_textarea($settings, $value) {
		$value          = str_replace('~', "\n", $value);
		$placeholder    = isset($settings['placeholder']) ? ' placeholder="' . $settings['placeholder'] . '"' : '';

		return '<textarea name="' . $settings['param_name'] . '" rows="10" class="wpb_vc_param_value wpb-vc-cenote-exploded-textarea ' . $settings['param_name'] . ' ' . $settings['type'] . '"' . $placeholder . '>' . $value . '</textarea>';
	}

	vc_add_shortcode_param('vc_cenote_exploded_textarea', 'vc_cenote_exploded_textarea');
}

// Style Textarea
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_style_textarea')) {
	function vc_cenote_style_textarea($settings, $value) {
		$placeholder    = isset($settings['placeholder']) ? ' placeholder="' . $settings['placeholder'] . '"' : '';

		return '<textarea name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-vc-cenote-style-textarea ' . $settings['param_name'] . ' ' . $settings['type'] . '"' . $placeholder . '>' . $value . '</textarea>';
	}

	vc_add_shortcode_param('vc_cenote_style_textarea', 'vc_cenote_style_textarea');
}


// Shortcode Textarea
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_shortcode_textarea')) {
	function vc_cenote_shortcode_textarea($settings, $value) {
		$placeholder    = isset($settings['placeholder']) ? ' placeholder="' . $settings['placeholder'] . '"' : '';
		$output         = '<p><button class="button button-primary shortcode-button" data-target="shortcode-textarea"><span class="dashicons dashicons-menu"></span> Quick Shortcode</button></p>';
		$output         .= '<textarea id="shortcode-textarea" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-vc-cenote-shortcode-textarea ' . $settings['param_name'] . ' ' . $settings['type'] . '"' . $placeholder . '>' . $value . '</textarea>';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_shortcode_textarea', 'vc_cenote_shortcode_textarea');
}

// Chosen
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_chosen')) {
	function vc_cenote_chosen($settings, $value) {

		$css_option = vc_get_dropdown_option($settings, $value);

		$value      = explode(',', $value);
		$chosen_rtl = is_rtl() ? ' chosen-rtl' : '';

		// begin output
		$output     = '<select name="' . $settings['param_name'] . '" data-placeholder="' . $settings['heading'] . '" multiple="multiple" class="wpb_vc_param_value wpb_chosen chosen ' . $chosen_rtl . ' wpb-input wpb-cenote-select ' . $settings['param_name'] . ' ' . $settings['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';

		foreach ($settings['value'] as $text_val => $val) {
			$selected   = (in_array($val, $value)) ? ' selected="selected"' : '';
			$output     .= '<option value="' . $val . '"' . $selected . '>' . htmlspecialchars($text_val) . '</option>';
		}

		$output .= '<select>';
		// end output
		return $output;
	}

	vc_add_shortcode_param('vc_cenote_chosen', 'vc_cenote_chosen');
}

// Color Picker
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_color_picker')) {
	function vc_cenote_color_picker($settings, $value) {
		// begin output
		$output = '';
		$output .= '<div class="cenote_field_color_picker">';
		$output .= '<div class="cenote-color-wrap">';
		$output .= '<input type="text" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value wpb_cenote_color_picker cenote-color-picker ' . $settings['param_name'] . ' ' . $settings['type'] . '" data-rgba="true"/>';
		$output .= '</div>';
		$output .= '</div>';
		// end output
		return $output;
	}

	vc_add_shortcode_param('vc_cenote_color_picker', 'vc_cenote_color_picker');
}

// Date Picker
// ----------------------------------------------------------------------------------
if (!function_exists('vc_cenote_datetime_picker')) {
	function vc_cenote_datetime_picker($settings, $value) {
			$value = htmlspecialchars($value);

			return '<input name="' . esc_attr($settings['param_name']) . '" class="cenote-datepicker wpb_cenote_datetime_picker wpb_vc_param_value wpb-textinput ' .
			esc_attr($settings['param_name']) . ' ' .
			esc_attr($settings['type']) . '_field" type="text" value="' . esc_attr($value) . '" />';

	}

	vc_add_shortcode_param('vc_cenote_datetime_picker', 'vc_cenote_datetime_picker');
}

// Number
// ----------------------------------------------------------------------------------
if (! function_exists('vc_cenote_number')) {
	function vc_cenote_number($settings, $value) {
		$placeholder    = isset($settings['placeholder']) ? ' placeholder="' . $settings['placeholder'] . '"' : '';

		$output = '<div class="cenote-field-number"><label><span class="t ti-angle-up"></span>';
		$output .= '<input type="number" name="' . $settings['param_name'] . '" class="wpb_vc_param_value vc_cenote_textfield ' . $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '"' . $placeholder . '/>';
		$output .= '<span class="b ti-angle-down"></span></label></div>';

		return $output;
	}

	vc_add_shortcode_param('vc_cenote_number', 'vc_cenote_number');
}
