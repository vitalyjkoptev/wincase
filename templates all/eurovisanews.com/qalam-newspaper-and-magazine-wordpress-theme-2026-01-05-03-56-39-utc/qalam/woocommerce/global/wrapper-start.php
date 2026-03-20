<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content_class = '';

if ( is_active_sidebar( 'default-sidebar' ) ) {
    $content_class .= ' has-sba';
}

if ( is_active_sidebar( 'sidebar-b' ) ) {
    $content_class .= ' has-sbb';
}

if ( ! is_active_sidebar( 'default-sidebar' ) && ! ( in_array( get_theme_mod( 'sb_pos', 'ca' ), array( 'cba', 'acb', 'bca', 'abc' ) ) && is_active_sidebar( 'sidebar-b' ) ) ) {
    $content_class = ' full-width';
}

if ( class_exists( 'woocommerce' ) && ( is_singular( 'product' ) || is_cart() || is_checkout() || is_account_page() ) ) {
	$content_class = ' full-width';
}
?>
<div id="primary" class="site-content<?php echo esc_attr( $content_class ); ?>">
    <div class="primary-row">
        <div id="content" role="main">