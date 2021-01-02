<?php

defined('ABSPATH') or die;

/**
 *
 * Field: Demo Importer
 *
 * @since 1.3.0
 * @version 1.0.0
 */
class CENOTEFramework_Option_Demo_Importer extends CENOTEFramework_Options {
	public function output() {
		cenote_locate_template('classes/demo-importer/state.php');

		echo $this->element_before();

		$condition		= isset($this->field['condition']) ? $this->field['condition'] : '';
		$message		= isset($this->field['message']) ? $this->field['message'] : '';
		$demos			= isset($this->field['demos']) ? $this->field['demos'] : array();
		$installed_demo	= Cenote_Demo_Importer_State::get_installed_demo();

		if (!$condition) {
			echo $message;
		} else {
			?>

			<div class="cenote-demo-importer">
				<?php foreach ($demos as $demo) :
					if ($installed_demo === false) {
						$class	= '';
					} else {
						$class	= $installed_demo == $demo['id'] ? ' cenote-demo-installed' : ' cenote-demo-disabled';
					}
				?>
					<div class="cenote-demo cenote-demo-<?php echo esc_attr($demo['id']); ?><?php echo $class; ?>">
						<div class="cenote-demo-overlay"></div>

						<div class="cenote-demo-thumbnail">
							<img class="cenote-demo-thumbnail-img" src="<?php echo esc_url($demo['thumbnail']); ?>" alt="<?php echo esc_attr($demo['title']); ?>" />
						</div>

						<div class="cenote-demo-info">
							<h3><?php echo $demo['title']; ?></h3>

							<div class="cenote-demo-actions">
								<a href="#" class="button button-primary cenote-button-install-demo" data-demo-id="<?php echo esc_attr($demo['id']); ?>">
									<?php echo esc_html__('Install', 'cenote-helper'); ?>
								</a>
								<a href="#" class="button button-primary cenote-button-install-demo-no-content" data-demo-id="<?php echo esc_attr($demo['id']); ?>">
									<?php echo esc_html__('Install No Content', 'cenote-helper'); ?>
								</a>
								<a href="#" class="button button-primary cenote-button-uninstall-demo" data-demo-id="<?php echo esc_attr($demo['id']); ?>">
									<?php echo esc_html__('Uninstall', 'cenote-helper'); ?>
								</a>
								<p class="cenote-demo-installed-msg"><?php echo esc_html__('Demo Installed!', 'cenote-helper'); ?></p>
							</div>
						</div>

						<div class="cenote-demo-progress-bar-wrapper">
							<div class="cenote-demo-progress-bar"></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php
		}

		echo $this->element_after();
	}
}
