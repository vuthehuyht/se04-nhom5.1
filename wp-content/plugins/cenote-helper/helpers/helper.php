<?php
/**
 * Created by Mystic.
 * User: mystic
 */

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// CORE CODE
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
/**
 * Get registered sidebars
 */
if ( ! function_exists( 'cenote_wp_registered_sidebars' ) ) {
	function cenote_wp_registered_sidebars() {
		global $wp_registered_sidebars;

		$widgets = array();

		if ( ! empty( $wp_registered_sidebars ) ) {
			foreach ( $wp_registered_sidebars as $key => $value ) {
				$widgets[ $key ] = $value['name'];
			}
		}

		return array_reverse( $widgets );
	}
}

//check key in array
if ( ! function_exists( 'cenote_check_key_not_in_array' ) ) {
	function cenote_check_key_not_in_array( $arr, $key ) {
		if ( ( ! empty( $arr ) && ! in_array( $key, $arr ) ) || empty( $arr ) ) {
			return true;
		} else {
			return false;
		}
	}
}

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// ONLY THEME CODE
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
if ( ! function_exists( 'cenote_style_css' ) ) {
	function cenote_style_css( $style_name, $data, $number = false ) {
		$html = array();
		if ( $number == true ) {
			$data    = ( $data != '' && ( $data == 0 || $data == '0' ) ) ? $data . 'px' : $data;
			$pattern = '/^(.\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
			$regexr  = preg_match( $pattern, $data, $matches );
			$value   = isset( $matches[1] ) ? (float) $matches[1] : '0';
			$unit    = isset( $matches[2] ) ? $matches[2] : 'px';
			$output  = $value . $unit;
			$html[]  = $data ? $style_name . ':' . $output . ';' : '';
		} else {
			$html[] = $data ? $style_name . ':' . $data . ';' : '';
		}

		return implode( '', $html );
	}
}

if ( ! function_exists( 'cenote_style_css_variables' ) ) {
	function cenote_style_css_variables( $style_name, $data, $custom, $number = false ) {
		$value = $data ? $data : $custom;
		$html  = array();

		if ( $number == true ) {
			$html[] = cenote_style_css( $style_name, $value, $number );
		} else {
			$html[] = $value ? $style_name . ':' . $value . ';' : '';
		}

		return implode( '', $html );
	}
}
