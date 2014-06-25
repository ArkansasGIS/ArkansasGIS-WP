<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo - Social Widgets Settings
 *
 * Settings for the WooDojo - Social Widgets feature.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Bundled
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - __construct()
 * - init_sections()
 * - init_fields()
 */
class WooDojo_SocialWidgets_Settings extends WooDojo_Settings_API {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct () {
		parent::__construct(); // Required in extended classes.
	} // End __construct()

	/**
	 * init_sections function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_sections () {
		$sections = array();

		$sections['twitter'] = array(
								'name' => __( 'Twitter API Settings', 'woodojo' ),
								'description' => __( 'Twitter API settings and configuration. For details on obtaining these settings please see the <a href="http://docs.woothemes.com/document/woodojo-tweets-widget-twitter-api-version-1-1-setup">documentation</a>.', 'woodojo' )
								);
		$this->sections = $sections;
	} // End init_sections()

	/**
	 * init_fields function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_fields () {
		$fields = array();

		$fields['consumer_key'] = array(
								'name' => __( 'Consumer Key', 'woodojo' ),
								'description' => __( 'Enter your Twitter Application Consumer Key.', 'woodojo' ),
								'type' => 'select',
								'type' => 'text',
								'default' => '',
								'section' => 'twitter',
								'required' => 0
								);

		$fields['consumer_secret'] = array(
								'name' => __( 'Consumer Secret', 'woodojo' ),
								'description' => __( 'Enter your Twitter Application Consumer Secret.', 'woodojo' ),
								'type' => 'text',
								'default' => '',
								'section' => 'twitter',
								'required' => 0
								);

		$fields['access_key'] = array(
								'name' => __( 'Access Token', 'woodojo' ),
								'description' => __( 'Enter your Twitter Application Token.', 'woodojo' ),
								'type' => 'text',
								'default' => '',
								'section' => 'twitter',
								'required' => 0
								);
		$fields['access_secret'] = array(
								'name' => __( 'Access Token Secret', 'woodojo' ),
								'description' => __( 'Enter your Twitter Application Token Secret.', 'woodojo' ),
								'type' => 'text',
								'default' => '',
								'section' => 'twitter',
								'required' => 0
								);

		$this->fields = $fields;
	} // End init_fields()
} // End Class
?>