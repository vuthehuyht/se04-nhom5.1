<?php
if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * Metabox Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Metabox extends CENOTEFramework_Abstract {

	/**
	 *
	 * metabox options
	 * @access public
	 * @var array
	 *
	 */
	public $options = array();

	/**
	 *
	 * instance
	 * @access private
	 * @var class
	 *
	 */
	private static $instance = null;

	// run metabox construct
	public function __construct($options) {

		$this->options = apply_filters('cenote_metabox_options', $options);

		if (!empty($this->options)) {
			$this->addAction('add_meta_boxes', 'add_meta_box');
			$this->addAction('save_post', 'save_post', 10, 2);
		}

	}

	// instance
	public static function instance($options = array()) {
		if (is_null(self::$instance) && CENOTE_ACTIVE_METABOX) {
			self::$instance = new self($options);
		}
		return self::$instance;
	}

	// add metabox
	public function add_meta_box($post_type) {

		foreach ($this->options as $value) {
			add_meta_box($value['id'], $value['title'], array(&$this, 'render_meta_box_content'), $value['post_type'], $value['context'], $value['priority'], $value);
		}

	}

	// metabox render content
	public function render_meta_box_content($post, $callback) {

		global $post, $cenote_errors, $typenow;

		wp_nonce_field('cenote-framework-metabox', 'cenote-framework-metabox-nonce');

		$unique = $callback['args']['id'];
		$sections = $callback['args']['sections'];
		$meta_value = get_post_meta($post->ID, $unique, true);
		$transient = get_transient('cenote-metabox-transient');
		$cenote_errors = $transient['errors'];
		$has_nav = (count($sections) >= 2 && $callback['args']['context'] != 'side') ? true : false;
		$show_all = (!$has_nav) ? ' cenote-show-all' : '';
		$section_id = (!empty($transient['ids'][$unique])) ? $transient['ids'][$unique] : '';
		$section_id = cenote_get_var('cenote-section', $section_id);

		echo '<div class="cenote-framework cenote-metabox-framework">';

		echo '<input type="hidden" name="cenote_section_id[' . $unique . ']" class="cenote-reset" value="' . $section_id . '">';

		echo '<div class="cenote-body' . $show_all . '">';

		if ($has_nav) {

			echo '<div class="cenote-nav">';

			echo '<ul>';
			$num = 0;
			foreach ($sections as $value) {

				if (!empty($value['typenow']) && $value['typenow'] !== $typenow) {
					continue;
				}

				$tab_icon = (!empty($value['icon'])) ? '<i class="cenote-icon ' . $value['icon'] . '"></i>' : '';

				if (isset($value['fields'])) {
					$active_section = ((empty($section_id) && $num === 0) || $section_id == $value['name']) ? ' class="cenote-section-active"' : '';
					echo '<li><a href="#"' . $active_section . ' data-section="' . $value['name'] . '">' . $tab_icon . $value['title'] . '</a></li>';
				} else {
					echo '<li><div class="cenote-seperator">' . $tab_icon . $value['title'] . '</div></li>';
				}

				$num++;
			}
			echo '</ul>';

			echo '</div>';

		}

		echo '<div class="cenote-content">';

		echo '<div class="cenote-sections">';
		$num = 0;
		foreach ($sections as $v) {

			if (!empty($v['typenow']) && $v['typenow'] !== $typenow) {
				continue;
			}

			if (isset($v['fields'])) {

				$active_content = ((empty($section_id) && $num === 0) || $section_id == $v['name']) ? ' style="display: block;"' : '';

				echo '<div id="cenote-tab-' . $v['name'] . '" class="cenote-section"' . $active_content . '>';
				echo (isset($v['title'])) ? '<div class="cenote-section-title"><h3>' . $v['title'] . '</h3></div>' : '';

				foreach ($v['fields'] as $field_key => $field) {

					$default = (isset($field['default'])) ? $field['default'] : '';
					$elem_id = (isset($field['id'])) ? $field['id'] : '';
					$elem_value = (is_array($meta_value) && isset($meta_value[$elem_id])) ? $meta_value[$elem_id] : $default;
					echo cenote_add_element($field, $elem_value, $unique);

				}
				echo '</div>';

			}

			$num++;
		}
		echo '</div>';

		echo '<div class="clear"></div>';

		echo '</div>';

		echo ($has_nav) ? '<div class="cenote-nav-background"></div>' : '';

		echo '<div class="clear"></div>';

		echo '</div>';

		echo '</div>';

	}

	// save metabox options
	public function save_post($post_id, $post) {

		if (wp_verify_nonce(cenote_get_var('cenote-framework-metabox-nonce'), 'cenote-framework-metabox')) {

			$errors = array();
			$post_type = cenote_get_var('post_type');

			foreach ($this->options as $request_value) {

				if (in_array($post_type, (array)$request_value['post_type'])) {

					$request_key = $request_value['id'];
					$request = cenote_get_var($request_key, array());

					// ignore _nonce
					if (isset($request['_nonce'])) {
						unset($request['_nonce']);
					}

					foreach ($request_value['sections'] as $key => $section) {

						if (isset($section['fields'])) {

							foreach ($section['fields'] as $field) {

								if (isset($field['type']) && isset($field['id'])) {

									$field_value = cenote_get_vars($request_key, $field['id']);

									// sanitize options
									if (isset($field['sanitize']) && $field['sanitize'] !== false) {
										$sanitize_type = $field['sanitize'];
									} else if (!isset($field['sanitize'])) {
										$sanitize_type = $field['type'];
									}

									if (has_filter('cenote_sanitize_' . $sanitize_type)) {
										$request[$field['id']] = apply_filters('cenote_sanitize_' . $sanitize_type, $field_value, $field, $section['fields']);
									}

									// validate options
									if (isset($field['validate']) && has_filter('cenote_validate_' . $field['validate'])) {

										$validate = apply_filters('cenote_validate_' . $field['validate'], $field_value, $field, $section['fields']);

										if (!empty($validate)) {

											$meta_value = get_post_meta($post_id, $request_key, true);

											$errors[$field['id']] = array('code' => $field['id'], 'message' => $validate, 'type' => 'error');
											$default_value = isset($field['default']) ? $field['default'] : '';
											$request[$field['id']] = (isset($meta_value[$field['id']])) ? $meta_value[$field['id']] : $default_value;

										}

									}

								}

							}

						}

					}

					$request = apply_filters('cenote_save_post', $request, $request_key, $post);

					if (empty($request)) {

						delete_post_meta($post_id, $request_key);

					} else {

						update_post_meta($post_id, $request_key, $request);

					}

					$transient['ids'][$request_key] = cenote_get_vars('cenote_section_id', $request_key);
					$transient['errors'] = $errors;

				}

			}

			set_transient('cenote-metabox-transient', $transient, 10);

		}

	}

}
