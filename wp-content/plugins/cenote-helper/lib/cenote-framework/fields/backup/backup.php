<?php if (! defined('ABSPATH')) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CENOTEFramework_Option_backup extends CENOTEFramework_Options {

  public function __construct($field, $value = '', $unique = '') {
    parent::__construct($field, $value, $unique);
  }

  public function output() {

    echo $this->element_before();

    echo '<textarea name="'. $this->unique .'[import]"'. $this->element_class() . $this->element_attributes() .'></textarea>';
    submit_button(__('Import a Backup', 'cenote-helper'), 'primary cenote-import-backup', 'backup', false);
    echo '<small>('. __('copy-paste your backup string here', 'cenote-helper').')</small>';

    echo '<hr />';

    echo '<textarea name="_nonce"'. $this->element_class() . $this->element_attributes() .' disabled="disabled">'. cenote_encode_string(get_option($this->unique)) .'</textarea>';
    echo '<a href="'. admin_url('admin-ajax.php?action=cenote-export-options') .'" class="button button-primary" target="_blank">'. __('Export and Download Backup', 'cenote-helper') .'</a>';
    echo '<small>-('. __('or', 'cenote-helper') .')-</small>';
    submit_button(__('Reset All Options', 'cenote-helper'), 'cenote-warning-primary cenote-reset-confirm', $this->unique . '[resetall]', false);
    echo '<small class="cenote-text-warning">'. __('Please be sure for reset all of framework options.', 'cenote-helper') .'</small>';

    echo $this->element_after();

  }

}
