<?php if (! defined('ABSPATH')) { die; } // Cannot access pages directly.
/**
 *
 * Field: Color Picker
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_color_picker extends CENOTEFramework_Options {

  public function __construct($field, $value = '', $unique = '') {
    parent::__construct($field, $value, $unique);
  }

  public function output() {

    echo $this->element_before();
    echo '<div class="cenote-color-wrap">';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class('cenote-field-color-picker') . $this->element_attributes($this->extra_attributes()) .'/>';
    echo '<span class="color_hex">'. $this->element_value() .'</span>';
    echo '</div>';
    echo $this->element_after();

  }

  public function extra_attributes() {

    $atts = array();

    if(isset($this->field['id'])) {
      $atts['data-depend-id'] = $this->field['id'];
    }

    if (isset($this->field['rgba']) &&  $this->field['rgba'] === false) {
      $atts['data-rgba'] = 'false';
    }

    if(isset($this->field['default'])) {
      $atts['data-default-color'] = $this->field['default'];
    }

    return $atts;

  }

}
