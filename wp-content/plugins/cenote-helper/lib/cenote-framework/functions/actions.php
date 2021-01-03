<?php

if (!defined('ABSPATH')) {
	return;
}
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if (!function_exists('cenote_get_icons')) {
	function cenote_get_icons() {
		do_action('cenote_add_icons_before');

		$jsons = apply_filters('cenote_add_icons_json', glob(CENOTE_DIR . '/fields/icon/*.json'));

		if (!empty($jsons)) {

			foreach ($jsons as $path) {
				if (strpos($path, CENOTE_DIR . '/fields/icon') !== false) {
					$link_defalut	= true;
					$file_load		= 'fields/icon/' . basename($path);
				} else {
					$link_defalut	= false;
					$file_load		= $path;
				}

				$object = cenote_get_icon_fonts($file_load, $link_defalut);

				if (is_object($object)) {

					echo (count($jsons) >= 2) ? '<h4 class="cenote-icon-title">' . $object->name . '</h4>' : '';

					foreach ($object->icons as $icon) {
						echo '<a class="cenote-icon-tooltip" data-cenote-icon="' . $icon . '" data-title="' . $icon . '"><span class="cenote-icon cenote-selector"><i class="' . $icon . '"></i></span></a>';
					}

				} else {
					echo '<h4 class="cenote-icon-title">' . __('Error! Can not load json file.', 'cenote-helper') . '</h4>';
				}

			}

		}

		do_action('cenote_add_icons');
		do_action('cenote_add_icons_after');

		die();
	}

	add_action('wp_ajax_cenote-get-icons', 'cenote_get_icons');
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_export_options')) {
	function cenote_export_options() {

		header('Content-Type: plain/text');
		header('Content-disposition: attachment; filename=backup-options-' . gmdate('d-m-Y') . '.txt');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo cenote_encode_string(get_option(CENOTE_OPTION));

		die();
	}

	add_action('wp_ajax_cenote-export-options', 'cenote_export_options');
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cenote_set_icons')) {
	function cenote_set_icons() {

		echo '<div id="cenote-icon-dialog" class="cenote-dialog" title="' . __('Add Icon', 'cenote-helper') . '">';
		echo '<div class="cenote-dialog-header cenote-text-center"><input type="text" placeholder="' . __('Search a Icon...', 'cenote-helper') . '" class="cenote-icon-search" /></div>';
		echo '<div class="cenote-dialog-load"><div class="cenote-icon-loading">' . __('Loading...', 'cenote-helper') . '</div></div>';
		echo '</div>';

	}

	add_action('admin_footer', 'cenote_set_icons');
	add_action('customize_controls_print_footer_scripts', 'cenote_set_icons');
}

/**
 * Custom Sidebars
 *
 * @since 1.2.0
 * @version 1.0.0
 */
if (! function_exists('cenote_custom_sidebar_add_form')) {
    function cenote_custom_sidebar_add_form() {
        if (CENOTE_ACTIVE_CUSTOM_SIDEBAR) {
            global $wp_version;
            ?>

            <script type="text/html" id="tmpl-cenote-add-widget">
                <div class="cenote-widgets-holder-wrap">
                    <?php if (version_compare($wp_version, '3.7.9', '>') == false) : ?>
                        <div class="sidebar-name">
                            <h3><?php esc_html_e('Custom Widget Area', 'cenote-helper'); ?></h3>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="cenote-add-widget">
                        <?php if (version_compare($wp_version, '3.7.9', '>') == true) : ?>
                            <div class="sidebar-name">
                                <h3><?php esc_html_e('Custom Widget Area', 'cenote-helper'); ?></h3>
                            </div>
                        <?php endif; ?>

                        <input type="text" name="cenote-add-widget" value="" placeholder="<?php esc_html_e('Enter name of the new widget area here', 'cenote-helper'); ?>" required="required" />
                        <?php submit_button(esc_html__('Add Widget Area', 'cenote-helper'), 'secondary large', 'cenote-custom-sidebar-submit'); ?>
                        <input type="hidden" name="cenote-delete-nonce" value="<?php echo wp_create_nonce('cenote-delete-nonce'); ?>" />
                    </form>
                </div>
            </script>

            <?php
        }
    }

    add_action('admin_footer' , 'cenote_custom_sidebar_add_form');
}

if (! function_exists('cenote_get_sidebar_name')) {
    function cenote_get_sidebar_name($name) {
        if (empty($GLOBALS['wp_registered_sidebars'])) {
            return $name;
        }

        $sidebars   = get_option('cenote_custom_sidebars');
        $taken      = array();

        foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
            $taken[]    = $sidebar['name'];
        }

        if (empty($sidebars)) {
            $sidebars   = array();
        }

        $taken  = array_merge($taken, $sidebars);

        if (in_array($name, $taken)) {
            $counter    = substr($name, -1);

            if (! is_numeric($counter)) {
                $new_name   = $name . ' 1';
            } else {
                $new_name   = substr($name, 0, -1) . ((int) $counter + 1);
            }

            $name = cenote_get_sidebar_name($new_name);
        }

        return $name;
    }
}

if (! function_exists('cenote_custom_sidebar_action_load_widgets')) {
    function cenote_custom_sidebar_action_load_widgets() {
        if (CENOTE_ACTIVE_CUSTOM_SIDEBAR) {
            if (! empty($_POST['cenote-add-widget'])) {
                $sidebars   = get_option('cenote_custom_sidebars');
                $name       = cenote_get_sidebar_name($_POST['cenote-add-widget']);

                $sidebars[ sanitize_title_with_dashes($name) ]    = $name;

                update_option('cenote_custom_sidebars', $sidebars);
                wp_redirect(admin_url('widgets.php'));
                exit;
            }
        }
    }

    add_action('load-widgets.php', 'cenote_custom_sidebar_action_load_widgets', 1000);
}

if (! function_exists('cenote_custom_sidebar_delete_sidebar')) {
    function cenote_custom_sidebar_delete_sidebar() {
        if (CENOTE_ACTIVE_CUSTOM_SIDEBAR) {
            check_ajax_referer('cenote-delete-nonce');

            if (! empty($_POST['name'])) {
                $name       = sanitize_title_with_dashes(stripslashes($_POST['name']));
                $sidebars   = get_option('cenote_custom_sidebars');

                if (array_key_exists($name, $sidebars)) {
                    unset($sidebars[ $name ]);
                    update_option('cenote_custom_sidebars', $sidebars);
                    unregister_sidebar($name);

                    echo 'sidebar-deleted';
                }
            }

            exit();
        }
    }

    add_action('wp_ajax_cenote_ajax_delete_custom_sidebar', 'cenote_custom_sidebar_delete_sidebar', 1000);
};

/**
 * Demo Importer
 *
 * @since 1.2.0
 * @version 1.0.0
 */
if (!function_exists('cenote_demo_importer_localize_script')) {
	function cenote_demo_importer_localize_script() {
		wp_localize_script('cenote-helper', 'adiL10n', array(
			'install_demo_confirm'		=> __(
				"Install demo content:\n"
				. "-----------------------------------------\n"
				. "Are you sure? This will install demo content\n\n"
				. "This may add demo posts, images, slideshows and settings into your website.\n\n"
				. "You can remove them later by clicking uninstall demo content.\n\n"
				. "Please backup your settings to be sure that you don't lose them by accident.\n\n\n"
			),
			'uninstall_demo_confirm'	=> __(
				"Uninstall demo content:\n"
				. "-----------------------------------------\n"
				. "Are you sure? This will remove demo posts, images, slideshows and settings from your website.\n\n\n"
			),
			'install_demo_error'		=> __('Error installing demo content!'),
			'uninstall_demo_error'		=> __('Error uninstalling demo content!'),
		));
	}

	add_action('admin_enqueue_scripts', 'cenote_demo_importer_localize_script', 99);
}

/**
 * Handle ajax demo importer.
 */
if (!function_exists('cenote_demo_importer_action')) {
	function cenote_demo_importer_action() {
		if (!isset($_POST['demo_id']) || !isset($_POST['cenote_demo_importer_action']) || !current_user_can('switch_themes')) {
			die;
		}

		set_time_limit(0);

		cenote_locate_template('classes/demo-importer/base.php');
		cenote_locate_template('classes/demo-importer/map.php');
		cenote_locate_template('classes/demo-importer/settings.php');
		cenote_locate_template('classes/demo-importer/post.php');
		cenote_locate_template('classes/demo-importer/term.php');
		cenote_locate_template('classes/demo-importer/user.php');
		cenote_locate_template('classes/demo-importer/state.php');

		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		$action		= $_POST['cenote_demo_importer_action'];
		$demo_id	= $_POST['demo_id'];
		$pni		= isset($_POST['pni']) ? $_POST['pni'] : 0;

		if ($action == 'uninstall') {
			$cenote_demo_import_seting = new Cenote_Demo_Importer_Settings();
			$cenote_demo_import_seting->restore();

			$cenote_demo_import_post	= new Cenote_Demo_Importer_Post();
			$cenote_demo_import_post->remove();

			$cenote_demo_import_term	= new Cenote_Demo_Importer_Term();
			$cenote_demo_import_term->remove();

			$cenote_demo_import_user	= new Cenote_Demo_Importer_User();
			$cenote_demo_import_user->remove();

			do_action('cenote_demo_importer_finish_uninstall');

			$x = Cenote_Demo_Importer_Map::instance();
			$x->remove();
			Cenote_Demo_Importer_State::update_state('');
		} else {
			// load data
			$data	= cenote_demo_importer_load_data_file($demo_id);

			if (!$data) {
				echo 0;
				die;
			}

			switch ($action) {
				case 'install':
					Cenote_Demo_Importer_State::update_state($demo_id);

					$settings	= new Cenote_Demo_Importer_Settings($data);
					$settings->save();
					$settings->add();

					$response	= array(
						'next_action'	=> 'term',
						'progress'		=> 10
					);

					break;
				case 'term':
					$term	= new Cenote_Demo_Importer_Term($data);
					$term->add();

					$user	= new Cenote_Demo_Importer_User($data);
					$user->add();

					$response	= array(
						'next_action'	=> 'post',
						'progress'		=> 25
					);

					break;
				case 'post':
					$time_out	= 5;	// 5s
					$start_time	= time();
					$index		= $pni;
					$post		= new Cenote_Demo_Importer_Post($data);

					do {
						$index		= $post->add_by_index($index);
						$end_time	= time();
					} while ($index && $end_time - $start_time < $time_out);

					if ($index) {
						$response	= array(
							'next_action'	=> 'post',
							'pni'			=> $index,
							'progress'		=> 25 + intval(40 * $index / count($data->posts)),
						);
					} else {
						$response	= array(
							'next_action'	=> 'remap',
							'progress'		=> 65
						);
					}

					break;
				case 'remap':
					$x = new Cenote_Demo_Importer_Settings($data);
					$x->remap();

					$y = new Cenote_Demo_Importer_Term($data);
					$y->remap();

					$z = new Cenote_Demo_Importer_Post($data);
					$z->remap();

					$t = new Cenote_Demo_Importer_User($data);
					$t->remap();

					$response	= array(
						'next_action'	=> 'finish',
						'progress'		=> 90,
					);

					break;
				case 'finish':
					do_action('cenote_demo_importer_finish_install', $demo_id, $data);
					echo 1;
					die;
			}

			if (isset($response)) {
				echo json_encode($response);
				die;
			} else {
				echo 0;
				die;
			}
		}
	}

	add_action('wp_ajax_cenote_demo_importer_action', 'cenote_demo_importer_action');
	add_action('wp_ajax_nopriv_cenote_demo_importer_action', 'cenote_demo_importer_action');
}

/**
 * Load demo data file.
 */
if (!function_exists('cenote_demo_importer_load_data_file')) {
	function cenote_demo_importer_load_data_file($id) {
		$file	= apply_filters('cenote_demo_importer_get_data_file', '', $id);

		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		if ($file) {
			return json_decode($wp_filesystem->get_contents($file));
		}

		return null;
	}
}
