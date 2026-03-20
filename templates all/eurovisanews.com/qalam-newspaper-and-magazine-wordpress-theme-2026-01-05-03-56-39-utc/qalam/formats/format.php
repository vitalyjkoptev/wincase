<?php
/**
 * Tempate part for standard post format
 *
 * @package Qalam
 * @since 1.0
 * @version 2.4.0
 */
global $post;
$schema = get_theme_mod( 'schema', 0 );
if ( has_post_thumbnail() ) {
	$width = get_theme_mod( 'arch_w', 600 );
	$height = get_theme_mod( 'arch_h', 0 );
	$bfi = get_theme_mod( 'bfi_thumb', false );

	/**
	* Double the image size for first Hero post
	* if the template style is "Mix"
	*/
	if ( '1' == get_query_var( 'qlm_post_count' ) && 'mix' == get_theme_mod( 'archive_style', 'list' ) ) {
		$width = 2 * floatval( $width );
		$height = 2 * floatval( $height );
	}

	if ( is_single() || ( is_archive() && 'full' == get_theme_mod( 'archive_style', 'list' ) ) && ! get_option( 'qlm_hide_feat_image', false ) ) {

		printf( '<div%s class="single-post-thumb">',
			$schema ? ' itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject"' : ''
		);
		if ( $schema ) {
			the_post_thumbnail( array( get_theme_mod( 'single_w', 800 ), get_theme_mod( 'single_h', 0 ), 'bfi_thumb' => $bfi ), array( 'itemprop' => 'url' ) );
		} else {
			the_post_thumbnail( array( get_theme_mod( 'single_w', 800 ), get_theme_mod( 'single_h', 0 ), 'bfi_thumb' => $bfi ) );
		}
		if ( get_the_post_thumbnail_caption() ) {
			$allowed_html = array(
			    'a' => array(
			        'href' => array(),
			        'title' => array(),
			        'rel' => array()
			    ),
			    'br' => array(),
			    'em' => array(),
			    'strong' => array(),
			);
			printf( '<p class="wp-caption-text">%s</p>',
				wp_kses( get_the_post_thumbnail_caption(), $allowed_html )
			);
		}
		echo '</div>';
	}
	else {

		$video_icon = '';
		if ( 'video' == get_post_format() ) {
			$video_icon = '<span class="' . get_post_format() . '-overlay"></span>';
		}

		printf( '<div class="%s"><a href="%s" title="%s">%s</a></div>',
			'video' !== get_post_format() ? 'post-img' : 'post-img video-thumb',
			esc_url( get_permalink() ),
			wp_strip_all_tags( get_the_title() ),
			get_the_post_thumbnail( get_the_id(), array( $width, $height, 'bfi_thumb' => $bfi ), array( 'itemprop' => 'url' ) ) . $video_icon
		);
	}
}