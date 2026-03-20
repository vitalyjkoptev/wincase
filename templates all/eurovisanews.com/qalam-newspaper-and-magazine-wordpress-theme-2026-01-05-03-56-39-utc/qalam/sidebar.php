<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

/**
 * Do not load sidebar if it is a single product,
 * cart, checkout or my account page
 */
if ( class_exists( 'woocommerce' ) && ( is_singular( 'product' ) || is_cart() || is_checkout() || is_account_page() ) ) {
	return;
}
?>

<div id="sidebar" class="widget-area" role="complementary">
	<?php
	if ( is_active_sidebar( 'default-sidebar' ) ) :
		dynamic_sidebar( 'default-sidebar' );
    endif;
    ?>
</div><!-- #sidebar -->