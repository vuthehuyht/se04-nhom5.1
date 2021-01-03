<?php
/**
 * Created by Mystic.
 * User: mystic
 * Date: 12/04/2020
 * Time: 9:38 AM
 */

vc_map( array(
	'name'     => esc_html__( 'Gallery Image', 'cenote-helper' ),
	'base'     => 'cenote_shortcode_gallery_image',
	'icon'     => 'fa fa-navicon',
	'category' => esc_html__( 'Cenote', 'cenote-helper' ),
	'params'   => array_merge(
		array(
			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Images', 'cenote-helper' ),
				'param_name' => 'gallery_images'
			),
			array(
				'type'       => 'param_group',
				'value'      => urlencode( json_encode( array(
					array(
						'image'   => ''
					),
				) ) ),
				'param_name' => 'gallery_child',
				'params'     => array(
					array(
						'type'       => 'attach_image',
						'heading'    => esc_html__( 'Images', 'cenote-helper' ),
						'param_name' => 'image'
					)
				)
			)
		)
	)
) );
