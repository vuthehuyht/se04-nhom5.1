<?php
defined('ABSPATH') or die;

if (! class_exists('CENOTE_WPBackeryShortCodesContainer')) {
	class CENOTE_WPBackeryShortCodesContainer extends WPBakeryShortCodesContainer {
		public function contentAdmin( $atts, $content = null ) {
			$width  = $el_class = '';

			extract( shortcode_atts( $this->predefined_atts, $atts ) );

			$label_class    = isset( $this->settings['label_class'] ) ? $this->setttings['label_class'] : 'info';
			$output         = '';

			$column_controls        = $this->getColumnControls( $this->settings( 'controls' ) );
			$column_controls_bottom = $this->getColumnControls( 'add', 'bottom-controls' );

			for ( $i = 0; $i < count( $width ); $i++ ) {
				$output .= '<div ' . $this->mainHtmlBlockParams( $width, $i ) . '>';
				$output .= '<div class="cenote-container-title"><span class="cenote-label cenote-label-' . $label_class . '">' . $this->settings['name'] . '</span></div>'; // ADDED THIS LINE
				$output .= $column_controls;
				$output .= '<div class="wpb_element_wrapper">';
				$output .= '<div ' . $this->containerHtmlBlockParams( $width, $i ) . '>';
				$output .= do_shortcode( shortcode_unautop( $content ) );
				$output .= '</div>';

				if ( isset( $this->settings['params'] ) ) {
					$inner  = '';

					foreach ( $this->settings['params'] as $param ) {
						$param_value    = isset( $$param['param_name'] ) ? $$param['param_name'] : '';

						if ( is_array( $param_value ) ) {
							// Get first element from the array
							reset( $param_value );

							$first_key      = key( $param_value );
							$param_value    = $param_value[ $first_key ];
						}

						$inner  .= $this->singleParamHtmlHolder( $param, $param_value );
					}

					$output .= $inner;
				}
				$output .= '</div>';
				$output .= $column_controls_bottom;
				$output .= '</div>';
			}

			return $output;
		}
	}
}
