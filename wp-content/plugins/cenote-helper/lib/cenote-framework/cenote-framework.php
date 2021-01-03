<?php
if (!defined('ABSPATH')) {
	return;
}
/**
 *
 * Cenote Framework
 *
 */

// ------------------------------------------------------------------------------------------------
require_once plugin_dir_path(__FILE__) . '/cenote-framework-path.php';
// ------------------------------------------------------------------------------------------------

if (!function_exists('cenote_framework_init') && !class_exists('CENOTEFramework')) {
	function cenote_framework_init() {
		// active modules
		defined('CENOTE_ACTIVE_FRAMEWORK') or define('CENOTE_ACTIVE_FRAMEWORK', true);
		defined('CENOTE_ACTIVE_SHORTCODE') or define('CENOTE_ACTIVE_SHORTCODE', true);
		defined('CENOTE_ACTIVE_LIGHT_THEME') or define('CENOTE_ACTIVE_LIGHT_THEME', false);

		// helpers
		cenote_locate_template('functions/fallback.php');
		cenote_locate_template('functions/helpers.php');
		cenote_locate_template('functions/actions.php');
		cenote_locate_template('functions/enqueue.php');
		cenote_locate_template('functions/sanitize.php');
		cenote_locate_template('functions/validate.php');

		// classes
		cenote_locate_template('classes/abstract.class.php');
		cenote_locate_template('classes/options.class.php');
		cenote_locate_template('classes/framework.class.php');
		cenote_locate_template('classes/metabox.class.php');
		cenote_locate_template('classes/taxonomy.class.php');
		cenote_locate_template('classes/shortcode.class.php');
		cenote_locate_template('classes/customize.class.php');

		if (class_exists('Vc_Manager')) {
			cenote_locate_template('plugins/js-composer/includes/init.php');
		}
	}

	add_action('init', 'cenote_framework_init', 10);

    // Load widget library
    function cenote_framework_widgets_init() {
        defined('CENOTE_ACTIVE_CUSTOM_SIDEBAR') or define('CENOTE_ACTIVE_CUSTOM_SIDEBAR', true);

        cenote_locate_template('classes/widget.class.php');

        // register custom sidebars
        if (CENOTE_ACTIVE_CUSTOM_SIDEBAR) {
            $sidebars   = get_option('cenote_custom_sidebars');

            $args       = apply_filters(
                'cenote_custom_sidebars_widget_args', array(
                    'description'   => esc_html__('Drag widgets for all of pages sidebar', 'cenote-helper'),
                    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</aside>',
                    'before_title'  => '<h5 class="widget-title"><span>',
                    'after_title'   => '</span></h5>',
               )
           );

            if (is_array($sidebars)) {
                foreach ($sidebars as $sidebar) {
                    $args['name']   = $sidebar;
                    $sidebar        = sanitize_title_with_dashes($sidebar);
                    $args['id']     = $sidebar;
                    $args['class']  = 'cenote-custom-widget';

                    register_sidebar(apply_filters('cenote_custom_sidebars_widget_args_' . $sidebar, $args));
                }
            }
        }
    }

    add_action('widgets_init', 'cenote_framework_widgets_init');
}
