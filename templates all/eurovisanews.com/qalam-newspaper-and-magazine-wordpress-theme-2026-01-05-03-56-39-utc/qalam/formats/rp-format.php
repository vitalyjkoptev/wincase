<?php
/**
 * Tempate part for standard post format
 *
 * @package Qalam
 * @since 1.0
 * @version 2.4.0
 */
if ( has_post_thumbnail() ) {
	printf( '<div class="post-img"><a href="%s" title="%s">%s%s</a></div>',
		esc_url( get_permalink() ),
		the_title_attribute( array( 'echo' => false ) ),
		get_theme_mod( 'schema', 0 ) ?
			get_the_post_thumbnail(
				get_the_id(),
				array(
					get_theme_mod( 'arch_w', 600 ),
					get_theme_mod( 'arch_h', 0 ),
					'bfi_thumb' => get_theme_mod( 'bfi_thumb', false )
				),
				array( 'itemprop' => 'url' )
			) :
			get_the_post_thumbnail(
				get_the_id(),
				array(
					get_theme_mod( 'arch_w', 600 ),
					get_theme_mod( 'arch_h', 0 ),
					'bfi_thumb' => get_theme_mod( 'bfi_thumb', false )
				)
			),
		'video' == get_post_format() ? '<div class="' . esc_attr( get_post_format() ) . '-overlay"></div>' : ''
	);
}