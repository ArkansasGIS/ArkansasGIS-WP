<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: WooThemes shortcode generator.
Date Created: 2011-01-21.
Author: Based on the work of the Shortcode Ninja plugin by VisualShortcodes.com.
Integration and Addons: Matty.
Since: 3.5.0


TABLE OF CONTENTS

- Constructor Function
- function init()
- function filter_mce_buttons()
- function filter_mce_external_plugins()

- Utility Functions
- framework_url()
- ajax_action_check_url()
- ajax_action_generate_nonce()

INSTANTIATE CLASS

-----------------------------------------------------------------------------------*/

class WooThemes_Shortcode_Generator {

/*-----------------------------------------------------------------------------------
  Class Variables

  * Setup of variable placeholders, to be populated when the constructor runs.
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------
  Class Constructor

  * Constructor function. Sets up the class and registers variable action hooks.
-----------------------------------------------------------------------------------*/

	function WooThemes_Shortcode_Generator () {
		// Register the necessary actions on `admin_init`.
		add_action( 'admin_init', array( $this, 'init' ) );

		// wp_ajax_... is only run for logged users.
		add_action( 'wp_ajax_woo_check_url_action', array( $this, 'ajax_action_check_url' ) );
		add_action( 'wp_ajax_woo_shortcodes_nonce', array( $this, 'ajax_action_generate_nonce' ) );

		// Output the markup in the footer.
		add_action( 'admin_footer', array( $this, 'output_dialog_markup' ) );
	} // End WooThemes_Shortcode_Generator()

/*-----------------------------------------------------------------------------------
  init()

  * This guy runs the show. Rocket boosters... engage!
-----------------------------------------------------------------------------------*/

	function init() {
		global $pagenow;

		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) == 'true' && ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' ) ) ) )  {

		  	// Add the tinyMCE buttons and plugins.
			add_filter( 'mce_buttons', array( $this, 'filter_mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_external_plugins' ) );

			// Register the colourpicker JavaScript.
			wp_register_script( 'woo-colourpicker', esc_url( $this->framework_url() . 'js/colorpicker.js' ), array( 'jquery' ), '3.6', true ); // Loaded into the footer.
			wp_enqueue_script( 'woo-colourpicker' );

			// Register the colourpicker CSS.
			wp_register_style( 'woo-colourpicker', esc_url( $this->framework_url() . 'css/colorpicker.css' ) );
			wp_enqueue_style( 'woo-colourpicker' );

			wp_register_style( 'woo-shortcode-icon', esc_url( $this->framework_url() . 'css/shortcode-icon.css' ) );
			wp_enqueue_style( 'woo-shortcode-icon' );

			// Register the custom CSS styles.
			wp_register_style( 'woo-shortcode-generator', esc_url( $this->framework_url() . 'css/shortcode-generator.css' ) );
			wp_enqueue_style( 'woo-shortcode-generator' );

		} // End IF Statement

	} // End init()

/*-----------------------------------------------------------------------------------
  filter_mce_buttons()

  * Add our new button to the tinyMCE editor.
-----------------------------------------------------------------------------------*/

	function filter_mce_buttons( $buttons ) {

		array_push( $buttons, '|', 'woothemes_shortcodes_button' );

		return $buttons;

	} // End filter_mce_buttons()

/*-----------------------------------------------------------------------------------
  filter_mce_external_plugins()

  * Add functionality to the tinyMCE editor as an external plugin.
-----------------------------------------------------------------------------------*/

	function filter_mce_external_plugins( $plugins ) {
		global $wp_version;
		$suffix = '';
		if ( '3.9' <= $wp_version ) {
			$suffix = '_39';
		}
        $plugins['WooThemesShortcodes'] = wp_nonce_url( esc_url( $this->framework_url() . 'js/shortcode-generator/editor_plugin' . $suffix . '.js' ), 'wooframework-shortcode-generator' );

        return $plugins;

	} // End filter_mce_external_plugins()

/*-----------------------------------------------------------------------------------
  Utility Functions

  * Helper functions for this class.
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------
  framework_url()

  * Returns the full URL of the WooFramework, including trailing slash.
-----------------------------------------------------------------------------------*/

function framework_url() {
	return esc_url( trailingslashit( get_template_directory_uri() . '/' . basename( dirname( __FILE__ ) ) ) );
} // End framework_url()

/*-----------------------------------------------------------------------------------
  ajax_action_check_url()

  * Checks if a given url (via GET or POST) exists.
  * Returns JSON.
  *
  * NOTE: For users that are not logged in this is not called.
  * The client recieves <code>-1</code> in that case.
-----------------------------------------------------------------------------------*/

function ajax_action_check_url() {
	$hadError = true;

	$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

	if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
		$url = esc_url( $url );
		$file_headers = @get_headers( $url );
		$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
		$hadError     = false;
	}

	echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

	die();
} // End ajax_action_check_url()

/*-----------------------------------------------------------------------------------
  ajax_action_generate_nonce()

  * Generate a nonce.
  *
  * NOTE: For users that are not logged in this is not called.
  * The client recieves <code>-1</code> in that case.
-----------------------------------------------------------------------------------*/

function ajax_action_generate_nonce() {
	echo wp_create_nonce( 'wooframework-shortcode-generator' );
	die();
} // End ajax_action_generate_nonce()


	/**
	 * Output the HTML markup for the dialog box.
	 * @access public
	 * @since  5.5.6
	 * @return void
	 */
	public function output_dialog_markup () {
		$woo_framework_url = $this->framework_url();
		$woo_framework_version = get_option( 'woo_framework_version' );

		$MIN_VERSION = '2.9';

		$meetsMinVersion = version_compare($woo_framework_version, $MIN_VERSION) >= 0;

		$isWooTheme = true;
?>
<div id="woo-dialog" style="display: none;">

<?php if ( $meetsMinVersion && $isWooTheme ) { ?>
<div id="woo-options-buttons" class="clear">
	<div class="alignleft">

	    <input type="button" id="woo-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />

	</div>
	<div class="alignright">
	    <input type="button" id="woo-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />
	</div>
	<div class="clear"></div><!--/.clear-->
</div><!--/#woo-options-buttons .clear-->

<div id="woo-options" class="alignleft">
    <h3><?php echo __( 'Customize the Shortcode', 'woothemes' ); ?></h3>

	<table id="woo-options-table">
	</table>

</div>
<div class="clear"></div>


<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/column-control.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/tab-control.js' ); ?>"></script>
<?php  }  else { ?>

<div id="woo-options-error">

    <h3><?php echo __( 'Ninja Trouble', 'woothemes' ); ?></h3>

    <?php if ( $isWooTheme && ( ! $meetsMinVersion ) ) { ?>
    <p><?php echo sprinf ( __( 'Your version of the WooFramework (%s) does not yet support shortcodes. Shortcodes were introduced with version %s of the framework.', 'woothemes' ), $woo_framework_version, $MIN_VERSION ); ?></p>

    <h4><?php echo __( 'What to do now?', 'woothemes' ); ?></h4>

    <p><?php echo __( 'Upgrading your theme, or rather the WooFramework portion of it, will do the trick.', 'woothemes' ); ?></p>

	<p><?php echo sprintf( __( 'The framework is a collection of functionality that all WooThemes have in common. In most cases you can update the framework even if you have modified your theme, because the framework resides in a separate location (under %s).', 'woothemes' ), '<code>/functions/</code>' ); ?></p>

	<p><?php echo sprintf ( __( 'There\'s a tutorial on how to do this on WooThemes.com: %sHow to upgradeyour theme%s.', 'woothemes' ), '<a title="WooThemes Tutorial" target="_blank" href="http://www.woothemes.com/2009/08/how-to-upgrade-your-theme/">', '</a>' ); ?></p>

	<p><?php echo __( '<strong>Remember:</strong> Every Ninja has a backup plan. Safe or not, always backup your theme before you update it or make changes to it.', 'woothemes' ); ?></p>

<?php } else { ?>

    <p><?php echo __( 'Looks like your active theme is not from WooThemes. The shortcode generator only works with themes from WooThemes.', 'woothemes' ); ?></p>

    <h4><?php echo __( 'What to do now?', 'woothemes' ); ?></h4>

	<p><?php echo __( 'Pick a fight: (1) If you already have a theme from WooThemes, install and activate it or (2) if you don\'t yet have one of the awesome WooThemes head over to the <a href="http://www.woothemes.com/themes/" target="_blank" title="WooThemes Gallery">WooThemes Gallery</a> and get one.', 'woothemes' ); ?></p>

<?php } ?>

<div style="float: right"><input type="button" id="woo-btn-cancel"
	class="button" name="cancel" value="Cancel" accesskey="C" /></div>
</div>

<?php  } ?>

<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/dialog-js.php' ); ?>"></script>
</div>
<?php
	} // End output_dialog_markup()
} // End Class

/*-----------------------------------------------------------------------------------
  INSTANTIATE CLASS
-----------------------------------------------------------------------------------*/

$woo_shortcode_generator = new WooThemes_Shortcode_Generator();
?>