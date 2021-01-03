<?php
/**
 * Created by Mystic.
 * User: mystic
 */

if (!defined('ABSPATH')) {
	return;
}

/**
 *
 * Field: DatePicker
 *
 * @since 1.0.0
 * @version 1.0.0
 */
class CENOTEFramework_Option_datepicker extends CENOTEFramework_Options {

	public function __construct($field, $value = '', $unique = '') {
		parent::__construct($field, $value, $unique);
	}

	public function output() {
		echo $this->element_before();

		?>
		<div class="cc-datepicker">
			<?php echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>'; ?>
		</div>
		<?php

		echo $this->element_after();
	}
}
