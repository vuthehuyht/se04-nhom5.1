<?php
defined('ABSPATH') or die;

cenote_locate_template('plugins/js-composer/includes/helpers.php');
cenote_locate_template('plugins/js-composer/includes/params.php');
cenote_locate_template('plugins/js-composer/includes/extends.php');

$options    = apply_filters('cenote_framework_vc_map_options', array());

foreach ($options as $option) {
	if ($option) {
		vc_map($option);
	}
}
