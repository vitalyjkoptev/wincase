<?php
/**
*
* Template Name: Qalam Full Width
*
* The template for displaying a full width page with header and footer
*
* @package Qalam
* @since 1.0.0
* @version 2.4.0
*/

get_header(); ?>
<div id="primary" class="site-content full-width">
	<div class="primary-row clearfix">
		<div id="content" role="main">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
						<?php
                        if ( get_theme_mod( 'page_titles_check', true ) && '' !== get_the_title() ) {
                            the_title( '<header class="page-header"><h1 class="entry-title">', '</h1></header>' );
                         }
                        ?>
						<div class="entry-content">
							<?php
							the_content();
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'qalam' ), 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
					</article><!-- #post -->
					<?php
                    /**
                     * Hook: qlm_page_content_after
                     *
                     * @hooked qlm_comments_single - 30
                     */
                    do_action( 'qlm_page_content_after' );
				endwhile;
			else :
				qalam_no_posts();
			endif; ?>
		</div><!-- #content -->
	</div><!--.primary-row -->
</div><!-- #primary -->
<?php
get_footer();