<?php
/**
 *  Plugin Name: Cenote Helper
 *  Plugin URI:
 *  Description: Plugin required for theme Cenote.
 *  Version: 1.0.0
 *  Author: Mystic
 *  Author URI: Cenote
 *  Text Domain: cenote-helper
 *
 * @package Cenote Helper
 * @author cenotetheme
 *
 **/

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'Cenote_Helper' ) ) {
	class Cenote_Helper {
		public function __construct() {
			$theme_name = 'Cenote';
			$themes     = wp_get_theme();

			if ( $theme_name != $themes->name && $theme_name != $themes->parent_theme ) {
				return;
			}

			$this->define_constant();
			$this->load_library();
			$this->load_helper();

			add_action( 'wp_loaded', array( __CLASS__, 'shortcodes' ) );

			if ( ! is_admin() ) {
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 20 );
			}

			add_image_size( 'gallery_700_450', 700, 450, true );
		}

		//== [ Define Constant ]
		public function define_constant() {
			define( 'CENOTE_HELPER_DIR_PATH', plugin_dir_path( __FILE__ ) );
			define( 'CENOTE_HELPER_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		//== Load Helper ]
		public function load_helper() {
			require_once CENOTE_HELPER_DIR_PATH . '/helpers/helper.php';
		}

		//== [ Load Library ]
		public function load_library() {
			if ( ! class_exists( 'CENOTEFramework' ) && ! function_exists( 'cenote_framework_init' ) ) {
				require_once CENOTE_HELPER_DIR_PATH . '/lib/cenote-framework/cenote-framework.php';

				define( 'CENOTE_ACTIVE_SHORTCODE', false );
				define( 'CENOTE_ACTIVE_FRAMEWORK', true );
			}
		}

		//== [ Call Shortcodes ]
		public static function shortcodes() {
			$vc_active = class_exists( 'Vc_Manager' );
			$path      = CENOTE_HELPER_DIR_PATH . 'shortcodes/';

			$basic_shortcodes = array(
				'gallery-image'
			);

			foreach ( $basic_shortcodes as $shortcode ) {
				if ( is_admin() && $vc_active && file_exists( $path . $shortcode . '/config.vc.php' ) ) {
					require_once $path . $shortcode . '/config.vc.php';
				}

				if ( ! is_admin() && $vc_active && file_exists( $path . $shortcode . '/' . $shortcode . '.php' ) ) {
					require_once $path . $shortcode . '/' . $shortcode . '.php';
				}
			}
		}

		//== [ Enqueue Scripts ]
		public static function enqueue_scripts() {
			//== [ Style ]
			wp_enqueue_style( 'cenote-fancy-css', CENOTE_HELPER_DIR_URL . 'assets/css/jquery.fancybox.min.css', array(), '3.5.2' );

			//== [ Script ]
			wp_enqueue_script( 'cenote-fancy', CENOTE_HELPER_DIR_URL . 'assets/vendor/jquery.fancybox.min.js', array( 'jquery' ), '3.5.2', true );
		}
	}

	new Cenote_Helper();
}
