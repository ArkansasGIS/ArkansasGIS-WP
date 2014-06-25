<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------*/
/* WooThemes Media Library-driven AJAX File Uploader Module */
/* 2010-11-05. */
/*
/* If we're on a WooThemes specific administration page, add Media Library Uploader
/* specific actions for CSS, JavaScript and several other functionalities.
/*-----------------------------------------------------------------------------------*/

if ( is_admin() ) {
	add_action( 'init', 'woothemes_mlu_init' );
	add_action( 'admin_print_scripts', 'woothemes_mlu_insidepopup' );
	add_filter( 'gettext', 'woothemes_mlu_change_button_text', null, 2 );
	
	$is_posts_page = 0;

	// Sanitize value.
	$_current_url =  strtolower( strip_tags( trim( $_SERVER['REQUEST_URI'] ) ) );

	if ( ( substr( basename( $_current_url ), 0, 8 ) == 'post.php' ) || substr( basename( $_current_url ), 0, 12 ) == 'post-new.php' ) {
		$is_posts_page = 1;
	}

	$_page = '';

	if ( ( isset( $_REQUEST['page'] ) ) ) {
		// Sanitize value.
		$_page = strtolower( strip_tags( trim( $_REQUEST['page'] ) ) );
	}

		if ( ( $_page != '' && substr( $_page, 0, 3 ) == 'woo' ) || $is_posts_page ) {
			add_action( 'admin_print_styles', 'woothemes_mlu_css', 0 );
			add_action( 'admin_print_scripts', 'woothemes_mlu_js', 0 );
		}
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_init */
/*
/* Global init() function for the WooThemes Media Library-driven AJAX File Uploader.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_init' ) ) {
	function woothemes_mlu_init () {
		register_post_type( 'wooframework', array(
			'labels' => array(
				'name' => __( 'WooFramework Internal Container', 'woothemes' ),
			),
			'public' => true,
			'show_ui' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'title', 'editor' ),
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => false
		) );
	} // End woothemes_mlu_init()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_css */
/*
/* Add the Thickbox CSS file and specific loading and button images to the header
/* on the pages where this function is called.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_css' ) ) {
	function woothemes_mlu_css () {
		$_html = '';
		$_html .= '<link rel="stylesheet" href="' . includes_url() . 'js/thickbox/thickbox.css" type="text/css" media="screen" />' . "\n";
		$_html .= '<script type="text/javascript">
		var tb_pathToImage = "' . includes_url() . 'js/thickbox/loadingAnimation.gif";
	    var tb_closeImage = "' . includes_url() . 'js/thickbox/tb-close.png";
	    </script>' . "\n";
	    
	    echo $_html;
	} // End woothemes_mlu_css()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_js */
/*
/* Register and enqueue (load) the necessary JavaScript file for working with the
/* Media Library-driven AJAX File Uploader Module.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_js' ) ) {
	function woothemes_mlu_js () {
		// Register custom scripts for the Media Library AJAX uploader.
		wp_register_script( 'woo-medialibrary-uploader', get_template_directory_uri() . '/functions/js/woo-medialibrary-uploader.js', array( 'jquery', 'thickbox' ) );
		wp_enqueue_script( 'woo-medialibrary-uploader' );
		wp_enqueue_script( 'media-upload' );
	} // End woothemes_mlu_js()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_medialibrary_uploader */
/*
/* WooThemes Uploader Using the WordPress Media Library.
/*
/* Parameters:
/* - string $_id - A token to identify this field (the name).
/* - string $_value - The value of the field, if present.
/* - string $_mode - The display mode of the field.
/* - string $_desc - An optional description of the field.
/* - int $_postid - An optional post id (used in the meta boxes).
/*
/* Dependencies:
/* - woothemes_mlu_get_silentpost()
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_medialibrary_uploader' ) ) {
	function woothemes_medialibrary_uploader ( $_id, $_value, $_mode = 'full', $_desc = '', $_postid = 0 ) {
		$output = '';

		$id = '';
		$class = '';
		$int = '';
		$value = '';

		$id = strip_tags( strtolower( $_id ) );

		// If a post id is present, use it. Otherwise, search for one based on the $_id.
		if ( $_postid != 0 ) {
			$int = $_postid;
		} else {
			$int = woothemes_mlu_get_silentpost( $id ); // Change for each field, using a "silent" post. If no post is present, one will be created.
		}

		// If we're on a post add/edit screen, call the post meta value.
		if ( $_mode == 'postmeta' ) {
			$value = get_post_meta( $_postid, $id, true );
		} else {
			$value = get_option( $id );
		}

		// If a value is passed and we don't have a stored value, use the value that's passed through.
		if ( $_value != '' && $value == '' ) {
			$value = $_value;
		}

		if ( $value ) { $class = ' has-file'; } // End IF Statement

		// Hide the input field for "minimal" upload fields.
		$field_type = 'text';
		if ( $_mode == 'min' ) { $field_type = 'hidden'; }

		$output .= '<input type="' . $field_type . '" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $value ) . '" class="upload' . $class . '" />' . "\n";
		$output .= '<input id="upload_' . $id . '" class="upload_button button" type="button" value="' . __( 'Upload', 'woothemes' ) . '" rel="' . $int . '" />' . "\n";

		if ( $_desc != '' ) {
			$output .= '<span class="woo_metabox_desc">' . $_desc . '</span>' . "\n";
		}
		
		$output .= '<div class="screenshot" id="' . $id . '_image">' . "\n";

		if ( $value != '' ) {
			$remove = '<a href="javascript:(void);" class="mlu_remove button">Remove</a>';

			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );

			if ( $image ) {
				$output .= '<img src="' . esc_url( $value ) . '" alt="" />'.$remove.'';
			} else {
				$parts = explode( "/", $value );

				for( $i = 0; $i < sizeof( $parts ); ++$i ) {
					$title = $parts[$i];
				} // End FOR Loop

				// No output preview if it's not an image.
				$output .= '';

				// Standard generic output if it's not an image.
				$title = __( 'View File', 'woothemes' );

				$output .= '<div class="no_image"><span class="file_link"><a href="' . esc_url( $value ) . '" target="_blank" rel="external">'.$title.'</a></span>' . $remove . '</div>';

			} // End IF Statement
		} // End IF Statement

		$output .= '</div>' . "\n";

		return $output;
	} // End woothemes_medialibrary_uploader()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_get_silentpost */
/*
/* Use "silent" posts in the database to store relationships for images.
/* This also creates the facility to collect galleries of, for example, logo images.
/*
/* Return: $_postid.
/*
/* If no "silent" post is present, one will be created with the type "wooframework"
/* and the post_name of "woo-wf-$_token".
/*
/* Example Usage:
/* woothemes_mlu_get_silentpost ( 'woo_logo' );
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_get_silentpost' ) ) {
	function woothemes_mlu_get_silentpost ( $_token ) {
		global $wpdb;

		$_id = 0;

		// Check if the token is valid against a whitelist.

		// $_whitelist = array( 'woo_logo', 'woo_custom_favicon', 'woo_body_img', 'woo_ad_top_image' );

		// Sanitise the token.

		$_token = strtolower( str_replace( ' ', '_', $_token ) );

		// if ( in_array( $_token, $_whitelist ) ) {

		if ( $_token ) {
			// Tell the function what to look for in a post.
			$_args = array( 'post_parent' => '0', 'post_type' => 'wooframework', 'post_name' => 'woo-wf-' . $_token, 'post_status' => 'draft', 'comment_status' => 'closed', 'ping_status' => 'closed' );

			// Look in the database for a "silent" post that meets our criteria.
			$_posts = get_post( $_args );

			// If we've got a post, loop through and get it's ID.
			if ( count( $_posts ) ) {
				$_id = $_posts->ID;
			} else {
				// If no post is present, insert one.
				// Prepare some additional data to go with the post insertion.
				$_words = explode( '_', $_token );
				$_title = join( ' ', $_words );
				$_title = ucwords( $_title );
				$_post_data = array( 'post_title' => $_title );
				$_post_data = array_merge( $_post_data, $_args );

				$_id = wp_insert_post( $_post_data );
			} // End IF Statement
		}

		return $_id;
	} // End woothemes_mlu_get_silentpost()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_insidepopup */
/*
/* Trigger code inside the Media Library popup.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_insidepopup' ) ) {
	function woothemes_mlu_insidepopup () {
		if ( isset( $_REQUEST['is_woothemes'] ) && $_REQUEST['is_woothemes'] == 'yes' ) {
			add_action( 'admin_head', 'woothemes_mlu_js_popup' );
			add_filter( 'media_upload_tabs', 'woothemes_mlu_modify_tabs' );
		}
	} // End woothemes_mlu_insidepopup()
}

if ( ! function_exists( 'woothemes_mlu_js_popup' ) ) {
	function woothemes_mlu_js_popup () {
		$_woo_title = 'file';

		if ( isset( $_REQUEST['woo_title'] ) ) { $_woo_title = $_REQUEST['woo_title']; } // End IF Statement
?>
	<script type="text/javascript">
	<!--
	jQuery(function($) {
		jQuery.noConflict();

		// Change the title of each tab to use the custom title text instead of "Media File".
		$( 'h3.media-title' ).each ( function () {
			var current_title = $( this ).html();

			var new_title = current_title.replace( 'media file', '<?php echo $_woo_title; ?>' );

			$( this ).html( new_title )
		} );

		// Hide the "Insert Gallery" settings box on the "Gallery" tab.
		$( 'div#gallery-settings' ).hide();

		// Preserve the "is_woothemes" parameter on the "delete" confirmation button.
		$( '.savesend a.del-link' ).click ( function () {
			var continueButton = $( this ).next( '.del-attachment' ).children( 'a.button[id*="del"]' );

			var continueHref = continueButton.attr( 'href' );

			continueHref = continueHref + '&is_woothemes=yes';

			continueButton.attr( 'href', continueHref );
		} );
	});
	-->
	</script>
<?php

	} // End woothemes_mlu_js_popup()
}

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_modify_tabs */
/*
/* Triggered inside the Media Library popup to modify the title of the "Gallery" tab.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_modify_tabs' ) ) {
	function woothemes_mlu_modify_tabs ( $tabs ) {
		if ( isset( $tabs['gallery'] ) ) { $tabs['gallery'] = str_replace( __( 'Gallery', 'woothemes' ), __( 'Previously Uploaded', 'woothemes' ), $tabs['gallery'] ); }
		return $tabs;
	} // End woothemes_mlu_modify_tabs()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* woothemes_mlu_change_button_text */
/*
/* Change the "Insert Into Post" button text where appropriate.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woothemes_mlu_change_button_text' ) ) {
	function woothemes_mlu_change_button_text( $translation, $original ) {
		 // We don't pass "type" in our custom upload fields, yet WordPress does, so ignore our function when WordPress has triggered the upload popup.
	    if ( isset( $_REQUEST['type'] ) ) { return $translation; }
	    
	    if( $original == 'Insert into Post' ) {
	    	$translation = __( 'Use this Image', 'woothemes' );
			if ( isset( $_REQUEST['title'] ) && $_REQUEST['title'] != '' ) { $translation = sprintf( __( 'Use as %s', 'woothemes' ), esc_attr( $_REQUEST['title'] ) ); }
	    }
	
	    return $translation;
	} // End woothemes_mlu_change_button_text()
}
?>