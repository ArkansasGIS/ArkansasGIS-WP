<?php
include_once( WOO_CE_PATH . 'includes/products.php' );
include_once( WOO_CE_PATH . 'includes/categories.php' );
include_once( WOO_CE_PATH . 'includes/tags.php' );
include_once( WOO_CE_PATH . 'includes/brands.php' );
include_once( WOO_CE_PATH . 'includes/orders.php' );
include_once( WOO_CE_PATH . 'includes/customers.php' );
include_once( WOO_CE_PATH . 'includes/users.php' );
include_once( WOO_CE_PATH . 'includes/coupons.php' );
include_once( WOO_CE_PATH . 'includes/subscriptions.php' );
include_once( WOO_CE_PATH . 'includes/product_vendors.php' );
include_once( WOO_CE_PATH . 'includes/shipping_classes.php' );

if( version_compare( phpversion(), '5.3' ) >= 0 )
	include_once( WOO_CE_PATH . 'includes/legacy.php' );
include_once( WOO_CE_PATH . 'includes/formatting.php' );

include_once( WOO_CE_PATH . 'includes/export-csv.php' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( WOO_CE_PATH . 'includes/admin.php' );
	include_once( WOO_CE_PATH . 'includes/settings.php' );

	function woo_ce_detect_non_woo_install() {

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
		if( !woo_is_woo_activated() && ( woo_is_jigo_activated() || woo_is_wpsc_activated() ) ) {
			$message = sprintf( __( 'We have detected another e-Commerce Plugin than WooCommerce activated, please check that you are using Store Exporter Deluxe for the correct platform. <a href="%s" target="_blank">Need help?</a>', 'woo_ce' ), $troubleshooting_url );
			woo_ce_admin_notice( $message, 'error', 'plugins.php' );
		} else if( !woo_is_woo_activated() ) {
			$message = sprintf( __( 'We have been unable to detect the WooCommerce Plugin activated on this WordPress site, please check that you are using Store Exporter Deluxe for the correct platform. <a href="%s" target="_blank">Need help?</a>', 'woo_ce' ), $troubleshooting_url );
			woo_ce_admin_notice( $message, 'error', 'plugins.php' );
		}
		woo_ce_plugin_page_notices();

	}

	// Displays a HTML notice when a WordPress or Store Exporter error is encountered
	function woo_ce_fail_notices() {

		woo_ce_memory_prompt();

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
		if( isset( $_GET['failed'] ) ) {
			$message = '';
			if( isset( $_GET['message'] ) )
				$message = urldecode( $_GET['message'] );
			if( $message )
				$message = sprintf( __( 'A WordPress or server error caused the exporter to fail, the exporter was provided with a reason: <em>%s</em>', 'woo_ce' ), $message ) . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			else
				$message = __( 'A WordPress or server error caused the exporter to fail, no reason was provided, please get in touch so we can reproduce and resolve this.', 'woo_ce' ) . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			woo_ce_admin_notice_html( $message, 'error' );
		}
		if( get_transient( WOO_CE_PREFIX . '_running' ) ) {
			$message = __( 'A WordPress or server error caused the exporter to fail with a blank screen, this is either a memory or timeout issue, please get in touch so we can reproduce and resolve this.', 'woo_ce' ) . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			woo_ce_admin_notice_html( $message, 'error' );
			delete_transient( WOO_CE_PREFIX . '_running' );
		}

	}

	function woo_ce_memory_prompt() {

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';

		// Displays a HTML notice where the memory allocated to WordPress falls below 64MB
		$memory_limit = (int)( ini_get( 'memory_limit' ) );
		$minimum_memory_limit = 64;
		if( ( $memory_limit < $minimum_memory_limit ) && !woo_ce_get_option( 'dismiss_memory_prompt', 0 ) ) {
			$dismiss_url = add_query_arg( 'action', 'dismiss_memory_prompt' );
			$message = sprintf( __( 'We recommend setting memory to at least %dMB, your site has only %dMB allocated to it. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'woo_ce' ), $minimum_memory_limit, $memory_limit, $troubleshooting_url ) . '<span style="float:right;"><a href="' . $dismiss_url . '">' . __( 'Dismiss', 'woo_ce' ) . '</a></span>';
			woo_ce_admin_notice_html( $message, 'error' );
		}

		if( version_compare( phpversion(), '5.3', '<' ) && !woo_ce_get_option( 'dismiss_php_legacy', 0 ) ) {
			$dismiss_url = add_query_arg( 'action', 'dismiss_php_legacy' );
			$message = sprintf( __( 'Your PHP version (%s) is not supported and is very much out of date, since 2010 all users are strongly encouraged to upgrade to PHP 5.3+ and above. Contact your hosting provider to make this happen. See: <a href="%s" target="_blank">Migrating from PHP 5.2 to 5.3</a>', 'woo_ce' ), phpversion(), $troubleshooting_url ) . '<span style="float:right;"><a href="' . $dismiss_url . '">' . __( 'Dismiss', 'woo_ce' ) . '</a></span>';
			woo_ce_admin_notice_html( $message, 'error' );
		}

	}

	function woo_ce_plugin_page_notices() {

		global $pagenow;

		if( $pagenow == 'plugins.php' ) {
			if( woo_is_jigo_activated() || woo_is_wpsc_activated() ) {
				$r_plugins = array(
					'woocommerce-exporter/exporter.php',
					'woocommerce-store-exporter/exporter.php'
				);
				$i_plugins = get_plugins();
				foreach( $r_plugins as $path ) {
					if( isset( $i_plugins[$path] ) ) {
						add_action( 'after_plugin_row_' . $path, 'woo_ce_plugin_page_notice', 10, 3 );
						break;
					}
				}
			}
		}
	}

	function woo_ce_plugin_page_notice( $file, $data, $context ) {

		if( is_plugin_active( $file ) ) { ?>
<tr class='plugin-update-tr su-plugin-notice'>
	<td colspan='3' class='plugin-update colspanchange'>
		<div class='update-message'>
			<?php printf( __( '%1$s is intended to be used with a WooCommerce store, please check that you are using Store Exporter with the correct e-Commerce platform.', 'woo_ce' ), $data['Name'] ); ?>
		</div>
	</td>
</tr>
<?php
		}

	}

	// Saves the state of Export fields for next export
	function woo_ce_save_fields( $type = '', $fields = array(), $sorting = array() ) {

		if( $fields == false )
			$fields = array();
		$types = array_keys( woo_ce_return_export_types() );
		if( in_array( $type, $types ) && !empty( $fields ) ) {
			woo_ce_update_option( $type . '_fields', array_map( 'sanitize_text_field', $fields ) );
			woo_ce_update_option( $type . '_sorting', array_map( 'absint', $sorting ) );
		}

	}

	// Returns number of an Export type prior to export, used on Store Exporter screen
	function woo_ce_return_count( $export_type = '', $args = array() ) {

		global $wpdb;

		$count_sql = null;
		switch( $export_type ) {

			case 'product':
				$post_type = array( 'product', 'product_variation' );
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => 1,
					'fields' => 'ids'
				);
				$query = new WP_Query( $args );
				$count = $query->found_posts;
				break;

			case 'category':
				$term_taxonomy = 'product_cat';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'tag':
				$term_taxonomy = 'product_tag';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'brand':
				$term_taxonomy = apply_filters( 'woo_ce_return_count_brand', 'product_brand' );
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'order':
				$post_type = 'shop_order';
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => 1,
					'fields' => 'ids'
				);
				$query = new WP_Query( $args );
				$count = $query->found_posts;
				break;

			case 'customer':
				if( $users = woo_ce_return_count( 'user' ) > 1000 ) {
					$count = sprintf( '~%s+', 1000 );
				} else {
					$post_type = 'shop_order';
					$args = array(
						'post_type' => $post_type,
						'posts_per_page' => -1,
						'fields' => 'ids'
					);
					// Check if this is a WooCommerce 2.2+ instance (new Post Status)
					$woocommerce_version = woo_get_woo_version();
					if( version_compare( $woocommerce_version, '2.2' ) >= 0 ) {
						$args['post_status'] = apply_filters( 'woo_ce_customer_post_status', array( 'wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed' ) );
					} else {
						$args['post_status'] = apply_filters( 'woo_ce_customer_post_status', woo_ce_post_statuses() );
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'shop_order_status',
								'field' => 'slug',
								'terms' => array( 'pending', 'on-hold', 'processing', 'completed' )
							),
						);
					}
					$orders = new WP_Query( $args );
					$count = $orders->found_posts;
					if( $count > 100 ) {
						$count = sprintf( '~%s', $count );
					} else {
						$customers = array();
						if ( $orders->have_posts() ) {
							while ( $orders->have_posts() ) {
								$orders->the_post();
								$email = get_post_meta( get_the_ID(), '_billing_email', true );
								if( !in_array( $email, $customers ) ) {
									$customers[get_the_ID()] = $email;
								}
								unset( $email );
							}
							$count = count( $customers );
						}
						wp_reset_postdata();
					}
				}
/*
				if( false ) {
					$orders = get_posts( $args );
					if( $orders ) {
						$customers = array();
						foreach( $orders as $order ) {
							$order->email = get_post_meta( $order->ID, '_billing_email', true );
							if( empty( $order->email ) ) {
								if( $order->user_id = get_post_meta( $order->ID, '_customer_user', true ) ) {
									$user = get_userdata( $order->user_id );
									if( $user )
										$order->email = $user->user_email;
									unset( $user );
								} else {
									$order->email = '-';
								}
							}
							if( !in_array( $order->email, $customers ) ) {
								$customers[$order->ID] = $order->email;
								$count++;
							}
						}
						unset( $orders, $order );
					}
				}
*/
				break;

			case 'user':
				if( $users = count_users() )
					$count = $users['total_users'];
				break;

			case 'coupon':
				$post_type = 'shop_coupon';
				$count = wp_count_posts( $post_type );
				break;

			case 'subscription':
				$count = 0;
				// Check that WooCommerce Subscriptions exists
				if( class_exists( 'WC_Subscriptions_Manager' ) ) {
					// Check that the get_all_users_subscriptions() function exists
					if( method_exists( 'WC_Subscriptions_Manager', 'get_all_users_subscriptions' ) ) {
						if( $subscriptions = WC_Subscriptions_Manager::get_all_users_subscriptions() ) {
							foreach( $subscriptions as $key => $user_subscription ) {
								if( !empty( $user_subscription ) ) {
									foreach( $user_subscription as $subscription )
										$count++;
								}
							}
							unset( $subscriptions, $subscription, $user_subscription );
						}
					}
				}
				break;

			case 'product_vendor':
				$term_taxonomy = 'shop_vendor';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'shipping_class':
				$term_taxonomy = 'product_shipping_class';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'attribute':
				$attributes = ( function_exists( 'wc_get_attribute_taxonomies' ) ? wc_get_attribute_taxonomies() : array() );
				$count = count( $attributes );
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				if( is_object( $count ) ) {
					$count = (array)$count;
					$count = (int)array_sum( $count );
				}
				return $count;
			} else {
				if( $count_sql )
					$count = $wpdb->get_var( $count_sql );
				else
					$count = 0;
			}
			return $count;
		} else {
			return 0;
		}

	}

	// In-line display of export file and export details when viewed via WordPress Media screen
	function woo_ce_read_csv_file( $post = null ) {

		if( !$post ) {
			if( isset( $_GET['post'] ) )
				$post = get_post( $_GET['post'] );
		}

		if( $post->post_type != 'attachment' )
			return false;

		if( !in_array( $post->post_mime_type, array( 'text/csv', 'xml/application', 'application/vnd.ms-excel' ) ) )
			return false;

		$filename = $post->post_name;
		$filepath = get_attached_file( $post->ID );
		$contents = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
		if( file_exists( $filepath ) ) {
			$handle = fopen( $filepath, "r" );
			$contents = stream_get_contents( $handle );
			fclose( $handle );
		} else {
			// This resets the _wp_attached_file Post meta key to the correct value
			update_attached_file( $post->ID, $post->guid );
			// Try grabbing the file contents again
			$filepath = get_attached_file( $post->ID );
			if( file_exists( $filepath ) ) {
				$handle = fopen( $filepath, "r" );
				$contents = stream_get_contents( $handle );
				fclose( $handle );
			}
		}
		if( !empty( $contents ) )
			include_once( WOO_CE_PATH . 'templates/admin/media-csv_file.php' );

		$export_type = get_post_meta( $post->ID, '_woo_export_type', true );
		$columns = get_post_meta( $post->ID, '_woo_columns', true );
		$rows = get_post_meta( $post->ID, '_woo_rows', true );
		$start_time = get_post_meta( $post->ID, '_woo_start_time', true );
		$end_time = get_post_meta( $post->ID, '_woo_end_time', true );
		$idle_memory_start = get_post_meta( $post->ID, '_woo_idle_memory_start', true );
		$data_memory_start = get_post_meta( $post->ID, '_woo_data_memory_start', true );
		$data_memory_end = get_post_meta( $post->ID, '_woo_data_memory_end', true );
		$idle_memory_end = get_post_meta( $post->ID, '_woo_idle_memory_end', true );

		include_once( WOO_CE_PATH . 'templates/admin/media-export_details.php' );

	}
	add_action( 'edit_form_after_editor', 'woo_ce_read_csv_file' );

	// Returns label of Export type slug used on Store Exporter screen
	function woo_ce_export_type_label( $export_type = '', $echo = false ) {

		$output = '';
		if( !empty( $export_type ) ) {
			$export_types = woo_ce_return_export_types();
			if( array_key_exists( $export_type, $export_types ) )
				$output = $export_types[$export_type];
		}
		if( $echo )
			echo $output;
		else
			return $output;

	}

	function woo_ce_export_options_export_format() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		ob_start(); ?>
<tr>
	<th>
		<label><?php _e( 'Export format', 'woo_ce' ); ?></label>
	</th>
	<td>
		<label><input type="radio" name="export_format" value="csv"<?php checked( 'csv', 'csv' ); ?> /> <?php _e( 'CSV', 'woo_ce' ); ?> <span class="description"><?php _e( '(Comma separated values)', 'woo_ce' ); ?></span></label><br />
		<label><input type="radio" name="export_format" value="xml" disabled="disabled" /> <?php _e( 'XML', 'woo_ce' ); ?> <span class="description"><?php _e( '(EXtensible Markup Language)', 'woo_ce' ); ?> <span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label><br />
		<label><input type="radio" name="export_format" value="xls" disabled="disabled" /> <?php _e( 'Excel (XLS)', 'woo_ce' ); ?> <span class="description"><?php _e( '(Microsoft Excel 2007)', 'woo_ce' ); ?> <span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label>
		<p class="description"><?php _e( 'Adjust the export format to generate different export file formats.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	function woo_ce_export_options_gallery_format() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		ob_start(); ?>
<tr class="export-options product-options">
	<th><label for=""><?php _e( 'Product gallery formatting', 'woo_ce' ); ?></label></th>
	<td>
		<label><input type="radio" name="product_gallery_formatting" value="0"<?php checked( 0, 0 ); ?> />&nbsp;<?php _e( 'Export Product Gallery as Post ID', 'woo_ce' ); ?></label><br />
		<label><input type="radio" name="product_gallery_formatting" value="1" disabled="disabled" />&nbsp;<?php _e( 'Export Product Gallery as Image URL', 'woo_ce' ); ?> <span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label>
		<p class="description"><?php _e( 'Choose the product gallery formatting that is accepted by your WooCommerce import Plugin (e.g. Product Importer Deluxe, Product Import Suite, etc.).', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// Returns a list of archived exports
	function woo_ce_get_archive_files() {

		$post_type = 'attachment';
		$meta_key = '_woo_export_type';
		$args = array(
			'post_type' => $post_type,
			'post_mime_type' => array( 'text/csv', 'xml/application', 'application/vnd.ms-excel' ),
			'meta_key' => $meta_key,
			'meta_value' => null,
			'posts_per_page' => -1
		);
		if( isset( $_GET['filter'] ) ) {
			$filter = $_GET['filter'];
			if( !empty( $filter ) )
				$args['meta_value'] = $filter;
		}
		$files = get_posts( $args );
		return $files;

	}

	// Returns an archived export with additional details
	function woo_ce_get_archive_file( $file = '' ) {

		$wp_upload_dir = wp_upload_dir();
		$file->export_type = get_post_meta( $file->ID, '_woo_export_type', true );
		$file->export_type_label = woo_ce_export_type_label( $file->export_type );
		if( empty( $file->export_type ) )
			$file->export_type = __( 'Unassigned', 'woo_ce' );
		if( empty( $file->guid ) )
			$file->guid = $wp_upload_dir['url'] . '/' . basename( $file->post_title );
		$file->post_mime_type = get_post_mime_type( $file->ID );
		if( !$file->post_mime_type )
			$file->post_mime_type = __( 'N/A', 'woo_ce' );
		$file->media_icon = wp_get_attachment_image( $file->ID, array( 80, 60 ), true );
		if( $author_name = get_user_by( 'id', $file->post_author ) )
			$file->post_author_name = $author_name->display_name;
		$t_time = strtotime( $file->post_date, current_time( 'timestamp' ) );
		$time = get_post_time( 'G', true, $file->ID, false );
		if( ( abs( $t_diff = time() - $time ) ) < 86400 )
			$file->post_date = sprintf( __( '%s ago' ), human_time_diff( $time ) );
		else
			$file->post_date = mysql2date( __( 'Y/m/d' ), $file->post_date );
		unset( $author_name, $t_time, $time );
		return $file;

	}

	// HTML template for displaying the current export type filter on the Archives screen
	function woo_ce_archives_quicklink_current( $current = '' ) {

		$output = '';
		if( isset( $_GET['filter'] ) ) {
			$filter = $_GET['filter'];
			if( $filter == $current )
				$output = ' class="current"';
		} else if( $current == 'all' ) {
			$output = ' class="current"';
		}
		echo $output;

	}

	// HTML template for displaying the number of each export type filter on the Archives screen
	function woo_ce_archives_quicklink_count( $type = '' ) {

		$output = '0';
		$post_type = 'attachment';
		$meta_key = '_woo_export_type';
		$args = array(
			'post_type' => $post_type,
			'meta_key' => $meta_key,
			'meta_value' => null,
			'numberposts' => -1
		);
		if( $type )
			$args['meta_value'] = $type;
		if( $posts = get_posts( $args ) )
			$output = count( $posts );
		echo $output;

	}

	/* End of: WordPress Administration */

}

// Export process for CSV file
function woo_ce_export_dataset( $export_type = null, &$output = null ) {

	global $export;

	$separator = $export->delimiter;
	$export->columns = array();
	$export->total_rows = 0;
	$export->total_columns = 0;
	set_transient( WOO_CE_PREFIX . '_running', time(), woo_ce_get_option( 'timeout', MINUTE_IN_SECONDS ) );

	switch( $export_type ) {

		// Products
		case 'product':
			$fields = woo_ce_get_product_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( (array)$export->fields, $fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_product_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			if( $products = woo_ce_get_products( $export->args ) ) {
				$export->total_rows = count( $products );
				$export->total_columns = $size = count( $export->columns );
				if( in_array( $export->export_format, array( 'csv' ) ) ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				$weight_unit = get_option( 'woocommerce_weight_unit' );
				$dimension_unit = get_option( 'woocommerce_dimension_unit' );
				$height_unit = $dimension_unit;
				$width_unit = $dimension_unit;
				$length_unit = $dimension_unit;
				if( !empty( $export->fields ) ) {
					foreach( $products as $product ) {

						$product = woo_ce_get_product_data( $product, $export->args );
						foreach( $export->fields as $key => $field ) {
							if( isset( $product->$key ) ) {
								if( is_array( $field ) ) {
									foreach( $field as $array_key => $array_value ) {
										if( !is_array( $array_value ) ) {
											if( in_array( $export->export_format, array( 'csv' ) ) )
												$output .= woo_ce_escape_csv_value( $array_value, $export->delimiter, $export->escape_formatting );
										}
									}
								} else {
									if( in_array( $export->export_format, array( 'csv' ) ) )
										$output .= woo_ce_escape_csv_value( $product->$key, $export->delimiter, $export->escape_formatting );
								}
							}
							if( in_array( $export->export_format, array( 'csv' ) ) )
								$output .= $separator;
						}

						if( in_array( $export->export_format, array( 'csv' ) ) )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $products, $product );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Categories
		case 'category':
			$fields = woo_ce_get_category_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( (array)$export->fields, $fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_category_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			$category_args = array(
				'orderby' => ( isset( $export->args['category_orderby'] ) ? $export->args['category_orderby'] : 'ID' ),
				'order' => ( isset( $export->args['category_order'] ) ? $export->args['category_order'] : 'ASC' ),
			);
			if( $categories = woo_ce_get_product_categories( $category_args ) ) {
				$export->total_rows = count( $categories );
				$export->total_columns = $size = count( $export->columns );
				if( in_array( $export->export_format, array( 'csv' ) ) ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				if( !empty( $export->fields ) ) {
					foreach( $categories as $category ) {

						foreach( $export->fields as $key => $field ) {
							if( isset( $category->$key ) ) {
								if( in_array( $export->export_format, array( 'csv' ) ) )
									$output .= woo_ce_escape_csv_value( $category->$key, $export->delimiter, $export->escape_formatting );
							}
							if( in_array( $export->export_format, array( 'csv' ) ) )
								$output .= $separator;
						}
						if( in_array( $export->export_format, array( 'csv' ) ) )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $categories, $category );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Tags
		case 'tag':
			$fields = woo_ce_get_tag_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( (array)$export->fields, $fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_tag_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			$tag_args = array(
				'orderby' => ( isset( $export->args['tag_orderby'] ) ? $export->args['tag_orderby'] : 'ID' ),
				'order' => ( isset( $export->args['tag_order'] ) ? $export->args['tag_order'] : 'ASC' ),
			);
			if( $tags = woo_ce_get_product_tags( $tag_args ) ) {
				$export->total_rows = count( $tags );
				$export->total_columns = $size = count( $export->columns );
				if( in_array( $export->export_format, array( 'csv' ) ) ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				if( !empty( $export->fields ) ) {
					foreach( $tags as $tag ) {

						foreach( $export->fields as $key => $field ) {
							if( isset( $tag->$key ) ) {
								if( in_array( $export->export_format, array( 'csv' ) ) )
									$output .= woo_ce_escape_csv_value( $tag->$key, $export->delimiter, $export->escape_formatting );
							}
							if( in_array( $export->export_format, array( 'csv' ) ) )
								$output .= $separator;
						}
						if( in_array( $export->export_format, array( 'csv' ) ) )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $tags, $tag );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Users
		case 'user':
			$fields = woo_ce_get_user_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( (array)$export->fields, $fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_user_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			if( $users = woo_ce_get_users( $export->args ) ) {
				$export->total_columns = $size = count( $export->columns );
				if( in_array( $export->export_format, array( 'csv' ) ) ) {
					$i = 0;
					foreach( $export->columns as $column ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $column, $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $column, $export->delimiter, $export->escape_formatting ) . $separator;
						$i++;
					}
				}
				if( !empty( $export->fields ) ) {
					foreach( $users as $user ) {

						$user = woo_ce_get_user_data( $user, $export->args );

						foreach( $export->fields as $key => $field ) {
							if( isset( $user->$key ) ) {
								if( in_array( $export->export_format, array( 'csv' ) ) )
									$output .= woo_ce_escape_csv_value( $user->$key, $export->delimiter, $export->escape_formatting );
							}
							if( in_array( $export->export_format, array( 'csv' ) ) )
								$output .= $separator;
						}
						if( in_array( $export->export_format, array( 'csv' ) ) )
							$output = substr( $output, 0, -1 ) . "\n";

					}
				}
				unset( $users, $user );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

	}
	// Export completed successfully
	delete_transient( WOO_CE_PREFIX . '_running' );
	// Check that the export file is populated, export columns have been assigned and rows counted
	if( $output && $export->total_rows && $export->total_columns ) {
		if( in_array( $export->export_format, array( 'csv' ) ) ) {
			$output = woo_ce_file_encoding( $output );
			if( $export->export_format == 'csv' && $export->bom && ( WOO_CE_DEBUG == false ) )
				$output = "\xEF\xBB\xBF" . $output;
		}
		if( WOO_CE_DEBUG && !$export->cron )
			set_transient( WOO_CE_PREFIX . '_debug_log', base64_encode( $output ), woo_ce_get_option( 'timeout', MINUTE_IN_SECONDS ) );
		else
			return $output;
	}

}

// List of Export types used on Store Exporter screen
function woo_ce_return_export_types() {

	$types = array();
	$types['product'] = __( 'Products', 'woo_ce' );
	$types['category'] = __( 'Categories', 'woo_ce' );
	$types['tag'] = __( 'Tags', 'woo_ce' );
	$types['user'] = __( 'Users', 'woo_ce' );
	$types = apply_filters( 'woo_ce_types', $types );
	return $types;

}

// Returns the Post object of the export file saved as an attachment to the WordPress Media library
function woo_ce_save_file_attachment( $filename = '', $post_mime_type = 'text/csv' ) {

	if( !empty( $filename ) ) {
		$post_type = 'woo-export';
		$args = array(
			'post_title' => $filename,
			'post_type' => $post_type,
			'post_mime_type' => $post_mime_type
		);
		$post_ID = wp_insert_attachment( $args, $filename );
		if( is_wp_error( $post_ID ) )
			error_log( sprintf( '[store-exporter-deluxe] save_file_attachment() - $s: %s', $filename, $result->get_error_message() ) );
		else
			return $post_ID;
	}

}

// Updates the GUID of the export file attachment to match the correct file URL
function woo_ce_save_file_guid( $post_ID, $export_type, $upload_url = '' ) {

	add_post_meta( $post_ID, '_woo_export_type', $export_type );
	if( !empty( $upload_url ) ) {
		$args = array(
			'ID' => $post_ID,
			'guid' => $upload_url
		);
		wp_update_post( $args );
	}

}

// Save critical export details against the archived export
function woo_ce_save_file_details( $post_ID ) {

	global $export;

	add_post_meta( $post_ID, '_woo_start_time', $export->start_time );
	add_post_meta( $post_ID, '_woo_idle_memory_start', $export->idle_memory_start );
	add_post_meta( $post_ID, '_woo_columns', $export->total_columns );
	add_post_meta( $post_ID, '_woo_rows', $export->total_rows );
	add_post_meta( $post_ID, '_woo_data_memory_start', $export->data_memory_start );
	add_post_meta( $post_ID, '_woo_data_memory_end', $export->data_memory_end );

}

// Update detail of existing archived export
function woo_ce_update_file_detail( $post_ID, $detail, $value ) {

	if( strstr( $detail, '_woo_' ) !== false )
		update_post_meta( $post_ID, $detail, $value );

}

// Returns a list of allowed Export type statuses, can be overridden on a per-Export type basis
function woo_ce_post_statuses( $extra_status = array(), $override = false ) {

	$output = array(
		'publish',
		'pending',
		'draft',
		'future',
		'private',
		'trash'
	);
	if( $override ) {
		$output = $extra_status;
	} else {
		if( $extra_status )
			$output = array_merge( $output, $extra_status );
	}
	return $output;

}

function woo_ce_add_missing_mime_type( $mime_types = array() ) {

	// Add CSV mime type if it has been removed
	if( !isset( $mime_types['csv'] ) )
		$mime_types['csv'] = 'text/csv';
	return $mime_types;

}
add_filter( 'upload_mimes', 'woo_ce_add_missing_mime_type', 10, 1 );

if( !function_exists( 'woo_ce_sort_fields' ) ) {
	function woo_ce_sort_fields( $key ) {

		return $key;

	}
}


// Add Store Export to filter types on the WordPress Media screen
function woo_ce_add_post_mime_type( $post_mime_types = array() ) {

	$post_mime_types['text/csv'] = array( __( 'Store Exports (CSV)', 'woo_ce' ), __( 'Manage Store Exports (CSV)', 'woo_ce' ), _n_noop( 'Store Export - CSV <span class="count">(%s)</span>', 'Store Exports - CSV <span class="count">(%s)</span>' ) );
	return $post_mime_types;

}
add_filter( 'post_mime_types', 'woo_ce_add_post_mime_type' );

function woo_ce_current_memory_usage() {

	$output = '';
	if( function_exists( 'memory_get_usage' ) )
		$output = round( memory_get_usage( true ) / 1024 / 1024, 2 );
	return $output;

}

function woo_ce_get_option( $option = null, $default = false, $allow_empty = false ) {

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( WOO_CE_PREFIX . $separator . $option, $default );
		if( $allow_empty == false && $output != 0 && ( $output == false || $output == '' ) )
			$output = $default;
	}
	return $output;

}

function woo_ce_update_option( $option = null, $value = null ) {

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( WOO_CE_PREFIX . $separator . $option, $value );
	}
	return $output;

}
?>