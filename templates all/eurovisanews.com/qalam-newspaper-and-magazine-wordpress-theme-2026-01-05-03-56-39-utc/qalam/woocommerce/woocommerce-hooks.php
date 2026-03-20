<?php
/**
 * WooCommerce custom hooks and functions used by the theme
 */

// Disable WooCommerce CSS files
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// Update cart fragments in top nav
function qalam_wc_cart_fragments( $fragments ) {
	global $woocommerce;
	ob_start();

	printf( '<a class="cart-contents" href="%s" title="%s"><i class="fas fa-shopping-basket"></i>%s</a>',
		wc_get_cart_url(),
		esc_attr__( 'View your shopping cart', 'qalam' ),
		'<sup class="amount">' . WC()->cart->get_cart_contents_count() . '</sup>'
	);

	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'qalam_wc_cart_fragments' );


// Change WooCommerce Breadcrumb separator
function qalam_wc_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = '<span class="sep"></span>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'qalam_wc_breadcrumb_delimiter' );