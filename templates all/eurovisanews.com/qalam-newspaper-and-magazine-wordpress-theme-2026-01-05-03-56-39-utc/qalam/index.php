<?php
/**
 * The template for displaying Archive pages
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

get_header();

$full_width = get_theme_mod( 'archive_fw', false );
$archive_template = get_theme_mod( 'archive_style', 'list' );
$archive_template = ( 'card' == $archive_template ? 'grid' : $archive_template );

$content_class = '';

if ( is_active_sidebar( 'default-sidebar' ) ) {
    $content_class .= ' has-sba';
}

if ( is_active_sidebar( 'sidebar-b' ) ) {
    $content_class .= ' has-sbb';
}

if ( ! is_active_sidebar( 'default-sidebar' ) && ! ( in_array( get_theme_mod( 'sb_pos', 'ca' ), array( 'cba', 'acb', 'bca', 'abc' ) ) && is_active_sidebar( 'sidebar-b' ) ) || get_theme_mod( 'archive_fw', false ) ) {
    $content_class = ' full-width';
}
?>
<div id="primary" class="site-content<?php echo esc_attr( $content_class ); ?>">
    <div class="primary-row">
        <div id="content" role="main">
            <?php
            if ( have_posts() ) :
                the_archive_description( '<header class="page-header"><div class="taxonomy-description">', '</div></header>' );

                get_template_part( 'content', $archive_template );

            endif; ?>
        </div><!-- #content -->
        <?php
        if ( ! $full_width ) {
            qalam_sidebar_b();
        }
        ?>
    </div><!-- .primary-row -->
</div><!-- #primary -->
<?php
if ( 'no-sb' != get_theme_mod( 'sb_pos', 'ca' ) ) {
    if ( ! $full_width ) {
        get_sidebar();
    }
}
get_footer();