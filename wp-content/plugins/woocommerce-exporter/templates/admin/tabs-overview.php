<div class="overview-left">

	<h3><div class="dashicons dashicons-migrate"></div>&nbsp;<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>"><?php _e( 'Export', 'woo_ce' ); ?></a></h3>
	<p><?php _e( 'Export store details out of WooCommerce into a CSV-formatted file.', 'woo_ce' ); ?></p>
	<ul class="ul-disc">
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-product"><?php _e( 'Export Products', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-category"><?php _e( 'Export Categories', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-tag"><?php _e( 'Export Tags', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-brand"><?php _e( 'Export Brands', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-order"><?php _e( 'Export Orders', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-customer"><?php _e( 'Export Customers', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-user"><?php _e( 'Export Users', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-coupon"><?php _e( 'Export Coupons', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-subscription"><?php _e( 'Export Subscriptions', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-product_vendor"><?php _e( 'Export Product Vendors', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-shipping_class"><?php _e( 'Export Shipping Classes', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
<!--
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>#export-attribute"><?php _e( 'Export Attributes', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
-->
	</ul>

	<h3><div class="dashicons dashicons-list-view"></div>&nbsp;<a href="<?php echo add_query_arg( 'tab', 'archive' ); ?>"><?php _e( 'Archives', 'woo_ce' ); ?></a></h3>
	<p><?php _e( 'Download copies of prior store exports.', 'woo_ce' ); ?></p>

	<h3><div class="dashicons dashicons-admin-settings"></div>&nbsp;<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>"><?php _e( 'Settings', 'woo_ce' ); ?></a></h3>
	<p><?php _e( 'Manage export options from a single detailed screen.', 'woo_ce' ); ?></p>
	<ul class="ul-disc">
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>#general-settings"><?php _e( 'General Settings', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>#csv-settings"><?php _e( 'CSV Settings', 'woo_ce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>#xml-settings"><?php _e( 'XML Settings', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>#scheduled-exports"><?php _e( 'Scheduled Exports', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'tab', 'settings' ); ?>#cron-exports"><?php _e( 'CRON Exports', 'woo_ce' ); ?></a>
			<span class="description">(<?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?>)</span>
		</li>
	</ul>

	<h3><div class="dashicons dashicons-hammer"></div>&nbsp;<a href="<?php echo add_query_arg( 'tab', 'tools' ); ?>"><?php _e( 'Tools', 'woo_ce' ); ?></a></h3>
	<p><?php _e( 'Export tools for WooCommerce.', 'woo_ce' ); ?></p>

	<hr />
	<label class="description">
		<input type="checkbox" disabled="disabled" /> <?php _e( 'Jump to Export screen in the future', 'woo_ce' ); ?>
		<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
	</label>

</div>
<!-- .overview-left -->
<div class="welcome-panel overview-right">
	<h3>
		<!-- <span><a href="#"><attr title="<?php _e( 'Dismiss this message', 'woo_ce' ); ?>"><?php _e( 'Dismiss', 'woo_ce' ); ?></attr></a></span> -->
		<?php _e( 'Upgrade to Pro', 'woo_ce' ); ?>
	</h3>
	<p class="clear"><?php _e( 'Upgrade to Store Exporter Deluxe to unlock business focused e-commerce features within Store Exporter, including:', 'woo_ce' ); ?></p>
	<ul class="ul-disc">
		<li><?php _e( 'Select export date ranges', 'woo_ce' ); ?></li>
		<li><?php _e( 'Select export fields to export', 'woo_ce' ); ?></li>
		<li><?php _e( 'Filter exports by multiple filter options', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Orders', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export custom Order and Order Item meta', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Customers', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export custom Customer meta', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Coupons', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export custom User meta', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Subscriptions', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Product Vendors', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export Shipping Classes', 'woo_ce' ); ?></li>
		<li><?php _e( 'CRON export engine', 'woo_ce' ); ?></li>
		<li><?php _e( 'Schedule automatic exports with filtering options', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export to remote POST', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export to e-mail addresses', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export to remote FTP', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export to XML file', 'woo_ce' ); ?></li>
		<li><?php _e( 'Export to Excel 2007 (XLS) file', 'woo_ce' ); ?></li>
		<li><?php _e( 'Premium Support', 'woo_ce' ); ?></li>
		<li><?php _e( '...and more.', 'woo_ce' ); ?></li>
	</ul>
	<p>
		<a href="<?php echo $woo_cd_url; ?>" target="_blank" class="button"><?php _e( 'More Features', 'woo_ce' ); ?></a>&nbsp;
		<a href="<?php echo $woo_cd_url; ?>" target="_blank" class="button button-primary"><?php _e( 'Buy Now', 'woo_ce' ); ?></a>
	</p>
</div>
<!-- .overview-right -->