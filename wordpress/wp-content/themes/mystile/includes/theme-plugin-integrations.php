<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Checks if plugins are activated and loads logic accordingly.
 * @uses  class_exists() detect if a class exists
 * @uses  function_exists() detect if a function exists
 * @uses  defined() detect if a constant is defined
 */

/**
 * Sensei by WooThemes
 * @link http://www.woothemes.com/products/sensei/
 */
if ( class_exists( 'Woothemes_Sensei' ) ) {
	require_once( get_template_directory() . '/includes/integrations/sensei/setup.php' );
	require_once( get_template_directory() . '/includes/integrations/sensei/template.php' );
	require_once( get_template_directory() . '/includes/integrations/sensei/functions.php' );
}