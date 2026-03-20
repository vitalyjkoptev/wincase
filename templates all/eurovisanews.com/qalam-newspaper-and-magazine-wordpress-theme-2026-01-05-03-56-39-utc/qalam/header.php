<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "#main" div.
 */

?><!DOCTYPE html>
<html <?php if ( get_theme_mod( 'schema', 0 ) ) { echo ' itemscope="itemscope" itemtype="https://schema.org/Website"'; } language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    
    wp_body_open();    

    if ( has_nav_menu( 'mobile' ) ) {
    ?>
        <div id="resp-menu">
            <?php
            echo '<a href="#" class="close-menu">'. esc_html__( 'Close', 'qalam' ) . '</a>';
            $menu_args = array( 'theme_location' => 'mobile', 'menu_class' => 'resp-menu', 'container' => false, 'fallback_cb' => false );
            wp_nav_menu( $menu_args );
            ?>
        </div><!--/ #resp-menu -->
    <?php
    }
    if ( is_active_sidebar( 'top-widget-area' ) ) { ?>
        <div class="container top-widget-area">
            <?php dynamic_sidebar( 'top-widget-area' ); ?>
        </div><!-- .top-widget-area -->
        <?php
    }
    if ( get_theme_mod( 'topbar_check', 1 ) ) {
        get_template_part( 'template-parts/header/top-bar' );
    }
    ?>
    <div id="page" class="site">
        <?php
        get_template_part( 'template-parts/header/header', get_theme_mod( 'header_style', '1' ) );
        /**
         * Hook: qalam_after_header
         *
         * @hooked qlm_widget_area_before_content - 10
         * @hooked qlm_show_breadcrumbs - 15
         */
        do_action( 'qalam_after_header' );
        ?>
        <div id="main">
            <div class="container clearfix">
                <div class="main-row clearfix">