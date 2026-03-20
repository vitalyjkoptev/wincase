<?php
/**
 *
 * Template Name: Qalam Canvas
 *
 * The template for displaying a full width canvas page
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

get_header();
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            the_content();
            wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'qalam' ), 'after' => '</div>' ) );

            /**
             * Hook: qlm_page_content_after
             *
             * @hooked qlm_comments_single - 30
             */
            do_action( 'qlm_page_content_after' );
        endwhile;
    else :
        qalam_no_posts();
    endif;
get_footer();