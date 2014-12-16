<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for disabled Filter Customers by Order Status widget on Store Exporter screen
	function woo_ce_customers_filter_by_status() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$order_statuses = woo_ce_get_order_statuses();

		ob_start(); ?>
<p><label><input type="checkbox" id="customers-filters-status" /> <?php _e( 'Filter Customers by Order Status', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-customers-filters-status" class="separator">
	<ul>
<?php if( $order_statuses ) { ?>
	<?php foreach( $order_statuses as $order_status ) { ?>
		<li><label><input type="checkbox" name="customer_filter_status[<?php echo $order_status->name; ?>]" value="<?php echo $order_status->name; ?>" disabled="disabled" /> <?php echo ucfirst( $order_status->name ); ?></label></li>
	<?php } ?>
<?php } else { ?>
		<li><?php _e( 'No Order Status\'s were found.', 'jigo_ce' ); ?></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Order Status you want to filter exported Customers by. Default is to include all Order Status options.', 'woo_ce' ); ?></p>
</div>
<!-- #export-customers-filters-status -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Custom Customers widget on Store Exporter screen
	function woo_ce_customers_custom_fields() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$custom_customers = '-';

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';

		ob_start(); ?>
<form method="post" id="export-customers-custom-fields" class="export-options customer-options">
	<div id="poststuff">

		<div class="postbox" id="export-options customer-options">
			<h3 class="hndle"><?php _e( 'Custom Customer Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'To include additional custom Customer meta in the Export Customers table above fill the Customers text box then click Save Custom Fields.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label><?php _e( 'Customer meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_customers" rows="5" cols="70" disabled="disabled"><?php echo esc_textarea( $custom_customers ); ?></textarea>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
							<p class="description"><?php _e( 'Include additional custom Customer meta in your export file by adding each custom Customer meta name to a new line above.<br />For example: <code>Customer UA, Customer IP Address</code>', 'woo_ce' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="button" class="button button-disabled" value="<?php _e( 'Save Custom Fields', 'woo_ce' ); ?>" />
				</p>
				<p class="description"><?php printf( __( 'For more information on custom Customer meta consult our <a href="%s" target="_blank">online documentation</a>.', 'woo_ce' ), $troubleshooting_url ); ?></p>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->
	<input type="hidden" name="action" value="update" />
</form>
<!-- #export-customers-custom-fields -->
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Customer export columns
function woo_ce_get_customer_fields( $format = 'full' ) {

	$export_type = 'customer';

	$fields = array();
	$fields[] = array(
		'name' => 'user_id',
		'label' => __( 'User ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user_name',
		'label' => __( 'Username', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user_role',
		'label' => __( 'User Role', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_full_name',
		'label' => __( 'Billing: Full Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_first_name',
		'label' => __( 'Billing: First Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_last_name',
		'label' => __( 'Billing: Last Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_company',
		'label' => __( 'Billing: Company', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_address',
		'label' => __( 'Billing: Street Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_city',
		'label' => __( 'Billing: City', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_postcode',
		'label' => __( 'Billing: ZIP Code', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_state',
		'label' => __( 'Billing: State (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_state_full',
		'label' => __( 'Billing: State', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_country',
		'label' => __( 'Billing: Country', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_phone',
		'label' => __( 'Billing: Phone Number', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_email',
		'label' => __( 'Billing: E-mail Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_full_name',
		'label' => __( 'Shipping: Full Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_first_name',
		'label' => __( 'Shipping: First Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_last_name',
		'label' => __( 'Shipping: Last Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_company',
		'label' => __( 'Shipping: Company', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_address',
		'label' => __( 'Shipping: Street Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_city',
		'label' => __( 'Shipping: City', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_postcode',
		'label' => __( 'Shipping: ZIP Code', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_state',
		'label' => __( 'Shipping: State (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_state_full',
		'label' => __( 'Shipping: State', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_country',
		'label' => __( 'Shipping: Country (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_country_full',
		'label' => __( 'Shipping: Country', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'total_spent',
		'label' => __( 'Total Spent', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'completed_orders',
		'label' => __( 'Completed Orders', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'total_orders',
		'label' => __( 'Total Orders', 'woo_ce' )
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' )
	);
*/

	// Allow Plugin/Theme authors to add support for additional columns
	$fields = apply_filters( 'woo_ce_' . $export_type . '_fields', $fields, $export_type );

	switch( $format ) {

		case 'summary':
			$output = array();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( isset( $fields[$i] ) )
					$output[$fields[$i]['name']] = 'on';
			}
			return $output;
			break;

		case 'full':
		default:
			$sorting = woo_ce_get_option( $export_type . '_sorting', array() );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ )
				$fields[$i]['order'] = ( isset( $sorting[$fields[$i]['name']] ) ? $sorting[$fields[$i]['name']] : $i );
			usort( $fields, woo_ce_sort_fields( 'order' ) );
			return $fields;
			break;

	}

}

function woo_ce_override_customer_field_labels( $fields = array() ) {

	$labels = woo_ce_get_option( 'customer_labels', array() );
	if( !empty( $labels ) ) {
		foreach( $fields as $key => $field ) {
			if( isset( $labels[$field['name']] ) )
				$fields[$key]['label'] = $labels[$field['name']];
		}
	}
	return $fields;

}
add_filter( 'woo_ce_customer_fields', 'woo_ce_override_customer_field_labels', 11 );
?>