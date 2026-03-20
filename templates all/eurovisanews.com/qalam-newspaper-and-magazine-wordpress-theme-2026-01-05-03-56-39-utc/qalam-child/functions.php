<?php
/**
 * Qalam Child theme functions
 *
 * Add your custom functions in this file to override parent theme
 * functions. You can also add scripts and CSS in this file.
 *
 */

// Load parent theme stylesheet
function qalam_parent_enqueue_styles() {
    wp_enqueue_style( 'qalam-style', get_template_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'qalam_parent_enqueue_styles' );

// Load child theme stylesheet on a late priority
function qalam_child_enqueue_styles() {
    wp_enqueue_style( 'qalam-child-style', get_stylesheet_uri(), array( 'qalam-style' ) );
}

add_action( 'wp_enqueue_scripts', 'qalam_child_enqueue_styles', 100 );