<?php
function woo_ce_export_settings_quicklinks() {

	ob_start(); ?>
<li>| <a href="#xml-settings"><?php _e( 'XML Settings', 'woo_ce' ); ?></a> |</li>
<li><a href="#scheduled-exports"><?php _e( 'Scheduled Exports', 'woo_ce' ); ?></a> |</li>
<li><a href="#cron-exports"><?php _e( 'CRON Exports', 'woo_ce' ); ?></a></li>
<?php
	ob_end_flush();

}

function woo_ce_export_settings_additional() {

	$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
	$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

	$email_to = get_option( 'admin_email', '' );
	$email_subject = __( '[%store_name%] Export: %export_type% (%export_filename%)', 'woo_ce' );
	$post_to = 'http://www.domain.com/sample-form/';
	ob_start(); ?>
<tr>
	<th>
		<label for="email_to"><?php _e( 'Default e-mail recipient', 'woo_ce' ); ?></label>
	</th>
	<td>
		<input name="email_to" type="text" id="email_to" value="<?php echo esc_attr( $email_to ); ?>" class="regular-text code" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Set the default recipient of scheduled export e-mails, can be overriden via CRON using the <code>to</code> argument. Default is the WordPress Blog administrator e-mail address.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="email_to"><?php _e( 'Default e-mail subject', 'woo_ce' ); ?></label>
	</th>
	<td>
		<input name="email_to" type="text" id="email_subject" value="<?php echo esc_attr( $email_subject ); ?>" class="large-text code" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Set the default subject of scheduled export e-mails, can be overriden via CRON using the <code>subject</code> argument. Tags can be used: <code>%store_name%</code>, <code>%export_type%</code>, <code>%export_filename%</code>.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="post_to"><?php _e( 'Default remote POST URL', 'woo_ce' ); ?></label>
	</th>
	<td>
		<input name="post_to" type="text" id="post_to" value="<?php echo esc_url( $post_to ); ?>" class="full-text code" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Set the default remote POST address for scheduled exports, can be overriden via CRON using the <code>to</code> argument. Default is empty.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
	ob_end_flush();
	
}

// Returns the disabled HTML template for the Enable CRON and Secret Export Key options for the Settings screen
function woo_ce_export_settings_cron() {

	$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
	$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );

	// Scheduled exports
	// Override to enable the Export Type to include all export types
	$export_types = array(
		'product' => __( 'Products', 'woo_ce' ),
		'category' => __( 'Categories', 'woo_ce' ),
		'tag' => __( 'Tags', 'woo_ce' ),
		'brand' => __( 'Brands', 'woo_ce' ),
		'order' => __( 'Orders', 'woo_ce' ),
		'customer' => __( 'Customers', 'woo_ce' ),
		'user' => __( 'Users', 'woo_ce' ),
		'coupon' => __( 'Coupons', 'woo_ce' ),
		'subscription' => __( 'Subscriptions', 'woo_ce' ),
		'product_vendor' => __( 'Product Vendors', 'woo_ce' ),
		'shipping_class' => __( 'Shipping Classes', 'woo_ce' )
	);
	$order_statuses = woo_ce_get_order_statuses();
	$product_types = woo_ce_get_product_types();
	$auto_interval = 1440;
	$auto_format = 'csv';
	$ftp_method_host = 'ftp.domain.com';
	$ftp_method_user = 'export';
	$ftp_method_pass = '';
	$ftp_method_port = '';
	$ftp_method_path = 'wp-content/uploads/export/';
	$ftp_method_passive = 'auto';
	$ftp_method_timeout = '';
	$scheduled_fields = 'all';

	// CRON exports
	$secret_key = '-';
	$cron_fields = 'all';

	$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
	ob_start(); ?>
<tr id="xml-settings">
	<td colspan="2" style="padding:0;">
		<hr />
		<h3><?php _e( 'XML Settings', 'woo_ce' ); ?></h3>
	</td>
</tr>
<tr>
	<th>
		<label><?php _e( 'Attribute display', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul>
			<li><label><input type="checkbox" name="xml_attribute_url" value="1" disabled="disabled" /> <?php _e( 'Site Address', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_title" value="1" disabled="disabled" /> <?php _e( 'Site Title', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_date" value="1" disabled="disabled" /> <?php _e( 'Export Date', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_time" value="1" disabled="disabled" /> <?php _e( 'Export Time', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_export" value="1" disabled="disabled" /> <?php _e( 'Export Type', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_orderby" value="1" disabled="disabled" /> <?php _e( 'Export Order By', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_order" value="1" disabled="disabled" /> <?php _e( 'Export Order', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_limit" value="1" disabled="disabled" /> <?php _e( 'Limit Volume', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="checkbox" name="xml_attribute_offset" value="1" disabled="disabled" /> <?php _e( 'Volume Offset', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
		</ul>
		<p class="description"><?php _e( 'Control the visibility of different attributes in the XML export.', 'woo_ce' ); ?></p>
	</td>
</tr>

<tr id="scheduled-exports">
	<td colspan="2" style="padding:0;">
		<hr />
		<h3><div class="dashicons dashicons-calendar"></div>&nbsp;<?php _e( 'Scheduled Exports', 'woo_ce' ); ?></h3>
		<p class="description"><?php _e( 'Configure Store Exporter Deluxe to automatically generate exports.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="enable_auto"><?php _e( 'Enable scheduled exports', 'woo_ce' ); ?></label>
	</th>
	<td>
		<select id="enable_auto" name="enable_auto">
			<option value="1" disabled="disabled"><?php _e( 'Yes', 'woo_ce' ); ?></option>
			<option value="0" selected="selected"><?php _e( 'No', 'woo_ce' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Enabling Scheduled Exports will trigger automated exports at the interval specified under Once every (minutes).', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="auto_type"><?php _e( 'Export type', 'woo_ce' ); ?></label>
	</th>
	<td>
		<select id="auto_type" name="auto_type">
<?php if( $export_types ) { ?>
	<?php foreach( $export_types as $key => $export_type ) { ?>
			<option value="<?php echo $key; ?>"><?php echo $export_type; ?></option>
	<?php } ?>
<?php } else { ?>
			<option value=""><?php _e( 'No export types were found.', 'woo_ce' ); ?></option>
<?php } ?>
		</select>
		<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Select the data type you want to export.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr class="auto_type_options">
	<th>
		<label><?php _e( 'Export filters', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul>
			<li class="export-options product-options">
				<label><?php _e( 'Product Type', 'woo_ce' ); ?></label>
<?php if( $product_types ) { ?>
				<ul style="margin-top:0.2em;">
	<?php foreach( $product_types as $key => $product_type ) { ?>
					<li><label><input type="checkbox" name="product_filter_type[<?php echo $key; ?>]" value="<?php echo $key; ?>" disabled="disabled" /> <?php echo woo_ce_format_product_type( $product_type['name'] ); ?> (<?php echo $product_type['count']; ?>)<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
	<?php } ?>
				</ul>
<?php } ?>
				<p class="description"><?php _e( 'Select the Product Type\'s you want to filter exported Products by. Default is to include all Product Types and Variations.', 'woo_ce' ); ?></p>
			</li>
			<li class="export-options category-options tag-options brand-options customer-options user-options coupon-options subscription-options product_vendor-options">
				<p><?php _e( 'No export filter options are available for this export type.', 'woo_ce' ); ?></p>
			</li>
			<li class="export-options order-options">
				<label><?php _e( 'Order Status', 'woo_ce' ); ?></label>
				<select name="order_filter_status">
					<option value="" selected="selected"><?php _e( 'All', 'woo_ce' ); ?></option>
<?php if( $order_statuses ) { ?>
	<?php foreach( $order_statuses as $order_status ) { ?>
					<option value="<?php echo $order_status->name; ?>" disabled="disabled"><?php echo ucfirst( $order_status->name ); ?></option>
	<?php } ?>
<?php } ?>
				</select>
				<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
				<p class="description"><?php _e( 'Select the Order Status you want to filter exported Orders by. Default is to include all Order Status options.', 'woo_ce' ); ?></p>
			</li>
			<li class="export-options order-options">
				<label><?php _e( 'Order Date', 'woo_ce' ); ?></label>
				<input type="text" size="10" maxlength="10" class="text" disabled="disabled"> to <input type="text" size="10" maxlength="10" class="text" disabled="disabled"><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
				<p class="description"><?php _e( 'Filter the dates of Orders to be included in the export. Default is empty.', 'woo_ce' ); ?></p>
			</li>
		</ul>
	</td>
</tr>
<tr>
	<th>
		<label for="auto_interval"><?php _e( 'Once every (minutes)', 'woo_ce' ); ?></label>
	</th>
	<td>
		<input name="auto_interval" type="text" id="auto_interval" value="<?php echo esc_attr( $auto_interval ); ?>" size="4" maxlength="4" class="text" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Choose how often Store Exporter Deluxe generates new exports. Default is every 1440 minutes (once every 24 hours).', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label><?php _e( 'Export format', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul style="margin-top:0.2em;">
			<li><label><input type="radio" name="auto_format" value="csv"<?php checked( $auto_format, 'csv' ); ?> disabled="disabled" /> <?php _e( 'CSV', 'woo_ce' ); ?> <span class="description"><?php _e( '(Comma separated values)', 'woo_ce' ); ?></span><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="radio" name="auto_format" value="xml"<?php checked( $auto_format, 'xml' ); ?> disabled="disabled" /> <?php _e( 'XML', 'woo_ce' ); ?> <span class="description"><?php _e( '(EXtensible Markup Language)', 'woo_ce' ); ?></span><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="radio" name="auto_format" value="xls"<?php checked( $auto_format, 'xls' ); ?> disabled="disabled" /> <?php _e( 'Excel (XLS)', 'woo_ce' ); ?> <span class="description"><?php _e( '(Microsoft Excel 2007)', 'woo_ce' ); ?></span><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
		</ul>
		<p class="description"><?php _e( 'Adjust the export format to generate different export file formats. Default is CSV.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="auto_method"><?php _e( 'Export method', 'woo_ce' ); ?></label>
	</th>
	<td>
		<select id="auto_method" name="auto_method">
			<option value="archive"><?php _e( 'Archive to WordPress Media', 'woo_ce' ); ?></option>
			<option value="email"><?php _e( 'Send as e-mail', 'woo_ce' ); ?></option>
			<option value="post"><?php _e( 'POST to remote URL', 'woo_ce' ); ?></option>
			<option value="ftp"><?php _e( 'Upload to remote FTP', 'woo_ce' ); ?></option>
		</select>
		<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'Choose what Store Exporter Deluxe does with the generated export. Default is to archive the export to the WordPress Media for archival purposes.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr class="auto_method_options">
	<th>
		<label><?php _e( 'Export method options', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul>
			<li class="export-options ftp-options">
				<label for="ftp_method_host"><?php _e( 'Host', 'woo_ce' ); ?>:</label> <input type="text" id="ftp_method_host" name="ftp_method_host" size="15" class="regular-text code" value="<?php echo sanitize_text_field( $ftp_method_host ); ?>" disabled="disabled" /><br />
				<label for="ftp_method_user"><?php _e( 'Username', 'woo_ce' ); ?>:</label> <input type="text" id="ftp_method_user" name="ftp_method_user" size="15" class="regular-text code" value="<?php echo sanitize_text_field( $ftp_method_user ); ?>" disabled="disabled" /><br />
				<label for="ftp_method_pass"><?php _e( 'Password', 'woo_ce' ); ?>:</label> <input type="password" id="ftp_method_pass" name="ftp_method_pass" size="15" class="regular-text code" value="" disabled="disabled" /><?php if( !empty( $ftp_method_pass ) ) { echo ' ' . __( '(password is saved)', 'woo_ce' ); } ?><br />
				<label for="ftp_method_port"><?php _e( 'Port', 'woo_ce' ); ?>:</label> <input type="text" id="ftp_method_port" name="ftp_method_port" size="5" class="short-text code" value="<?php echo sanitize_text_field( $ftp_method_port ); ?>" maxlength="5" disabled="disabled" /><br />
				<label for="ftp_method_file_path"><?php _e( 'File path', 'woo_ce' ); ?>:</label> <input type="text" id="ftp_method_file_path" name="ftp_method_path" size="25" class="regular-text code" value="<?php echo sanitize_text_field( $ftp_method_path ); ?>" disabled="disabled" /><br />
				<label for="ftp_method_passive"><?php _e( 'Transfer mode', 'woo_ce' ); ?>:</label> 
				<select id="ftp_method_passive" name="ftp_method_passive">
					<option value="auto" selected="selected"><?php _e( 'Auto', 'woo_ce' ); ?></option>
					<option value="active" disabled="disabled"><?php _e( 'Active', 'woo_ce' ); ?></option>
					<option value="passive" disabled="disabled"><?php _e( 'Passive', 'woo_ce' ); ?></option>
				</select><br />
				<label for="ftp_method_timeout"><?php _e( 'Timeout', 'woo_ce' ); ?>:</label> <input type="text" id="ftp_method_timeout" name="ftp_method_timeout" size="5" class="short-text code" value="<?php echo sanitize_text_field( $ftp_method_timeout ); ?>" /><br />
				<p class="description"><?php _e( 'Enter the FTP host, login details and path of where to save the export file, do not provide the filename, the export filename can be set on General Settings above. For file path example: <code>wp-content/uploads/exports/</code>', 'woo_ce' ); ?></p>
			</li>
			<li class="export-options archive-options email-options post-options">
				<p><?php _e( 'No export method options are available for this export method.', 'woo_ce' ); ?></p>
			</li>
		</ul>
	</td>
</tr>
<tr>
	<th>
		<label for="scheduled_fields"><?php _e( 'Export Fields', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul style="margin-top:0.2em;">
			<li><label><input type="radio" id="scheduled_fields" name="scheduled_fields" value="all"<?php checked( $scheduled_fields, 'all' ); ?> /> <?php _e( 'Include all Export Fields for the requested Export Type', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="radio" name="scheduled_fields" value="saved"<?php checked( $scheduled_fields, 'saved' ); ?> disabled="disabled" /> <?php _e( 'Use the saved Export Fields preference set on the Export screen for the requested Export Type', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
		</ul>
		<p class="description"><?php _e( 'Control whether all known export fields are included or only checked fields from the Export Fields section on the Export screen for each Export Type. Default is to include all export fields.', 'woo_ce' ); ?></p>
	</td>
</tr>

<tr id="cron-exports">
	<td colspan="2" style="padding:0;">
		<hr />
		<h3><div class="dashicons dashicons-clock"></div>&nbsp;<?php _e( 'CRON Exports', 'woo_ce' ); ?></h3>
		<p class="description"><?php printf( __( 'Store Exporter Deluxe supports exporting via a command line request. For sample CRON requests and supported arguments consult our <a href="%s" target="_blank">online documentation</a>.', 'woo_ce' ), $troubleshooting_url ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="enable_cron"><?php _e( 'Enable CRON', 'woo_ce' ); ?></label>
	</th>
	<td>
		<select id="enable_cron" name="enable_cron">
			<option value="1" disabled="disabled"><?php _e( 'Yes', 'woo_ce' ); ?></option>
			<option value="0" selected="selected"><?php _e( 'No', 'woo_ce' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Enabling CRON allows developers to schedule automated exports and connect with Store Exporter Deluxe remotely.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="secret_key"><?php _e( 'Export secret key', 'woo_ce' ); ?></label>
	</th>
	<td>
		<input name="secret_key" type="text" id="secret_key" value="<?php echo esc_attr( $secret_key ); ?>" class="large-text code" disabled="disabled" /><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
		<p class="description"><?php _e( 'This secret key (can be left empty to allow unrestricted access) limits access to authorised developers who provide a matching key when working with Store Exporter Deluxe.', 'woo_ce' ); ?></p>
	</td>
</tr>
<tr>
	<th>
		<label for="cron_fields"><?php _e( 'Export Fields', 'woo_ce' ); ?></label>
	</th>
	<td>
		<ul style="margin-top:0.2em;">
			<li><label><input type="radio" id="cron_fields" name="cron_fields" value="all"<?php checked( $cron_fields, 'all' ); ?> /> <?php _e( 'Include all Export Fields for the requested Export Type', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
			<li><label><input type="radio" name="cron_fields" value="saved"<?php checked( $cron_fields, 'saved' ); ?> disabled="disabled" /> <?php _e( 'Use the saved Export Fields preference set on the Export screen for the requested Export Type', 'woo_ce' ); ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span></label></li>
		</ul>
		<p class="description"><?php _e( 'Control whether all known export fields are included or only checked fields from the Export Fields section on the Export screen for each Export Type. Default is to include all export fields.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
	ob_end_flush();

}
?>