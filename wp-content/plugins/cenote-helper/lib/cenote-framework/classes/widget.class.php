<?php
defined('ABSPATH') or die;

/**
 *
 * Base Widget Class
 * A base class for widget.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
class CENOTEFramework_Widget extends WP_Widget {
	function get_options() {
		return array();
	}

	function update($new_instance, $old_instance) {
		$instance   = $old_instance;
		$options    = $this->get_options();

		foreach ($options as $option) {
			if (isset($new_instance[ $option['id'] ])) {
				$instance[ $option['id'] ]    = $new_instance[ $option['id'] ];
			} else if (in_array($option['type'], array('group', 'checkbox', 'switcher', 'select'))) {
				$instance[ $option['id'] ]    = '';
			}
		}

		return $instance;
	}

	function form($instance) {
		$options            = $this->get_options();

		// get all fields names.
		$default_options    = array();

		foreach ($options as $option) {
			$default_options[ $option['id'] ]     = isset($option['default']) ? $option['default'] : '';
		}

		$instance   = wp_parse_args((array) $instance, $default_options);

		// render fields.
		foreach ($options as $option) {
			$id             = $option['id'];
			$unique         = '';

			if (! in_array($option['type'], array('group'))) {
				$option['id']   = $this->get_field_id($id);
				$option['name'] = $this->get_field_name($id);
			} else {
				$unique         = 'widget-' . $this->id_base . '[' . $this->number . ']';
			}

			if (isset($option['dependency'][0])) {
				$dependencies   = explode('|', $option['dependency'][0]);
				$dependency_new = array();

				foreach ($dependencies as $dependency) {
					$dependency_new[]   = $this->get_field_id($dependency);
				}

				$option['dependency'][0]    = implode('|', $dependency_new);
			}

			if (isset($option['attributes']['data-depend-id'])) {
				$option['attributes']['data-depend-id'] = $this->get_field_id($option['attributes']['data-depend-id']);
			}

			echo cenote_add_element($option, is_array($instance[ $id ]) ? $instance[ $id ] : esc_attr($instance[ $id ]), $unique);
		}
	}
}
