<?php
defined('ABSPATH') or die;

if (! function_exists('cenote_vc_enqueue_scripts')) {
	function cenote_vc_enqueue_scripts() {
		wp_enqueue_style('cenote-vc-style', CENOTE_URI . '/plugins/js-composer/assets/vc-style.css', array(), '1.0.0', 'all');
		wp_enqueue_script('cenote-vc-script', CENOTE_URI . '/plugins/js-composer/assets/vc-script.js', array('jquery'), '1.0.0', true);
	}

	add_action('admin_print_scripts-post.php', 'cenote_vc_enqueue_scripts', 99);
	add_action('admin_print_scripts-post-new.php', 'cenote_vc_enqueue_scripts', 99);
}

if (! function_exists('cenote_vc_deregister_style')) {
	function cenote_vc_deregister_style() {
		wp_deregister_style('font-awesome');
	}

	add_action('wp_head', 'cenote_vc_deregister_style', 1001);
}

if (! function_exists('cenote_vc_js_plugins')) {
	function cenote_vc_js_plugins() {
		echo '<script type="text/javascript">(function($) { $(document).ready(function() { $.CENOTE_VC_RELOAD_PLUGINS(); }); })(jQuery);</script>';
	}

	add_action('vc_load_default_params', 'cenote_vc_js_plugins');
}

if (! function_exists('cenote_element_values')) {
	function cenote_element_values($type = '', $query_args = array()) {
		$options    = array();

		switch ($type) {
			case 'page':
			case 'pages':
				$pages  = get_pages($query_args);

				if (! empty($pages)) {
					foreach ($pages as $page) {
						$options[ $page->post_title ] = $page->ID;
					}
				}

				break;
			case 'post':
			case 'posts':
				$posts  = get_posts($query_args);

				if (! empty($posts)) {
					foreach ($posts as $post) {
						$options[ $post->post_title ] = $post->ID;
					}
				}

				break;

			case 'tag':
			case 'tags':
				$tags   = get_terms($query_args['taxonomies'], $query_args['args']);

				if (! empty($tags)) {
					foreach ($tags as $tag) {
						$options[ $tag->name ]    = $tag->term_id;
					}
				}

				break;
			case 'category':
			case 'categories':
				$categories = get_categories($query_args);

				if (! empty($categories)) {
					foreach ($categories as $category) {
						$options[ $category->name ]   = $category->term_id;
					}
				}

				break;
			case 'custom':
			case 'callback':
				if (is_callable($query_args['function'])) {
					$options    = call_user_func($query_args['function'], $query_args['args']);
				}

				break;
		}

		return $options;
	}
}
