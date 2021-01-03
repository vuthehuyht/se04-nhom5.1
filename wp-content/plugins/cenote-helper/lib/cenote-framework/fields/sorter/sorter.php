<?php if (! defined('ABSPATH')) { die; } // Cannot access pages directly.
/**
 *
 * Field: Sorter
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_Sorter extends CENOTEFramework_Options {

  public function __construct($field, $value = '', $unique = '') {
    parent::__construct($field, $value, $unique);
  }

  public function output(){

    echo $this->element_before();

    $value          = $this->element_value();
    $value          = (! empty($value)) ? $value : $this->field['default'];
    $enabled        = (! empty($value['enabled'])) ? $value['enabled'] : array();
    $disabled       = (! empty($value['disabled'])) ? $value['disabled'] : array();
    $enabled_title  = (isset($this->field['enabled_title'])) ? $this->field['enabled_title'] : __('Enabled Modules', 'cenote-helper');
    $disabled_title = (isset($this->field['disabled_title'])) ? $this->field['disabled_title'] : __('Disabled Modules', 'cenote-helper');

    echo '<div class="cenote-modules">';
    echo '<h3>'. $enabled_title .'</h3>';
    echo '<ul class="cenote-enabled">';
    if(! empty($enabled)) {
      foreach($enabled as $en_id => $en_name) {
        echo '<li><input type="hidden" name="'. $this->element_name('[enabled]['. $en_id .']') .'" value="'. $en_name .'"/><label>'. $en_name .'</label></li>';
      }
    }
    echo '</ul>';
    echo '</div>';

    echo '<div class="cenote-modules">';
    echo '<h3>'. $disabled_title .'</h3>';
    echo '<ul class="cenote-disabled">';
    if(! empty($disabled)) {
      foreach($disabled as $dis_id => $dis_name) {
        echo '<li><input type="hidden" name="'. $this->element_name('[disabled]['. $dis_id .']') .'" value="'. $dis_name .'"/><label>'. $dis_name .'</label></li>';
      }
    }
    echo '</ul>';
    echo '</div>';
    echo '<div class="clear"></div>';

    echo $this->element_after();

  }

}
