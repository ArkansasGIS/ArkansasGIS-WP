<?php
/*
Plugin Name: WooCommerce - Store Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into simple formatted files (e.g. CSV, XML, Excel 2007 XLS, etc.).
Version: 1.8.2
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WOO_CE_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'WOO_CE_RELPATH', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'WOO_CE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_CE_PREFIX', 'woo_ce' );

// Turn this on to enable additional debugging options at export time
define( 'WOO_CE_DEBUG', false );

// Avoid conflicts if Store Exporter Deluxe is activated
include_once( WOO_CE_PATH . 'common/common.php' );
if( defined( 'WOO_CD_PREFIX' ) == false ) {
	include_once( WOO_CE_PATH . 'includes/functions.php' );
}

function woo_ce_i18n() {

	load_plugin_textdomain( 'woo_ce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'woo_ce_i18n' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( WOO_CE_PATH . 'includes/install.php' );
	register_activation_hook( __FILE__, 'woo_ce_install' );

	// Initial scripts and export process
	function woo_ce_admin_init() {

		global $export, $wp_roles;

		// Now is the time to de-activate Store Exporter if Store Exporter Deluxe is activated
		if( defined( 'WOO_CD_PREFIX' ) ) {
			include_once( WOO_CE_PATH . 'includes/install.php' );
			woo_ce_deactivate_ce();
		}
		// Detect if WooCommerce Subscriptions Exporter is activated
		if( function_exists( 'wc_subs_exporter_admin_init' ) ) {
			$message = sprintf( __( 'We have detected a WooCommerce Plugin that is activated and known to conflict with Store Exporter, please de-activate WooCommerce Subscriptions Exporter to resolve export issues. <a href="%s" target="_blank">Need help?</a>', 'woo_ce' ), $troubleshooting_url );
			woo_cd_admin_notice( $message, 'error', array( 'plugins.php', 'admin.php' ) );
		}

		// Check that we are on the Store Exporter screen
		$page = ( isset($_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : false );
		if( $page != strtolower( WOO_CE_PREFIX ) )
			return;

		// Detect other platform versions
		woo_ce_detect_non_woo_install();

		// Add Store Exporter widgets to Export screen
		add_action( 'woo_ce_export_product_options_before_table', 'woo_ce_products_filter_by_product_category' );
		add_action( 'woo_ce_export_product_options_before_table', 'woo_ce_products_filter_by_product_tag' );
		add_action( 'woo_ce_export_product_options_before_table', 'woo_ce_products_filter_by_product_status' );
		add_action( 'woo_ce_export_product_options_before_table', 'woo_ce_products_filter_by_product_type' );
		add_action( 'woo_ce_export_product_options_before_table', 'woo_ce_products_filter_by_stock_status' );
		add_action( 'woo_ce_export_product_options_after_table', 'woo_ce_products_product_sorting' );
		add_action( 'woo_ce_export_category_options_after_table', 'woo_ce_category_order_sorting' );
		add_action( 'woo_ce_export_tag_options_after_table', 'woo_ce_tag_order_sorting' );
		add_action( 'woo_ce_export_user_options_after_table', 'woo_ce_users_user_sorting' );
		add_action( 'woo_ce_export_shipping_class_options_after_table', 'woo_ce_shipping_class_order_sorting' );
		add_action( 'woo_ce_export_options', 'woo_ce_products_upsells_formatting' );
		add_action( 'woo_ce_export_options', 'woo_ce_products_crosssells_formatting' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_products_custom_fields' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_users_custom_fields' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_customers_custom_fields' );

		// Add Store Exporter Deluxe widgets to Export screen
		add_action( 'woo_ce_export_brand_options_before_table', 'woo_ce_brands_brand_sorting' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_date' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_status' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_customer' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_user_role' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_coupon' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_product_category' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_product_tag' );
		add_action( 'woo_ce_export_order_options_after_table', 'woo_ce_orders_order_sorting' );
		add_action( 'woo_ce_export_customer_options_before_table', 'woo_ce_customers_filter_by_status' );
		add_action( 'woo_ce_export_coupon_options_before_table', 'woo_ce_coupons_coupon_sorting' );
		add_action( 'woo_ce_export_options', 'woo_ce_orders_items_formatting' );
		add_action( 'woo_ce_export_options', 'woo_ce_orders_max_order_items' );
		add_action( 'woo_ce_export_options', 'woo_ce_orders_items_types' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_orders_custom_fields' );
		add_action( 'woo_ce_export_options', 'woo_ce_export_options_export_format' );
		add_action( 'woo_ce_export_options', 'woo_ce_export_options_gallery_format' );

		// Add Store Exporter Deluxe options to Settings screen
		add_action( 'woo_ce_export_settings_top', 'woo_ce_export_settings_quicklinks' );
		add_action( 'woo_ce_export_settings_general', 'woo_ce_export_settings_additional' );
		add_action( 'woo_ce_export_settings_after', 'woo_ce_export_settings_cron' );

		$action = woo_get_action();
		switch( $action ) {

			// Prompt on Export screen when insufficient memory (less than 64M is allocated)
			case 'dismiss_memory_prompt':
				woo_ce_update_option( 'dismiss_memory_prompt', 1 );
				$url = add_query_arg( 'action', null );
				wp_redirect( $url );
				exit();
				break;

			// Prompt on Export screen when insufficient memory (less than 64M is allocated)
			case 'dismiss_php_legacy':
				woo_ce_update_option( 'dismiss_php_legacy', 1 );
				$url = add_query_arg( 'action', null );
				wp_redirect( $url );
				exit();
				break;

			// Save skip overview preference
			case 'skip_overview':
				$skip_overview = false;
				if( isset( $_POST['skip_overview'] ) )
					$skip_overview = 1;
				woo_ce_update_option( 'skip_overview', $skip_overview );

				if( $skip_overview == 1 ) {
					$url = add_query_arg( 'tab', 'export' );
					wp_redirect( $url );
					exit();
				}
				break;

			// This is where the magic happens
			case 'export':

				// Set up the basic export options
				$export = new stdClass();
				$export->cron = 0;
				$export->start_time = time();
				$export->idle_memory_start = woo_ce_current_memory_usage();
				$export->delete_file = woo_ce_get_option( 'delete_file', 0 );
				$export->encoding = woo_ce_get_option( 'encoding', get_option( 'blog_charset', 'UTF-8' ) );
				// Reset the Encoding if corrupted
				if( $export->encoding == '' || $export->encoding == false || $export->encoding == 'System default' ) {
					$export->encoding = 'UTF-8';
					woo_ce_update_option( 'encoding', 'UTF-8' );
				}
				$export->delimiter = woo_ce_get_option( 'delimiter', ',' );
				// Reset the Delimiter if corrupted
				if( $export->delimiter == '' || $export->delimiter == false ) {
					$export->delimiter = ',';
					woo_ce_update_option( 'delimiter', ',' );
				}
				$export->category_separator = woo_ce_get_option( 'category_separator', '|' );
				// Reset the Category Separator if corrupted
				if( $export->category_separator == '' || $export->category_separator == false ) {
					$export->category_separator = '|';
					woo_ce_update_option( 'category_separator', '|' );
				}
				$export->bom = woo_ce_get_option( 'bom', 1 );
				$export->escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
				// Reset the Escape Formatting if corrupted
				if( $export->escape_formatting == '' || $export->escape_formatting == false ) {
					$export->escape_formatting = 'all';
					woo_ce_update_option( 'escape_formatting', 'all' );
				}
				$export->date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );
				// Reset the Date Format if corrupted
				if( $export->date_format == '1' || $export->date_format == '' || $export->date_format == false ) {
					$export->date_format = 'd/m/Y';
					woo_ce_update_option( 'date_format', 'd/m/Y' );
				}

				// Save export option changes made on the Export screen
				$export->limit_volume = ( isset( $_POST['limit_volume'] ) ? sanitize_text_field( $_POST['limit_volume'] ) : '' );
				woo_ce_update_option( 'limit_volume', $export->limit_volume );
				if( $export->limit_volume == '' )
					$export->limit_volume = -1;
				$export->offset = ( isset( $_POST['offset'] ) ? sanitize_text_field( $_POST['offset'] ) : '' );
				woo_ce_update_option( 'offset', $export->offset );
				if( $export->offset == '' )
					$export->offset = 0;

				// Set default values for all export options to be later passed onto the export process
				$export->fields = false;
				$export->fields_order = false;
				$export->export_format = 'csv';

				// Product sorting
				$export->product_categories = false;
				$export->product_tags = false;
				$export->product_status = false;
				$export->product_type = false;
				$export->product_orderby = false;
				$export->product_order = false;
				$export->upsell_formatting = false;
				$export->crosssell_formatting = false;

				// Category sorting
				$export->category_orderby = false;
				$export->category_order = false;

				// Tag sorting
				$export->tag_orderby = false;
				$export->tag_order = false;

				// User sorting
				$export->user_orderby = false;
				$export->user_order = false;

				$export->type = ( isset( $_POST['dataset'] ) ? sanitize_text_field( $_POST['dataset'] ) : false );
				if( $export->type )
					woo_ce_update_option( 'last_export', $export->type );
				switch( $export->type ) {

					case 'product':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['product_fields'] ) ? array_map( 'sanitize_text_field', $_POST['product_fields'] ) : false );
						$export->fields_order = ( isset( $_POST['product_fields_order'] ) ? array_map( 'absint', $_POST['product_fields_order'] ) : false );
						$export->product_categories = ( isset( $_POST['product_filter_category'] ) ? woo_ce_format_product_filters( array_map( 'absint', $_POST['product_filter_category'] ) ) : false );
						$export->product_tags = ( isset( $_POST['product_filter_tag'] ) ? woo_ce_format_product_filters( array_map( 'absint', $_POST['product_filter_tag'] ) ) : false );
						$export->product_status = ( isset( $_POST['product_filter_status'] ) ? woo_ce_format_product_filters( array_map( 'sanitize_text_field', $_POST['product_filter_status'] ) ) : false );
						$export->product_type = ( isset( $_POST['product_filter_type'] ) ? woo_ce_format_product_filters( array_map( 'sanitize_text_field', $_POST['product_filter_type'] ) ) : false );
						$export->product_orderby = ( isset( $_POST['product_orderby'] ) ? sanitize_text_field( $_POST['product_orderby'] ) : false );
						$export->product_order = ( isset( $_POST['product_order'] ) ? sanitize_text_field( $_POST['product_order'] ) : false );
						$export->upsell_formatting = ( isset( $_POST['product_upsell_formatting'] ) ? absint( $_POST['product_upsell_formatting'] ) : false );
						$export->crosssell_formatting = ( isset( $_POST['product_crosssell_formatting'] ) ? absint( $_POST['product_crosssell_formatting'] ) : false );

						// Save dataset export specific options
						if( $export->product_orderby <> woo_ce_get_option( 'product_orderby' ) )
							woo_ce_update_option( 'product_orderby', $export->product_orderby );
						if( $export->product_order <> woo_ce_get_option( 'product_order' ) )
							woo_ce_update_option( 'product_order', $export->product_order );
						if( $export->upsell_formatting <> woo_ce_get_option( 'upsell_formatting' ) )
							woo_ce_update_option( 'upsell_formatting', $export->upsell_formatting );
						if( $export->crosssell_formatting <> woo_ce_get_option( 'crosssell_formatting' ) )
							woo_ce_update_option( 'crosssell_formatting', $export->crosssell_formatting );
						break;

					case 'category':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['category_fields'] ) ? array_map( 'sanitize_text_field', $_POST['category_fields'] ) : false );
						$export->fields_order = ( isset( $_POST['category_fields_order'] ) ? array_map( 'absint', $_POST['category_fields_order'] ) : false );
						$export->category_orderby = ( isset( $_POST['category_orderby'] ) ? sanitize_text_field( $_POST['category_orderby'] ) : false );
						$export->category_order = ( isset( $_POST['category_order'] ) ? sanitize_text_field( $_POST['category_order'] ) : false );

						// Save dataset export specific options
						if( $export->category_orderby <> woo_ce_get_option( 'category_orderby' ) )
							woo_ce_update_option( 'category_orderby', $export->category_orderby );
						if( $export->category_order <> woo_ce_get_option( 'category_order' ) )
							woo_ce_update_option( 'category_order', $export->category_order );
						break;

					case 'tag':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['tag_fields'] ) ? array_map( 'sanitize_text_field', $_POST['tag_fields'] ) : false );
						$export->fields_order = ( isset( $_POST['tag_fields_order'] ) ? array_map( 'absint', $_POST['tag_fields_order'] ) : false );
						$export->tag_orderby = ( isset( $_POST['tag_orderby'] ) ? sanitize_text_field( $_POST['tag_orderby'] ) : false );
						$export->tag_order = ( isset( $_POST['tag_order'] ) ? sanitize_text_field( $_POST['tag_order'] ) : false );

						// Save dataset export specific options
						if( $export->tag_orderby <> woo_ce_get_option( 'tag_orderby' ) )
							woo_ce_update_option( 'tag_orderby', $export->tag_orderby );
						if( $export->tag_order <> woo_ce_get_option( 'tag_order' ) )
							woo_ce_update_option( 'tag_order', $export->tag_order );
						break;

					case 'user':
						// Set up dataset specific options
						$export->fields = array_map( 'sanitize_text_field', $_POST['user_fields'] );
						$export->fields_order = ( isset( $_POST['user_fields_order'] ) ? array_map( 'absint', $_POST['user_fields_order'] ) : false );
						$export->user_orderby = ( isset( $_POST['user_orderby'] ) ? sanitize_text_field( $_POST['user_orderby'] ) : false );
						$export->user_order = ( isset( $_POST['user_order'] ) ? sanitize_text_field( $_POST['user_order'] ) : false );

						// Save dataset export specific options
						if( $export->user_orderby <> woo_ce_get_option( 'user_orderby' ) )
							woo_ce_update_option( 'user_orderby', $export->user_orderby );
						if( $export->user_order <> woo_ce_get_option( 'user_order' ) )
							woo_ce_update_option( 'user_order', $export->user_order );
						break;

				}
				if( $export->type ) {

					$timeout = 600;
					if( isset( $_POST['timeout'] ) ) {
						$timeout = absint( (int)$_POST['timeout'] );
						if( $timeout <> woo_ce_get_option( 'timeout' ) )
							woo_ce_update_option( 'timeout', $timeout );
					}
					if( !ini_get( 'safe_mode' ) )
						@set_time_limit( (int)$timeout );

					@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT );
					@ini_set( 'max_execution_time', (int)$timeout );

					$export->args = array(
						'limit_volume' => $export->limit_volume,
						'offset' => $export->offset,
						'encoding' => $export->encoding,
						'date_format' => $export->date_format,
						'product_categories' => $export->product_categories,
						'product_tags' => $export->product_tags,
						'product_status' => $export->product_status,
						'product_type' => $export->product_type,
						'product_orderby' => $export->product_orderby,
						'product_order' => $export->product_order,
						'category_orderby' => $export->category_orderby,
						'category_order' => $export->category_order,
						'tag_orderby' => $export->tag_orderby,
						'tag_order' => $export->tag_order,
						'user_orderby' => $export->user_orderby,
						'user_order' => $export->user_order
					);
					woo_ce_save_fields( $export->type, $export->fields, $export->fields_order );

					if( $export->export_format == 'csv' ) {
						$export->filename = woo_ce_generate_csv_filename( $export->type );
					}

					// Print file contents to debug export screen
					if( WOO_CE_DEBUG ) {

						if( in_array( $export->export_format, array( 'csv' ) ) ) {
							woo_ce_export_dataset( $export->type );
						}
						$export->idle_memory_end = woo_ce_current_memory_usage();
						$export->end_time = time();

					// Print file contents to browser
					} else {
						if( in_array( $export->export_format, array( 'csv' ) ) ) {

							// Generate CSV contents
							$bits = woo_ce_export_dataset( $export->type );
							unset( $export->fields );
							if( !$bits ) {
								$message = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
								woo_ce_admin_notice( $message, 'error' );
								return;
							}
							if( $export->delete_file ) {

								// Print to browser
								if( $export->export_format == 'csv' )
									woo_ce_generate_csv_header( $export->type );
								echo $bits;
								exit();

							} else {

								// Save to file and insert to WordPress Media
								if( $export->filename && $bits ) {
									if( $export->export_format == 'csv' )
										$post_ID = woo_ce_save_file_attachment( $export->filename, 'text/csv' );
									$upload = wp_upload_bits( $export->filename, null, $bits );
									if( ( $post_ID == false ) || $upload['error'] ) {
										wp_delete_attachment( $post_ID, true );
										if( isset( $upload['error'] ) )
											wp_redirect( add_query_arg( array( 'failed' => true, 'message' => urlencode( $upload['error'] ) ) ) );
										else
											wp_redirect( add_query_arg( array( 'failed' => true ) ) );
										return;
									}
									$attach_data = wp_generate_attachment_metadata( $post_ID, $upload['file'] );
									wp_update_attachment_metadata( $post_ID, $attach_data );
									update_attached_file( $post_ID, $upload['file'] );
									if( $post_ID ) {
										woo_ce_save_file_guid( $post_ID, $export->type, $upload['url'] );
										woo_ce_save_file_details( $post_ID );
									}
									$export_type = $export->type;
									unset( $export );

									// The end memory usage and time is collected at the very last opportunity prior to the CSV header being rendered to the screen
									woo_ce_update_file_detail( $post_ID, '_woo_idle_memory_end', woo_ce_current_memory_usage() );
									woo_ce_update_file_detail( $post_ID, '_woo_end_time', time() );

									// Generate CSV header
									woo_ce_generate_csv_header( $export_type );
									unset( $export_type );

									// Print file contents to screen
									if( $upload['file'] )
										readfile( $upload['file'] );
									else
										wp_redirect( add_query_arg( 'failed', true ) );
									unset( $upload );
								} else {
									wp_redirect( add_query_arg( 'failed', true ) );
								}

							}

						}
						exit();
					}
				}
				break;

			// Save changes on Settings screen
			case 'save-settings':
				// Sanitize each setting field as needed
				woo_ce_update_option( 'export_filename', strip_tags( (string)$_POST['export_filename'] ) );
				woo_ce_update_option( 'delete_file', sanitize_text_field( (int)$_POST['delete_file'] ) );
				woo_ce_update_option( 'encoding', sanitize_text_field( (string)$_POST['encoding'] ) );
				woo_ce_update_option( 'delimiter', sanitize_text_field( (string)$_POST['delimiter'] ) );
				woo_ce_update_option( 'category_separator', sanitize_text_field( (string)$_POST['category_separator'] ) );
				woo_ce_update_option( 'bom', absint( (int)$_POST['bom'] ) );
				woo_ce_update_option( 'escape_formatting', sanitize_text_field( (string)$_POST['escape_formatting'] ) );
				if( $_POST['date_format'] == 'custom' && !empty( $_POST['date_format_custom'] ) )
					woo_ce_update_option( 'date_format', sanitize_text_field( (string)$_POST['date_format_custom'] ) );
				else
					woo_ce_update_option( 'date_format', sanitize_text_field( (string)$_POST['date_format'] ) );

				$message = __( 'Changes have been saved.', 'woo_ce' );
				woo_ce_admin_notice( $message );
				break;

			// Save changes on Field Editor screen
			case 'save-fields':
				$fields = ( isset( $_POST['fields'] ) ? array_filter( $_POST['fields'] ) : array() );
				$types = array_keys( woo_ce_return_export_types() );
				$export_type = ( isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '' );
				if( in_array( $export_type, $types ) ) {
					woo_ce_update_option( $export_type . '_labels', $fields );
					$message = __( 'Changes have been saved.', 'woo_ce' );
					woo_ce_admin_notice( $message );
				} else {
					$message = __( 'Changes could not be saved.', 'woo_ce' );
					woo_ce_admin_notice( $message, 'error' );
				}
				break;

		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init', 11 );

	// HTML templates and form processor for Store Exporter screen
	function woo_ce_html_page() {

		global $wpdb, $export;

		$title = apply_filters( 'woo_ce_template_header', __( 'Store Exporter', 'woo_ce' ) );
		woo_ce_template_header( $title );
		woo_ce_support_donate();
		$action = woo_get_action();
		switch( $action ) {

			case 'export':
				$message = __( 'Chosen WooCommerce details have been exported from your store.', 'woo_ce' );
				woo_ce_admin_notice( $message );
				if( WOO_CE_DEBUG ) {
					$output = '';
					$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
					if( false === ( $export_log = get_transient( WOO_CE_PREFIX . '_debug_log' ) ) ) {
						$export_log = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
					} else {
						$export_log = base64_decode( $export_log );
						delete_transient( WOO_CE_PREFIX . '_debug_log' );
					}
					$output = '
<h3>' . sprintf( __( 'Export Details: %s', 'woo_ce' ), esc_attr( $export->filename ) ) . '</h3>
<p>' . __( 'This prints the $export global that contains the different export options and filters to help reproduce this on another instance of WordPress. Very useful for debugging blank or unexpected exports.', 'woo_ce' ) . '</p>
<textarea id="export_log">' . esc_textarea( print_r( $export, true ) ) . '</textarea>
<hr />';
					if( in_array( $export->export_format, array( 'csv' ) ) ) {
						$output .= '
<script>
	$j(function() {
		$j(\'#export_sheet\').CSVToTable(\'\', { startLine: 0 });
	});
</script>
<h3>' . __( 'Export', 'woo_ce' ) . '</h3>
<p>' . __( 'We use the <a href="http://code.google.com/p/jquerycsvtotable/" target="_blank"><em>CSV to Table plugin</em></a> to see first hand formatting errors or unexpected values within the export file.', 'woo_ce' ) . '</p>
<div id="export_sheet">' . esc_textarea( $export_log ) . '</div>
<p class="description">' . __( 'This jQuery plugin can fail with <code>\'Item count (#) does not match header count\'</code> notices which simply mean the number of headers detected does not match the number of cell contents.', 'woo_ce' ) . '</p>
<hr />';
					}
					$output .= '
<h3>' . __( 'Export Log', 'woo_ce' ) . '</h3>
<p>' . __( 'This prints the raw export contents and is helpful when the jQuery plugin above fails due to major formatting errors.', 'woo_ce' ) . '</p>
<textarea id="export_log" wrap="off">' . esc_textarea( $export_log ) . '</textarea>
<hr />
';
					echo $output;
				}

				woo_ce_manage_form();
				break;

			case 'update':
				// Save Custom Product Meta
				if( isset( $_POST['custom_products'] ) ) {
					$custom_products = $_POST['custom_products'];
					$custom_products = explode( "\n", trim( $custom_products ) );
					$size = count( $custom_products );
					if( $size ) {
						for( $i = 0; $i < $size; $i++ )
							$custom_products[$i] = sanitize_text_field( trim( $custom_products[$i] ) );
						woo_ce_update_option( 'custom_products', $custom_products );
					}
				}

				$message = __( 'Custom Fields saved.', 'woo_ce' );
				woo_ce_admin_notice_html( $message );
				woo_ce_manage_form();
				break;

			default:
				woo_ce_manage_form();
				break;

		}
		woo_ce_template_footer();

	}

	// HTML template for Export screen
	function woo_ce_manage_form() {

		$tab = false;
		if( isset( $_GET['tab'] ) ) {
			$tab = sanitize_text_field( $_GET['tab'] );
		// If Skip Overview is set then jump to Export screen
		} else if( woo_ce_get_option( 'skip_overview', false ) ) {
			$tab = 'export';
		}
		$url = add_query_arg( 'page', 'woo_ce' );
		woo_ce_fail_notices();

		include_once( WOO_CE_PATH . 'templates/admin/tabs.php' );

	}

	/* End of: WordPress Administration */

}
?>