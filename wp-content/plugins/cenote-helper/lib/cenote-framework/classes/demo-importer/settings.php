<?php
defined('ABSPATH') or die;

class Cenote_Demo_Importer_Settings extends Cenote_Demo_Importer_Base {
	protected $history			= array();
	public $option_name			= '';
	public $theme_options_name	= '';

	public function __construct($data = null) {
		parent::__construct($data);

		$theme						= apply_filters('cenote_demo_importer_get_theme_name', get_template());
		$this->option_name			= $theme . '_demo_history';
		$this->theme_options_name	= apply_filters('cenote_demo_importer_get_theme_options_name', CENOTE_CUSTOMIZE);
		$this->history				= get_option($this->option_name);
	}

	/**
	 * Get the widgets names used on each sidebar.
	 * @param	sidebar_widgets_option
	 * @return	array
	 */
	protected function get_used_widgets($sidebar_widgets) {
		$used_widgets	= array();

		if (is_array($sidebar_widgets)) {
			foreach ($sidebar_widgets as $sidebar => $widgets) {
				if (is_array($widgets)) {
					foreach ($widgets as $widget) {
						$used_widgets[]	= $this->_get_widget_id_base($widget);
					}
				}
			}
		}

		return array_unique($used_widgets);
	}

	protected function _get_widget_id_base($id) {
		return preg_replace('/-[0-9]+$/', '', $id);
	}

	/**
	 * Save current settings.
	 */
	public function save() {
		if (isset($this->history['date'])) {
			return;
		}

		$sidebars_widgets	= get_option('sidebars_widgets');
		$current_settings	= array(
			'date'					=> time(),
			'page_on_front'			=> get_option('page_on_front'),
			'show_on_front'			=> get_option('show_on_front'),
			'nav_menu_locations'	=> get_theme_mod('nav_menu_locations'),
			'sidebars_widgets'		=> $sidebars_widgets,
			'theme_options'			=> get_option($this->theme_options_name),
			'cenote_custom_sidebars'	=> get_option('cenote_custom_sidebar'),
		);

		$used_widgets	= $this->get_used_widgets($sidebars_widgets);

		if (is_array($used_widgets)) {
			foreach ($used_widgets as $widget) {
				$current_settings['used_widgets'][$widget]	= get_option('widget_' . $widget);
			}
		}

		$current_settings	= apply_filters('cenote_demo_importer_save_current_settings', $current_settings);

		update_option($this->option_name, $current_settings);
	}

	/**
	 * Restore settings.
	 */
	public function restore() {
		update_option($this->theme_options_name, $this->history['theme_options']);
		update_option('page_on_front', $this->history['page_on_front']);
    	update_option('show_on_front', $this->history['show_on_front']);
    	update_option('sidebars_widgets', $this->history['sidebars_widgets']);
    	set_theme_mod('nav_menu_locations', $this->history['nav_menu_locations']);
    	update_option('cenote_custom_sidebars', $this->history['cenote_custom_sidebars']);

    	if (isset($this->history['used_widgets']) && is_array($this->history['used_widgets'])) {
    		foreach ($this->history['used_widgets'] as $widget => $widget_options) {
    			update_option('widget_' . $widget, $widget_options);
    		}
    	}

    	do_action('cenote_demo_importer_restore_settings', $this->history);

    	delete_option($this->option_name);
	}

	/**
	 * Add new settings.
	 */
	public function add() {
		// add wp options
		if (isset(self::$data->show_on_front)) {
			update_option('show_on_front', self::$data->show_on_front);
		}

		// update sidebars
		if (isset(self::$data->custom_sidebars) && count(self::$data->custom_sidebars)) {
			$custom_sidebars	= get_option('cenote_custom_sidebars');

			foreach (self::$data->custom_sidebars as $key => $name) {
				$custom_sidebars[$key]	= $name;
			}

			update_option('cenote_custom_sidebars', $custom_sidebars);
		}

		// update widgets
		if (isset(self::$data->sidebars) && count((self::$data->sidebars))) {
			$sidebars_widgets	= get_option('sidebars_widgets');

			foreach (self::$data->sidebars as $sidebar => $widgets) {
				foreach ($widgets as $widget) {
					if (!isset($sidebars_widgets[$sidebar])) {
						$sidebars_widgets[$sidebar]	= array();
					}

					$widget_options	= get_option('widget_' . $widget->name, array());
					$new_id			= count($widget_options) ? max(array_keys($widget_options)) + 1 : 1;
					$widget_new_id	= $widget->name . '-' . $new_id;

					$widget_options[$new_id]		= (array) $widget->options;
					$sidebars_widgets[$sidebar][]	= $widget_new_id;

					$widget_options	= apply_filters('cenote_demo_importer_add_widget', $widget_options, $widget, $sidebar, $sidebars_widgets);

					update_option('widget_' . $widget->name, $widget_options);

					Cenote_Demo_Importer_Map::instance()->save('widgets', $widget->name . '-' . $widget->id, $new_id, null);
				}
			}

			$sidebars_widgets	= apply_filters('cenote_demo_importer_add_sidebars_widgets', $sidebars_widgets);

			update_option('sidebars_widgets', $sidebars_widgets);
		}

		do_action('cenote_demo_importer_add_settings', self::$data);
	}

	/**
	 * Remap settings.
	 */
	public function remap() {
		$map		= Cenote_Demo_Importer_Map::instance();
		$term_ids	= $map->get('terms');
		$post_ids	= $map->get('posts');
		$widget_ids	= $map->get('widgets');

		// frontpage
		if (isset(self::$data->show_on_front) && self::$data->show_on_front == 'page') {
			if (isset(self::$data->page_on_front) && isset($post_ids[self::$data->page_on_front])) {
				update_option('page_on_front', $post_ids[self::$data->page_on_front][0]);
			}
		}

		// add theme options
		if (isset(self::$data->theme_options)) {
			$theme_options	= apply_filters('cenote_demo_importer_remap_theme_options', cenote_decode_string(self::$data->theme_options), self::$data->site_url);

			update_option($this->theme_options_name, $theme_options);
		}

		// menu locations
		if (isset(self::$data->nav_menu_locations) && count(self::$data->nav_menu_locations)) {
			$locations	= array();

			foreach (self::$data->nav_menu_locations as $location => $menu) {
				if (isset($term_ids[$menu])) {
					$locations[$location]	= $term_ids[$menu][0];
				}
			}

			$locations	= apply_filters('cenote_demo_importer_remap_nav_menu_locations', $locations);

			set_theme_mod('nav_menu_locations', $locations);
		}

		// widgets
		if (isset(self::$data->sidebars) && count((self::$data->sidebars))) {
			foreach (self::$data->sidebars as $sidebar => $widgets) {
				foreach ($widgets as $widget) {
					$widget_options	= get_option('widget_' . $widget->name, array());

					if (isset($widget_ids[$widget->name . '-' . $widget->id]) && isset($widget_options[$widget_ids[$widget->name . '-' . $widget->id]])) {
						if ($widget->name == 'nav_menu') {
							if (isset($widget->options->nav_menu) && isset($term_ids[$widget->options->nav_menu])) {
								$widget_options[$widget_ids[$widget->name . '-' . $widget->id]]['nav_menu']	= $term_ids[$widget->options->nav_menu][0];
							}
						}

						$widget_options	= apply_filters('cenote_demo_importer_remap_widget', $widget_options, $widget);

						update_option('widget_' . $widget->name, $widget_options);
					}
				}
			}
		}

		do_action('cenote_demo_importer_remap_settings', self::$data);
	}
}
