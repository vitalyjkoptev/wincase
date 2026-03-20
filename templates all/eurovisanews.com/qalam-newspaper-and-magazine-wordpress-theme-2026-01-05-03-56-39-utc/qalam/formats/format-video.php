<?php
/**
 * Tempate part for video post format
 *
 * @package Qalam
 * @since 1.0
 * @version 2.4.0
 */

$content = apply_filters( 'the_content', get_the_content() );
$video = false;

// Only get video from the content if a playlist isn't present.
if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
}

// If not a single post, highlight the video file.
if ( ! empty( $video ) && get_theme_mod( 'show_embed', 0 ) ) :
	if ( preg_match("/wp-video-shortcode/", $video[0]) ) {
		echo esc_url( $video[0] );
	}
	else {
		echo '<div class="embed-wrap">' . $video[0] . '</div>';
	}
else :
	include( locate_template( 'formats/format.php' ) );
endif;