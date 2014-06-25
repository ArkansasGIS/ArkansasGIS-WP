<?php
/**
 * Module Name: WooDojo - Social Widgets
 * Module Description: A collection of widgets to connect to your online social profiles.
 * Module Version: 1.1.3
 * Module Settings: woodojo-social-widgets-settings
 *
 * @package WooDojo
 * @subpackage Bundled
 * @author Matty
 * @since 1.0.0
 */

if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/* Include Social Widgets Class*/
require_once( 'classes/woodojo-social-widgets.class.php' );
/* Instantiate Social Widgets Class */
if ( class_exists( 'WooDojo' ) ) {
	global $WooDojo_Social_Widgets;
	$WooDojo_Social_Widgets = new WooDojo_Social_Widgets();
}
 /**
  * woodojo_socialwidgets_register function.
  * 
  * @access public
  * @since 1.0.0
  * @return void
  */
 
if ( ! function_exists( 'woodojo_socialwidgets_register' ) ) {
 	add_action( 'widgets_init', 'woodojo_socialwidgets_register' );

	function woodojo_socialwidgets_register () {
		global $woodojo;
		$widgets = array();
		$widgets['WooDojo_Widget_Tweets'] = 'widgets/widget-woodojo-tweets.php';
	 	$widgets['WooDojo_Widget_TwitterProfile'] = 'widgets/widget-woodojo-twitter-profile.php';
	 	$widgets['WooDojo_Widget_Instagram'] = 'widgets/widget-woodojo-instagram.php';
	 	$widgets['WooDojo_Widget_InstagramProfile'] = 'widgets/widget-woodojo-instagram-profile.php';

		$widgets = apply_filters( 'woodojo_socialwidgets_widgets', $widgets );

		if ( count( $widgets ) > 0 ) {
			foreach ( $widgets as $k => $v ) {
				if ( file_exists( $woodojo->base->components_path . 'woodojo-social-widgets/' . $v ) ) {
					require_once( $v );
					register_widget( $k );
				}
			}
		}
	} // End woodojo_socialwidgets_register()
}
?>