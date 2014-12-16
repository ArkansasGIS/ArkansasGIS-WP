<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for disabled Filter Orders by Date widget on Store Exporter screen
	function woo_ce_orders_filter_by_date() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$current_month = date( 'F' );
		$last_month = date( 'F', mktime( 0, 0, 0, date( 'n' )-1, 1, date( 'Y' ) ) );
		$order_dates_from = '-';
		$order_dates_to = '-';

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-date" /> <?php _e( 'Filter Orders by Order Date', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-date" class="separator">
	<ul>
		<li>
			<label><input type="radio" name="order_dates_filter" value="current_month" disabled="disabled" /> <?php _e( 'Current month', 'woo_ce' ); ?> (<?php echo $current_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="last_month" disabled="disabled" /> <?php _e( 'Last month', 'woo_ce' ); ?> (<?php echo $last_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="last_quarter" disabled="disabled" /> <?php _e( 'Last quarter', 'woo_ce' ); ?> (Nov. - Jan.)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="manual" disabled="disabled" /> <?php _e( 'Manual', 'woo_ce' ); ?></label>
			<div style="margin-top:0.2em;">
				<input type="text" size="10" maxlength="10" id="order_dates_from" name="order_dates_from" value="<?php echo esc_attr( $order_dates_from ); ?>" class="text" disabled="disabled" /> to <input type="text" size="10" maxlength="10" id="order_dates_to" name="order_dates_to" value="<?php echo esc_attr( $order_dates_to ); ?>" class="text" disabled="disabled" />
				<p class="description"><?php _e( 'Filter the dates of Orders to be included in the export. Default is the date of the first order to today.', 'woo_ce' ); ?></p>
			</div>
		</li>
	</ul>
</div>
<!-- #export-orders-filters-date -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Customer widget on Store Exporter screen
	function woo_ce_orders_filter_by_customer() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-customer" /> <?php _e( 'Filter Orders by Customer', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-customer" class="separator">
	<select id="order_customer" name="order_customer" disabled="disabled">
		<option value=""><?php _e( 'Show all customers', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Filter Orders by Customer (unique e-mail address) to be included in the export. Default is to include all Orders.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-customer -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by User Role widget on Store Exporter screen
	function woo_ce_orders_filter_by_user_role() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$user_roles = woo_ce_get_user_roles();

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-user_role" /> <?php _e( 'Filter Orders by User Role', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-user_role" class="separator">
	<ul>
<?php if( $user_roles ) { ?>
	<?php foreach( $user_roles as $key => $user_role ) { ?>
		<li><label><input type="checkbox" name="order_filter_user_role[<?php echo $key; ?>]" value="<?php echo $key; ?>" disabled="disabled" /> <?php echo ucfirst( $user_role['name'] ); ?></label></li>
	<?php } ?>
<?php } else { ?>
		<li><?php _e( 'No User Roles were found.', 'woo_ce' ); ?></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the User Roles you want to filter exported Orders by. Default is to include all User Role options.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-user_role -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Coupon Code widget on Store Exporter screen
	function woo_ce_orders_filter_by_coupon() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$args = array(
			'coupon_orderby' => 'ID',
			'coupon_order' => 'DESC'
		);
		$coupons = woo_ce_get_coupons( $args );

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-coupon" /> <?php _e( 'Filter Orders by Coupon Code', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-coupon" class="separator">
	<ul>
<?php foreach( $coupons as $key => $coupon ) { ?>
		<li><label><input type="checkbox" name="order_filter_coupon[<?php echo $key; ?>]" disabled="disabled" /> <?php echo get_the_title( $coupon ); ?></label></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Coupon Codes you want to filter exported Orders by. Default is to include all Orders with and without assigned Coupon Codes.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-coupon -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Order Items Formatting on Store Exporter screen
	function woo_ce_orders_items_formatting() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		ob_start(); ?>
<tr class="export-options order-options">
	<th><label for="order_items"><?php _e( 'Order items formatting', 'woo_ce' ); ?></label></th>
	<td>
		<ul>
			<li>
				<label><input type="radio" name="order_items" value="combined" disabled="disabled" />&nbsp;<?php _e( 'Place Order Items within a grouped single Order row', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label>
				<p class="description"><?php _e( 'For example: <code>Order Items: SKU</code> cell might contain <code>SPECK-IPHONE|INCASE-NANO|-</code> for 3 Order items within an Order', 'woo_ce' ); ?></p>
			</li>
			<li>
				<label><input type="radio" name="order_items" value="unique" disabled="disabled" />&nbsp;<?php _e( 'Place Order Items on individual cells within a single Order row', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label>
				<p class="description"><?php _e( 'For example: <code>Order Items: SKU</code> would become <code>Order Item #1: SKU</code> with <codeSPECK-IPHONE</code> for the first Order item within an Order', 'woo_ce' ); ?></p>
			</li>
			<li>
				<label><input type="radio" name="order_items" value="individual" disabled="disabled" />&nbsp;<?php _e( 'Place each Order Item within their own Order row', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label>
				<p class="description"><?php _e( 'For example: An Order with 3 Order items will display a single Order item on each row', 'woo_ce' ); ?></p>
			</li>
		</ul>
		<p class="description"><?php _e( 'Choose how you would like Order Items to be presented within Orders.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Max Order Items widget on Store Exporter screen
	function woo_ce_orders_max_order_items() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$max_size = 10;

		ob_start(); ?>
<tr id="max_order_items_option" class="export-options order-options">
	<th>
		<label for="max_order_items"><?php _e( 'Max unique Order items', 'woo_ce' ); ?>: </label>
	</th>
	<td>
		<input type="text" id="max_order_items" name="max_order_items" size="3" class="text" value="<?php echo esc_attr( $max_size ); ?>" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Manage the number of Order Item colums displayed when the \'Place Order Items on individual cells within a single Order row\' Order items formatting option is selected.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Order Items Types on Store Exporter screen
	function woo_ce_orders_items_types() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$types = woo_ce_get_order_items_types();
		$order_items_types = woo_ce_get_option( 'order_items_types', array() );

		ob_start(); ?>
<tr class="export-options order-options">
	<th><label><?php _e( 'Order items types', 'woo_ce' ); ?></label></th>
	<td>
		<ul>
<?php foreach( $types as $key => $type ) { ?>
			<li><label><input type="checkbox" name="order_filter_order_item_types[<?php echo $key; ?>]" value="<?php echo $key; ?>" disabled="disabled" /> <?php echo ucfirst( $type ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
<?php } ?>
		</ul>
		<p class="description"><?php _e( 'Choose what Order Item types are included within the Orders export. Default is to include all Order Item types.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Order Status widget on Store Exporter screen
	function woo_ce_orders_filter_by_status() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$order_statuses = woo_ce_get_order_statuses();

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-status" /> <?php _e( 'Filter Orders by Order Status', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-status" class="separator">
	<ul>
<?php if( $order_statuses ) { ?>
	<?php foreach( $order_statuses as $order_status ) { ?>
		<li>
			<label><input type="checkbox" name="order_filter_status[<?php echo $order_status->slug; ?>]" value="<?php echo $order_status->slug; ?>" disabled="disabled" /> <?php echo ucfirst( $order_status->name ); ?></label>
			<span class="description">(<?php echo $order_status->count; ?>)</span>
		</li>
	<?php } ?>
<?php } else { ?>
		<li><?php _e( 'No Order Status\'s were found.', 'woo_ce' ); ?></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Order Status you want to filter exported Orders by. Default is to include all Order Status options.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-status -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Product Category widget on Store Exporter screen
	function woo_ce_orders_filter_by_product_category() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$args = array(
			'hide_empty' => 1
		);
		$product_categories = woo_ce_get_product_categories( $args );

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-category" /> <?php _e( 'Filter Orders by Product Category', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-category" class="separator">
	<ul>
<?php if( $product_categories ) { ?>
	<?php foreach( $product_categories as $product_category ) { ?>
		<li>
			<label><input type="checkbox" name="order_filter_category[<?php echo $product_category->name; ?>]" value="<?php echo $product_category->term_id; ?>" title="<?php printf( __( 'Term ID: %d', 'woo_ce' ), $product_category->term_id ); ?>" disabled="disabled" /> <?php echo woo_ce_format_product_category_label( $product_category->name, $product_category->parent_name ); ?></label>
		</li>
	<?php } ?>
<?php } else { ?>
		<li><?php _e( 'No Product Categories were found.', 'woo_ce' ); ?></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Product Categories you want to filter exported Orders by. Default is to include all Product Categories.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-category -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Product Tag widget on Store Exporter screen
	function woo_ce_orders_filter_by_product_tag() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$args = array(
			'hide_empty' => 1
		);
		$product_tags = woo_ce_get_product_tags( $args );

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-tag" /> <?php _e( 'Filter Orders by Product Tag', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></p>
<div id="export-orders-filters-tag" class="separator">
	<ul>
<?php if( $product_tags ) { ?>
	<?php foreach( $product_tags as $product_tag ) { ?>
		<li>
			<label><input type="checkbox" name="order_filter_tag[<?php echo $product_tag->name; ?>]" value="<?php echo $product_tag->name; ?>" title="<?php printf( __( 'Term ID: %d', 'woo_ce' ), $product_tag->term_id ); ?>" disabled="disabled" /> <?php echo $product_tag->name; ?></label>
			<span class="description">(<?php echo $product_tag->count; ?>)</span>
		</li>
	<?php } ?>
<?php } else { ?>
		<li><?php _e( 'No Product Tags have been found.', 'jigo_ce' ); ?></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Product Tags you want to filter exported Orders by. Default is to include all Product Tags.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-tag -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Order Sorting widget on Store Exporter screen
	function woo_ce_orders_order_sorting() {

		ob_start(); ?>
<p><label><?php _e( 'Order Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="order_orderby" disabled="disabled">
		<option value="ID"><?php _e( 'Order ID', 'woo_ce' ); ?></option>
		<option value="title"><?php _e( 'Order Name', 'woo_ce' ); ?></option>
		<option value="date"><?php _e( 'Date Created', 'woo_ce' ); ?></option>
		<option value="modified"><?php _e( 'Date Modified', 'woo_ce' ); ?></option>
		<option value="rand"><?php _e( 'Random', 'woo_ce' ); ?></option>
	</select>
	<select name="order_order" disabled="disabled">
		<option value="ASC"><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Orders within the exported file. By default this is set to export Orders by Order ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Custom Orders widget on Store Exporter screen
	function woo_ce_orders_custom_fields() {

		$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
		$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

		$custom_orders = '-';
		$custom_order_items = '-';

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';

		ob_start(); ?>
<form method="post" id="export-orders-custom-fields" class="export-options order-options">
	<div id="poststuff">

		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Custom Order Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'To include additional custom Order and Order Item meta in the Export Orders table above fill the Orders and Order Items text box then click Save Custom Fields.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label><?php _e( 'Order meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_orders" rows="5" cols="70" disabled="disabled"><?php echo esc_textarea( $custom_orders ); ?></textarea>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
							<p class="description"><?php _e( 'Include additional custom Order meta in your export file by adding each custom Order meta name to a new line above.<br />For example: <code>Customer UA, Customer IP Address</code>', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label><?php _e( 'Order Item meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_order_items" rows="5" cols="70" disabled="disabled"><?php echo esc_textarea( $custom_order_items ); ?></textarea>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
							<p class="description"><?php _e( 'Include additional custom Order Item meta in your export file by adding each custom Order Item meta name to a new line above.<br />For example: <code>Personalized Message</code>.', 'woo_ce' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="button" class="button button-disabled" value="<?php _e( 'Save Custom Fields', 'woo_ce' ); ?>" />
				</p>
				<p class="description"><?php printf( __( 'For more information on custom Order and Order Item meta consult our <a href="%s" target="_blank">online documentation</a>.', 'woo_ce' ), $troubleshooting_url ); ?></p>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->
	<input type="hidden" name="action" value="update" />
</form>
<!-- #export-orders-custom-fields -->
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Order export columns
function woo_ce_get_order_fields( $format = 'full' ) {

	$export_type = 'order';

	$fields = array();
	$fields[] = array(
		'name' => 'purchase_id',
		'label' => __( 'Purchase ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_total',
		'label' => __( 'Order Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_discount',
		'label' => __( 'Order Discount', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'coupon_code',
		'label' => __( 'Coupon Code', 'woo_ce' )
	);
/*
	$fields[] = array(
		'name' => 'order_incl_tax',
		'label' => __( 'Order Incl. Tax', 'woo_ce' )
	);
*/
	$fields[] = array(
		'name' => 'order_excl_tax',
		'label' => __( 'Order Excl. Tax', 'woo_ce' )
	);
/*
	$fields[] = array(
		'name' => 'order_tax_rate',
		'label' => __( 'Order Tax Rate', 'woo_ce' )
	);
*/
	$fields[] = array(
		'name' => 'order_sales_tax',
		'label' => __( 'Sales Tax Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_shipping_tax',
		'label' => __( 'Shipping Tax Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_gateway_id',
		'label' => __( 'Payment Gateway ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_gateway',
		'label' => __( 'Payment Gateway', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_method_id',
		'label' => __( 'Shipping Method ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_method',
		'label' => __( 'Shipping Method', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_cost',
		'label' => __( 'Shipping Cost', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_weight',
		'label' => __( 'Shipping Weight', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_status',
		'label' => __( 'Order Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'post_status',
		'label' => __( 'Post Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_key',
		'label' => __( 'Order Key', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_date',
		'label' => __( 'Purchase Date', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_time',
		'label' => __( 'Purchase Time', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'customer_message',
		'label' => __( 'Customer Message', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'customer_note',
		'label' => __( 'Customer Note', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_notes',
		'label' => __( 'Order Notes', 'woo_ce' )
	);
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
		'name' => 'ip_address',
		'label' => __( 'Checkout IP Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'browser_agent',
		'label' => __( 'Checkout Browser Agent', 'woo_ce' )
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
		'label' => __( 'Billing: Street Address (Full)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_address_1',
		'label' => __( 'Billing: Street Address 1', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_address_2',
		'label' => __( 'Billing: Street Address 2', 'woo_ce' )
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
		'label' => __( 'Billing: Country (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_country_full',
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
		'label' => __( 'Shipping: Street Address (Full)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_address_1',
		'label' => __( 'Shipping: Street Address 1', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_address_2',
		'label' => __( 'Shipping: Street Address 2', 'woo_ce' )
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
		'name' => 'order_items_product_id',
		'label' => __( 'Order Items: Product ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_variation_id',
		'label' => __( 'Order Items: Variation ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_sku',
		'label' => __( 'Order Items: SKU', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_name',
		'label' => __( 'Order Items: Product Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_variation',
		'label' => __( 'Order Items: Product Variation', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax_class',
		'label' => __( 'Order Items: Tax Class', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_quantity',
		'label' => __( 'Order Items: Quantity', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_total',
		'label' => __( 'Order Items: Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_subtotal',
		'label' => __( 'Order Items: Subtotal', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax',
		'label' => __( 'Order Items: Tax', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax_subtotal',
		'label' => __( 'Order Items: Tax Subtotal', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_type',
		'label' => __( 'Order Items: Type', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_category',
		'label' => __( 'Order Items: Category', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tag',
		'label' => __( 'Order Items: Tag', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_weight',
		'label' => __( 'Order Items: Weight', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_total_weight',
		'label' => __( 'Order Items: Total Weight', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_stock',
		'label' => __( 'Order Items: Stock', 'woo_ce' )
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

function woo_ce_override_order_field_labels( $fields = array() ) {

	$labels = woo_ce_get_option( 'order_labels', array() );
	if( !empty( $labels ) ) {
		foreach( $fields as $key => $field ) {
			if( isset( $labels[$field['name']] ) )
				$fields[$key]['label'] = $labels[$field['name']];
		}
	}
	return $fields;

}
add_filter( 'woo_ce_order_fields', 'woo_ce_override_order_field_labels', 11 );

// Adds custom Order and Order Item columns to the Order fields list
function woo_ce_extend_order_fields( $fields = array() ) {

	// Product Addons - http://www.woothemes.com/
	if( class_exists( 'Product_Addon_Admin' ) || class_exists( 'Product_Addon_Display' ) ) {
		$product_addons = woo_ce_get_product_addons();
		if( !empty( $product_addons ) ) {
			foreach( $product_addons as $product_addon ) {
				if( !empty( $product_addon ) ) {
					$fields[] = array(
						'name' => sprintf( 'order_items_product_addon_%s', $product_addon->post_name ),
						'label' => sprintf( __( 'Order Items: %s', 'woo_ce' ), ucfirst( $product_addon->post_title ) )
					);
				}
			}
		}
		unset( $product_addons, $product_addon );
	}

	// WooCommerce Sequential Order Numbers - http://www.skyverge.com/blog/woocommerce-sequential-order-numbers/
	// Sequential Order Numbers Pro - http://www.woothemes.com/products/sequential-order-numbers-pro/
	if( class_exists( 'WC_Seq_Order_Number' ) || class_exists( 'WC_Seq_Order_Number_Pro' ) ) {
		$fields[] = array(
			'name' => 'order_number',
			'label' => __( 'Order Number', 'woo_ce' )
		);
	}

	// WooCommerce Print Invoice & Delivery Note - https://wordpress.org/plugins/woocommerce-delivery-notes/
	if( class_exists( 'WooCommerce_Delivery_Notes' ) ) {
		$fields[] = array(
			'name' => 'invoice_number',
			'label' => __( 'Invoice Number', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'invoice_date',
			'label' => __( 'Invoice Date', 'woo_ce' )
		);
	}

	// WooCommerce PDF Invoices & Packing Slips - http://www.wpovernight.com
	if( class_exists( 'WooCommerce_PDF_Invoices' ) ) {
		$fields[] = array(
			'name' => 'pdf_invoice_number',
			'label' => __( 'PDF Invoice Number', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'pdf_invoice_date',
			'label' => __( 'PDF Invoice Date', 'woo_ce' )
		);
	}

	// WooCommerce Checkout Manager - http://wordpress.org/plugins/woocommerce-checkout-manager/
	// WooCommerce Checkout Manager Pro - http://www.trottyzone.com/product/woocommerce-checkout-manager-pro
	if( function_exists( 'wccs_install' ) ) {
		$options = get_option( 'wccs_settings' );
		if( isset( $options['buttons'] ) ) {
			$buttons = $options['buttons'];
			if( !empty( $buttons ) ) {
				foreach( $buttons as $button ) {
					$fields[] = array(
						'name' => $button['label'],
						'label' => ucfirst( $button['label'] )
					);
				}
				unset( $buttons, $button );
			}
		}
		unset( $options );
	}

	// Poor Guys Swiss Knife - http://wordpress.org/plugins/woocommerce-poor-guys-swiss-knife/
	if( function_exists( 'wcpgsk_init' ) ) {
		$options = get_option( 'wcpgsk_settings' );
		$billing_fields = ( isset( $options['woofields']['billing'] ) ? $options['woofields']['billing'] : array() );
		$shipping_fields = ( isset( $options['woofields']['shipping'] ) ? $options['woofields']['shipping'] : array() );

		// Custom billing fields
		if( !empty( $billing_fields ) ) {
			foreach( $billing_fields as $key => $billing_field ) {
				$fields[] = array(
					'name' => $key,
					'label' => $options['woofields']['label_' . $key]
				);
			}
			unset( $billing_fields, $billing_field );
		}

		// Custom shipping fields
		if( !empty( $shipping_fields ) ) {
			foreach( $shipping_fields as $key => $shipping_field ) {
				$fields[] = array(
					'name' => $key,
					'label' => $options['woofields']['label_' . $key]
				);
			}
			unset( $shipping_fields, $shipping_field );
		}

		unset( $options );
	}

	// Checkout Field Editor - http://woothemes.com/woocommerce/
	if( function_exists( 'woocommerce_init_checkout_field_editor' ) ) {
		$billing_fields = get_option( 'wc_fields_billing', array() );
		$shipping_fields = get_option( 'wc_fields_shipping', array() );
		$custom_fields = get_option( 'wc_fields_additional', array() );

		// Custom billing fields
		if( !empty( $billing_fields ) ) {
			foreach( $billing_fields as $key => $billing_field ) {
				// Only add non-default Checkout fields to export columns list
				if( $billing_field['custom'] == 1 ) {
					$fields[] = array(
						'name' => sprintf( 'wc_billing_%s', $key ),
						'label' => sprintf( __( 'Billing: %s', 'woo_ce' ), ucfirst( $billing_field['label'] ) )
					);
				}
			}
		}
		unset( $billing_fields, $billing_field );

		// Custom shipping fields
		if( !empty( $shipping_fields ) ) {
			foreach( $shipping_fields as $key => $shipping_field ) {
				// Only add non-default Checkout fields to export columns list
				if( $shipping_field['custom'] == 1 ) {
					$fields[] = array(
						'name' => sprintf( 'wc_shipping_%s', $key ),
						'label' => sprintf( __( 'Shipping: %s', 'woo_ce' ), ucfirst( $shipping_field['label'] ) )
					);
				}
			}
		}
		unset( $shipping_fields, $shipping_field );

		// Custom fields
		if( !empty( $custom_fields ) ) {
			foreach( $custom_fields as $key => $custom_field ) {
				// Only add non-default Checkout fields to export columns list
				if( $billing_field['custom'] == 1 ) {
					$fields[] = array(
						'name' => sprintf( 'wc_additional_%s', $key ),
						'label' => sprintf( __( 'Additional: %s', 'woo_ce' ), ucfirst( $custom_field['label'] ) )
					);
				}
			}
		}
		unset( $custom_fields, $custom_field );
	}

	// Checkout Field Manager - http://61extensions.com
	if( function_exists( 'sod_woocommerce_checkout_manager_settings' ) ) {
		$billing_fields = get_option( 'woocommerce_checkout_billing_fields', array() );
		$shipping_fields = get_option( 'woocommerce_checkout_shipping_fields', array() );
		$custom_fields = get_option( 'woocommerce_checkout_additional_fields', array() );

		// Custom billing fields
		if( !empty( $billing_fields ) ) {
			foreach( $billing_fields as $key => $billing_field ) {
				// Only add non-default Checkout fields to export columns list
				if( strtolower( $billing_field['default_field'] ) != 'on' ) {
					$fields[] = array(
						'name' => sprintf( 'sod_billing_%s', $billing_field['name'] ),
						'label' => sprintf( __( 'Billing: %s', 'woo_ce' ), ucfirst( $billing_field['label'] ) )
					);
				}
			}
		}
		unset( $billing_fields, $billing_field );

		// Custom shipping fields
		if( !empty( $shipping_fields ) ) {
			foreach( $shipping_fields as $key => $shipping_field ) {
				// Only add non-default Checkout fields to export columns list
				if( strtolower( $shipping_field['default_field'] ) != 'on' ) {
					$fields[] = array(
						'name' => sprintf( 'sod_shipping_%s', $shipping_field['name'] ),
						'label' => sprintf( __( 'Shipping: %s', 'woo_ce' ), ucfirst( $shipping_field['label'] ) )
					);
				}
			}
		}
		unset( $shipping_fields, $shipping_field );

		// Custom fields
		if( !empty( $custom_fields ) ) {
			foreach( $custom_fields as $key => $custom_field ) {
				// Only add non-default Checkout fields to export columns list
				if( strtolower( $custom_field['default_field'] ) != 'on' ) {
					$fields[] = array(
						'name' => sprintf( 'sod_additional_%s', $custom_field['name'] ),
						'label' => sprintf( __( 'Additional: %s', 'woo_ce' ), ucfirst( $custom_field['label'] ) )
					);
				}
			}
		}
		unset( $custom_fields, $custom_field );
	}

	// WooCommerce Checkout Add-Ons - http://www.skyverge.com/product/woocommerce-checkout-add-ons/
	if( function_exists( 'init_woocommerce_checkout_add_ons' ) ) {
		$fields[] = array(
			'name' => 'order_items_checkout_addon_id',
			'label' => __( 'Order Items: Checkout Add-ons ID', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'order_items_checkout_addon_label',
			'label' => __( 'Order Items: Checkout Add-ons Label', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'order_items_checkout_addon_value',
			'label' => __( 'Order Items: Checkout Add-ons Value', 'woo_ce' )
		);
	}

	// WooCommerce Brands Addon - http://woothemes.com/woocommerce/
	if( class_exists( 'WC_Brands' ) ) {
		$fields[] = array(
			'name' => 'order_items_brand',
			'label' => __( 'Order Items: Brand', 'woo_ce' )
		);
	}

	// Product Vendors - http://www.woothemes.com/products/product-vendors/
	if( class_exists( 'WooCommerce_Product_Vendors' ) ) {
		$fields[] = array(
			'name' => 'order_items_vendor',
			'label' => __( 'Order Items: Product Vendor', 'woo_ce' )
		);
	}

	// Cost of Goods - http://www.skyverge.com/product/woocommerce-cost-of-goods-tracking/
	if( class_exists( 'WC_COG' ) ) {
		$fields[] = array(
			'name' => 'total_cost_of_goods',
			'label' => __( 'Total Cost of Goods', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'order_items_cost_of_goods',
			'label' => __( 'Order Items: Cost of Goods', 'woo_ce' )
		);
	}

	// Local Pickup Plus - http://www.woothemes.com/products/local-pickup-plus/
	if( class_exists( 'WC_Local_Pickup_Plus' ) ) {
		$fields[] = array(
			'name' => 'order_items_pickup_location',
			'label' => __( 'Order Items: Pickup Location', 'woo_ce' )
		);
	}

	// Gravity Forms - http://woothemes.com/woocommerce
	if( class_exists( 'RGForms' ) && class_exists( 'woocommerce_gravityforms' ) ) {
		// Check if there are any Products linked to Gravity Forms
		if( $gf_fields = woo_ce_get_gravity_form_fields() ) {
			$fields[] = array(
				'name' => 'order_items_gf_form_id',
				'label' => __( 'Order Items: Gravity Form ID', 'woo_ce' )
			);
			$fields[] = array(
				'name' => 'order_items_gf_form_label',
				'label' => __( 'Order Items: Gravity Form Label', 'woo_ce' )
			);
			foreach( $gf_fields as $key => $gf_field ) {
				$fields[] = array(
					'name' => sprintf( 'order_items_gf_%d_%s', $gf_field['formId'], $gf_field['id'] ),
					'label' => sprintf( __( 'Order Items: %s', 'woo_ce' ), ucfirst( $gf_field['label'] ) )
				);
			}
		}
	}

	// WooCommerce Currency Switcher - http://dev.pathtoenlightenment.net/shop
	if( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
		$fields[] = array(
			'name' => 'order_currency',
			'label' => __( 'Order Currency', 'woo_ce' )
		);
	}

	// Custom Order fields
	$custom_orders = woo_ce_get_option( 'custom_orders', '' );
	if( !empty( $custom_orders ) ) {
		foreach( $custom_orders as $custom_order ) {
			if( !empty( $custom_order ) ) {
				$fields[] = array(
					'name' => $custom_order,
					'label' => ucfirst( $custom_order )
				);
			}
		}
		unset( $custom_orders, $custom_order );
	}


	// Custom Order Items fields
	$custom_order_items = woo_ce_get_option( 'custom_order_items', '' );
	if( !empty( $custom_order_items ) ) {
		foreach( $custom_order_items as $custom_order_item ) {
			if( !empty( $custom_order_item ) ) {
				$fields[] = array(
					'name' => sprintf( 'order_items_%s', $custom_order_item ),
					'label' => sprintf( __( 'Order Items: %s', 'woo_ce' ), $custom_order_item )
				);
			}
		}
	}

	return $fields;

}
add_filter( 'woo_ce_order_fields', 'woo_ce_extend_order_fields' );

function woo_ce_get_gravity_forms_products() {

	global $wpdb;

	$meta_key = '_gravity_form_data';
	$post_ids_sql = $wpdb->prepare( "SELECT `post_id`, `meta_value` FROM `$wpdb->postmeta` WHERE `meta_key` = %s GROUP BY `meta_value`", $meta_key );
	return $wpdb->get_results( $post_ids_sql );

}

function woo_ce_get_gravity_form_fields() {

	if( $gf_products = woo_ce_get_gravity_forms_products() ) {
		$fields = array();
		foreach( $gf_products as $gf_product ) {
			if( $gf_product_data = maybe_unserialize( get_post_meta( $gf_product->post_id, '_gravity_form_data', true ) ) ) {
				// Check the class and method for Gravity Forms exists
				if( class_exists( 'RGFormsModel' ) && method_exists( 'RGFormsModel', 'get_form_meta' ) ) {
					// Check the form exists
					$gf_form_meta = RGFormsModel::get_form_meta( $gf_product_data['id'] );
					if( !empty( $gf_form_meta ) ) {
						// Check that the form has fields assigned to it
						if( !empty( $gf_form_meta['fields'] ) ) {
							foreach( $gf_form_meta['fields'] as $gf_form_field ) {
								// Check for duplicate Gravity Form fields
								$gf_form_field['formTitle'] = $gf_form_meta['title'];
								$fields[] = $gf_form_field;
							}
						}
					}
				}
			}
		}
		return $fields;
	}

}

function woo_ce_format_order_date( $date ) {

	$output = $date;
	if( $date )
		$output = str_replace( '/', '-', $date );
	return $output;

}

// Returns a list of WooCommerce Order statuses
function woo_ce_get_order_statuses() {

	$terms = false;
	// Check if this is a WooCommerce 2.2+ instance (new Post Status)
	$woocommerce_version = woo_get_woo_version();
	if( version_compare( $woocommerce_version, '2.2' ) >= 0 ) {
		// Convert Order Status array into our magic sauce
		$order_statuses = ( function_exists( 'wc_get_order_statuses' ) ? wc_get_order_statuses() : false );
		if( !empty( $order_statuses ) ) {
			$terms = array();
			$post_type = 'shop_order';
			$posts_count = wp_count_posts( $post_type );
			foreach( $order_statuses as $key => $order_status ) {
				$terms[] = (object)array(
					'name' => $order_status,
					'slug' => $key,
					'count' => ( isset( $posts_count->$key ) ? $posts_count->$key : 0 )
				);
			}
		}
	} else {
		$args = array(
			'hide_empty' => false
		);
		$terms = get_terms( 'shop_order_status', $args );
		if( empty( $terms ) || ( is_wp_error( $terms ) == true ) )
			$terms = false;
	}
	return $terms;

}

function woo_ce_get_order_items_types() {

	$types = array(
		'line_item' => __( 'Line Item', 'woo_ce' ),
		'coupon' => __( 'Coupon', 'woo_ce' ),
		'fee' => __( 'Fee', 'woo_ce' ),
		'tax' => __( 'Tax', 'woo_ce' ),
		'shipping' => __( 'Shipping', 'woo_ce' )
	);
	$types = apply_filters( 'woo_ce_order_item_types', $types );
	return $types;

}

// Returns list of Product Addon columns
function woo_ce_get_product_addons() {

	$output = array();

	// Product Addons - http://www.woothemes.com/
	if( class_exists( 'Product_Addon_Admin' ) || class_exists( 'Product_Addon_Display' ) ) {
		$post_type = 'global_product_addon';
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1
		);
		if( $product_addons = get_posts( $args ) ) {
			foreach( $product_addons as $product_addon ) {
				if( $meta = maybe_unserialize( get_post_meta( $product_addon->ID, '_product_addons', true ) ) ) {
					$size = count( $meta );
					for( $i = 0; $i < $size; $i++ ) {
						$output[] = (object)array(
							'post_name' => $meta[$i]['name'],
							'post_title' => $meta[$i]['name']
						);
					}
				}
			}
		}
	}

	// Custom Order Items
	if( $custom_order_items = woo_ce_get_option( 'custom_order_items', '' ) ) {
		foreach( $custom_order_items as $custom_order_item ) {
			$output[] = (object)array(
				'post_name' => $custom_order_item,
				'post_title' => $custom_order_item
			);
		}
	}

	return $output;

}
?>