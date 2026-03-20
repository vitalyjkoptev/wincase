<?php
/**
 * The template for displaying all pages.
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

get_header();

$content_class = '';

if ( is_active_sidebar( 'default-sidebar' ) ) {
    $content_class .= ' has-sba';
}

if ( is_active_sidebar( 'sidebar-b' ) ) {
    $content_class .= ' has-sbb';
}

if ( ! is_active_sidebar( 'default-sidebar' ) && ! is_active_sidebar( 'sidebar-b' ) ) {
    $content_class = ' full-width';
}

if ( class_exists( 'woocommerce' ) && ( is_singular( 'product' ) || is_cart() || is_checkout() || is_account_page() ) ) {
    $content_class = ' full-width';
}
?>
<div id="primary" class="site-content<?php echo esc_attr( $content_class ); ?>">
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
        <?php
        qalam_sidebar_b();
        ?>
    </div><!--.primary-row -->
</div><!-- #primary -->
<?php
if ( 'no-sb' != get_theme_mod( 'sb_pos', 'ca' ) ) {
    get_sidebar();
}
get_footer();