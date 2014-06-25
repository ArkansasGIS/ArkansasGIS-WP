<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Integrates this theme with the Sensei plugin
 * http://www.woothemes.com/products/sensei/
 */

/**
 * Declare support
 * Decares this themes compatibility with Sensei.
 */
add_action( 'after_setup_theme', 'woo_sensei_support' );

/**
 * Styles
 * Disable stock sensei css and enqueue our own.
 */
add_filter( 'sensei_disable_styles', '__return_true' );
add_action( 'wp_enqueue_scripts', 'woo_sensei_css', 10 );


/**
 * Wrappers
 * Remove default Sensei wrappers and replace with our own
 */
add_action( 'init', 'woo_sensei_remove_wrappers' );

add_action( 'sensei_before_main_content', 'woo_sensei_layout_wrap', 10 );
add_action( 'sensei_after_main_content', 'woo_sensei_layout_wrap_end', 10 );

add_action( 'sensei_before_main_content', 'woo_sensei_content_wrap', 14 );
add_action( 'sensei_after_main_content', 'woo_sensei_content_wrap_end', 8 );


/**
 * Breadcrumbs
 * Pull the Woo Breadcrumbs in
 */
add_action( 'sensei_before_main_content', 'woo_display_breadcrumbs', 12 );


/**
 * Pagination
 * Remove the Sensei pagination and add our own
 */
add_action( 'init', 'woo_sensei_remove_pagination' );
add_action( 'sensei_pagination', 'woo_sensei_pagination', 10 );