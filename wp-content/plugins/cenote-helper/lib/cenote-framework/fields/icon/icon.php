<?php if (! defined('ABSPATH')) { die; } // Cannot access pages directly.
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_icon extends CENOTEFramework_Options {

  public function __construct($field, $value = '', $unique = '') {
    parent::__construct($field, $value, $unique);
  }

  public function output() {

    echo $this->element_before();

    $value  = $this->element_value();
    $hidden = (empty($value)) ? ' hidden' : '';

    echo '<div class="cenote-icon-select">';
    echo '<span class="cenote-icon-preview'. $hidden .'"><i class="'. $value .'"></i></span>';
    echo '<a href="#" class="button button-primary cenote-icon-add">'. __('Add Icon', 'cenote-helper') .'</a>';
    echo '<a href="#" class="button cenote-warning-primary cenote-icon-remove'. $hidden .'">'. __('Remove Icon', 'cenote-helper') .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class('cenote-icon-value') . $this->element_attributes() .' />';
    echo '</div>';

    echo $this->element_after();

  }

}
