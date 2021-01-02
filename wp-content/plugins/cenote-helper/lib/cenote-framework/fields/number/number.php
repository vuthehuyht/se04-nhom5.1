<?php if (! defined('ABSPATH')) { die; } // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_number extends CENOTEFramework_Options {

  public function __construct($field, $value = '', $unique = '') {
    parent::__construct($field, $value, $unique);
  }

  public function output() {

    echo $this->element_before();
    $unit = (isset($this->field['unit'])) ? '<em>'. $this->field['unit'] .'</em>' : '';
    echo '<label>';
    echo '<span class="t ti-angle-up"></span>';
    echo '<input type="number" name="'. $this->element_name() .'" value="'. $this->element_value().'"'. $this->element_class() . $this->element_attributes() .'/>'. $unit;
    echo '<span class="b ti-angle-down"></span>';
    echo '</label>';
    echo $this->element_after();

  }

}
