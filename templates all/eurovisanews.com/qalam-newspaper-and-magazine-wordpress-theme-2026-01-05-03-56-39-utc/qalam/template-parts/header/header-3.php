<?php
/**
 * Template part for header 3
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

?>
<header id="header" class="site-header hst-3">
    <div class="light-nav nav-1">
        <div class="container has-menu-trigger clearfix">
            <div class="flex w-100 flex-center">
                <?php
                $has_logo = false;
                if ( get_custom_logo() || get_theme_mod( 'header_text', 'true' ) ) :
                    $has_logo = true;
                ?>
                <div class="qlm-col w-100 site-branding text-center">
                    <?php
                    the_custom_logo();
                    ?>
                    <div class="site-branding-text">
                        <?php if ( is_front_page() ) : ?>
                            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php else : ?>
                            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                        <?php endif; ?>

                        <?php $description = get_bloginfo( 'description', 'display' );
                        if ( $description || is_customize_preview() ) : ?>
                            <p class="site-description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                    </div><!-- .site-branding-text -->
                </div><!-- .brand -->
                <?php
                endif;
                ?>
            </div><!-- /.flex -->
        </div><!-- .container -->
    </div><!-- /.light-nav -->
    <div class="light-nav nav-2 sticky-nav">
        <div class="container has-menu-trigger clearfix">
            <div class="flex w-100 flex-center">
                <?php
                if ( has_nav_menu( 'mobile' ) ) {
                ?>
                    <a class="menu-button menu-trigger"><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'qalam' ); ?></span><span class="toggle-icon"></span></a>
                <?php
                }
				if ( get_theme_mod( 'social_check', true ) ) {
                    echo qlm_header_social_links( ' qlm-col' );
				}
				?>
                <nav<?php if ( get_theme_mod( 'schema', 0 ) ) { echo ' itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement"'; } ?>  id="main-nav" class="main-navigation text-center qlm-col">
                    <?php
                        wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => '', 'container' => false,'fallback_cb' => false ) );
                    ?>

                </nav><!-- #main-nav -->

                <div class="utility-links qlm-col text-right">
                    <?php
                    /**
                     * Hook: qalam_utility_links.
                     *
                     * @hooked qlm_add_social_links - 10
                     * @hooked qlm_woo_cart - 15
                     * @hooked qlm_search_panel - 20
                     */
                    do_action( 'qalam_utility_links' );
                    ?>
                </div><!-- /.utility-links -->
            </div><!-- /.flex -->
        </div><!-- .container -->
    </div><!-- /.light-nav -->
</header><!-- .header-slim -->