<?php
/**
 * Created by Mystic.
 * User: mystic
 * Date: 12/04/2020
 * Time: 9:38 AM
 */

if ( ! function_exists( 'cenote_shortcode_gallery_image' ) ) {
	function cenote_shortcode_gallery_image( $atts, $content = '', $key = '' ) {
		$shortcode_atts = array(
			'gallery_images' => '',
			'gallery_child'  => ''
		);

		extract( shortcode_atts( $shortcode_atts, $atts ) );

		static $i = 1;
		$cssID = 'cenote-gallery-sc' . $i;

		ob_start();

		$gallery_child = (array) vc_param_group_parse_atts( $gallery_child );

		$gallery_url   = wp_get_attachment_image_url( $gallery_images, 'full' );
		$gallery_crop  = wp_get_attachment_image_url( $gallery_images, 'gallery_700_450' );
		$gallery_alt   = get_post_meta( $gallery_images, '_wp_attachment_image_alt', true );
		?>
        <div id="<?php echo esc_attr( $cssID ); ?>" class="cenote-gallery-sc gallery-fancybox-sc">
            <div class="item">
                <a href="<?php echo esc_url( $gallery_url ) ?>" data-fancybox="gallery<?php echo $i ?>">
                    <img src="<?php echo esc_url( $gallery_crop ) ?>" alt="<?php echo $gallery_alt ?>">
                </a>
                <div class="list-image">
					<?php if ( ! empty( $gallery_child ) ) : ?>
						<?php foreach ( $gallery_child as $gallery ) : ?>
							<?php
							$image_url = wp_get_attachment_image_url( $gallery['image'], 'full' );
							echo '<a href="' . $image_url . '" data-fancybox="gallery' . $i . '"></a>';
							?>
						<?php endforeach; ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
		<?php
		$i ++;

		$html = ob_get_clean();

		return $html;
	}

	add_shortcode( 'cenote_shortcode_gallery_image', 'cenote_shortcode_gallery_image' );
}
