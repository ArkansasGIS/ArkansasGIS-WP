<ul class="subsubsub">
	<li><a href="#export-type"><?php _e( 'Export Type', 'woo_ce' ); ?></a> |</li>
	<li><a href="#export-options"><?php _e( 'Export Options', 'woo_ce' ); ?></a></li>
	<li>| <a href="#export-modules"><?php _e( 'Export Modules', 'woo_ce' ); ?></a></li>
	<?php do_action( 'woo_ce_export_quicklinks' ); ?>
</ul>
<!-- .subsubsub -->
<br class="clear" />

<p><?php _e( 'Select an export type from the list below to export entries. Once you have selected an export type you may select the fields you would like to export and optional filters available for each export type. When you click the export button below, Store Exporter will create an export file for you to save to your computer.', 'woo_ce' ); ?></p>
<div id="poststuff">
	<form method="post" action="<?php echo add_query_arg( array( 'failed' => null, 'empty' => null, 'message' => null ) ); ?>" id="postform">

		<div id="export-type" class="postbox">
			<h3 class="hndle"><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Select the data type you want to export.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<input type="radio" id="product" name="dataset" value="product"<?php disabled( $products, 0 ); ?><?php checked( $export_type, 'product' ); ?> />
							<label for="product"><?php _e( 'Products', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $products; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="category" name="dataset" value="category"<?php disabled( $categories, 0 ); ?><?php checked( $export_type, 'category' ); ?> />
							<label for="category"><?php _e( 'Categories', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $categories; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="tag" name="dataset" value="tag"<?php disabled( $tags, 0 ); ?><?php checked( $export_type, 'tag' ); ?> />
							<label for="tag"><?php _e( 'Tags', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $tags; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="brand" name="dataset" value="brand"<?php disabled( $brands, 0 ); ?><?php checked( $export_type, 'brand' ); ?> />
							<label for="brand"><?php _e( 'Brands', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $brands; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="order" name="dataset" value="order"<?php disabled( $orders, 0 ); ?><?php checked( $export_type, 'order' ); ?>/>
							<label for="order"><?php _e( 'Orders', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $orders; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="customer" name="dataset" value="customer"<?php disabled( $customers, 0 ); ?><?php checked( $export_type, 'customer' ); ?>/>
							<label for="customer"><?php _e( 'Customers', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $customers; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="user" name="dataset" value="user"<?php disabled( $users, 0 ); ?><?php checked( $export_type, 'user' ); ?>/>
							<label for="user"><?php _e( 'Users', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $users; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="coupon" name="dataset" value="coupon"<?php disabled( $coupons, 0 ); ?><?php checked( $export_type, 'coupon' ); ?> />
							<label for="coupon"><?php _e( 'Coupons', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $coupons; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="subscription" name="dataset" value="subscription"<?php disabled( $subscriptions, 0 ); ?><?php checked( $export_type, 'subscription' ); ?> />
							<label for="subscription"><?php _e( 'Subscriptions', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $subscriptions; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="product_vendor" name="dataset" value="product_vendor"<?php disabled( $product_vendors, 0 ); ?><?php checked( $export_type, 'product_vendor' ); ?> />
							<label for="product_vendor"><?php _e( 'Product Vendors', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $product_vendors; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="shipping_class" name="dataset" value="shipping_class"<?php disabled( $shipping_classes, 0 ); ?><?php checked( $export_type, 'shipping_class' ); ?> />
							<label for="shipping_class"><?php _e( 'Shipping Classes', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $shipping_classes; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

<!--
					<tr>
						<th>
							<input type="radio" id="attribute" name="dataset" value="attribute"<?php disabled( $attributes, 0 ); ?><?php checked( $export_type, 'attribute' ); ?> />
							<label for="attribute"><?php _e( 'Attributes', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $attributes; ?>)</span>
						</td>
					</tr>
-->

				</table>
				<!-- .form-table -->
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

<?php if( $product_fields ) { ?>
		<div id="export-product" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Product Fields', 'woo_ce' ); ?>
					<a href="<?php echo add_query_arg( array( 'tab' => 'fields', 'type' => 'product' ) ); ?>" style="float:right;"><?php _e( 'Configure', 'woo_ce' ); ?></a>
				</h3>
				<div class="inside">
	<?php if( $products ) { ?>
					<p class="description"><?php _e( 'Select the Product fields you would like to export, your field selection is saved for future exports.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="product-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="product-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="product-fields" class="ui-sortable">

		<?php foreach( $product_fields as $product_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="product_fields[<?php echo $product_field['name']; ?>]" class="product_field"<?php ( isset( $product_field['default'] ) ? checked( $product_field['default'], 1 ) : '' ); ?><?php disabled( $product_field['disabled'], 1 ); ?> />
									<?php echo $product_field['label']; ?>
									<?php if( $product_field['disabled'] ) { ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span><?php } ?>
									<input type="hidden" name="product_fields_order[<?php echo $product_field['name']; ?>]" class="field_order" value="<?php echo $product_field['order']; ?>" />
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_product" value="<?php _e( 'Export Products', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Product field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Products were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
			</div>
			<!-- .postbox -->

			<div id="export-products-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Product Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_product_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_product_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_product_options_after_table' ); ?>

				</div>
				<!-- .inside -->

			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-product -->

<?php } ?>
<?php if( $category_fields ) { ?>
		<div id="export-category" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Category Fields', 'woo_ce' ); ?>
					<a href="<?php echo add_query_arg( array( 'tab' => 'fields', 'type' => 'category' ) ); ?>" style="float:right;"><?php _e( 'Configure', 'woo_ce' ); ?></a>
				</h3>
				<div class="inside">
					<p class="description"><?php _e( 'Select the Category fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="category-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="category-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="category-fields" class="ui-sortable">

	<?php foreach( $category_fields as $category_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="category_fields[<?php echo $category_field['name']; ?>]" class="category_field"<?php ( isset( $category_field['default'] ) ? checked( $category_field['default'], 1 ) : '' ); ?><?php disabled( $category_field['disabled'], 1 ); ?> />
									<?php echo $category_field['label']; ?>
									<input type="hidden" name="category_fields_order[<?php echo $category_field['name']; ?>]" class="field_order" value="" />
								</label>
							</td>
						</tr>

	<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_category" value="<?php _e( 'Export Categories', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Category field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-categories-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Category Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_category_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_category_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_category_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- #export-categories-filters -->

		</div>
		<!-- #export-category -->
<?php } ?>
<?php if( $tag_fields ) { ?>
		<div id="export-tag" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Tag Fields', 'woo_ce' ); ?>
					<a href="<?php echo add_query_arg( array( 'tab' => 'fields', 'type' => 'tag' ) ); ?>" style="float:right;"><?php _e( 'Configure', 'woo_ce' ); ?></a>
				</h3>
				<div class="inside">
					<p class="description"><?php _e( 'Select the Tag fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="tag-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="tag-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="tag-fields" class="ui-sortable">

	<?php foreach( $tag_fields as $tag_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="tag_fields[<?php echo $tag_field['name']; ?>]" class="tag_field"<?php ( isset( $tag_field['default'] ) ? checked( $tag_field['default'], 1 ) : '' ); ?><?php disabled( $tag_field['disabled'], 1 ); ?> />
									<?php echo $tag_field['label']; ?>
									<input type="hidden" name="tag_fields_order[<?php echo $tag_field['name']; ?>]" class="field_order" value="" />
								</label>
							</td>
						</tr>

	<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_tag" value="<?php _e( 'Export Tags', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Tag field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-tags-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Product Tag Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_tag_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_tag_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_tag_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- #export-tags-filters -->

		</div>
		<!-- #export-tag -->
<?php } ?>

<?php if( $brand_fields ) { ?>
		<div id="export-brand" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Brand Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $brands ) { ?>
					<p class="description"><?php _e( 'Select the Brand fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="brand-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="brand-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="brand-fields" class="ui-sortable">

		<?php foreach( $brand_fields as $brand_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="brand_fields[<?php echo $brand_field['name']; ?>]" class="brand_field"<?php ( isset( $brand_field['default'] ) ? checked( $brand_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $brand_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Brands', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Brand field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Brands were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-brands-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Brand Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_brand_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_brand_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_brand_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-brand -->

<?php } ?>
<?php if( $order_fields ) { ?>
		<div id="export-order" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Order Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">

	<?php if( $orders ) { ?>
					<p class="description"><?php _e( 'Select the Order fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="order-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="order-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="order-fields" class="ui-sortable">

		<?php foreach( $order_fields as $order_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="order_fields[<?php echo $order_field['name']; ?>]" class="order_field"<?php ( isset( $order_field['default'] ) ? checked( $order_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $order_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Orders', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Order field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Orders were found.', 'woo_ce' ); ?></p>
	<?php } ?>

				</div>
			</div>
			<!-- .postbox -->

			<div id="export-orders-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Order Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_order_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_order_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_order_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-order -->

<?php } ?>
<?php if( $customer_fields ) { ?>
		<div id="export-customer" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Customer Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $customers ) { ?>
					<p class="description"><?php _e( 'Select the Customer fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="customer-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="customer-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="customer-fields" class="ui-sortable">

		<?php foreach( $customer_fields as $customer_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="customer_fields[<?php echo $customer_field['name']; ?>]" class="customer_field"<?php ( isset( $customer_field['default'] ) ? checked( $customer_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $customer_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Customers', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Customer field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Customers were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-customers-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Customer Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_customer_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_customer_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_customer_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-customer -->

<?php } ?>
<?php if( $user_fields ) { ?>
		<div id="export-user" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'User Fields', 'woo_ce' ); ?>
					<a href="<?php echo add_query_arg( array( 'tab' => 'fields', 'type' => 'user' ) ); ?>" style="float:right;"><?php _e( 'Configure', 'woo_ce' ); ?></a>
				</h3>
				<div class="inside">
	<?php if( $users ) { ?>
					<p class="description"><?php _e( 'Select the User fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="user-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="user-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="user-fields" class="ui-sortable">

		<?php foreach( $user_fields as $user_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="user_fields[<?php echo $user_field['name']; ?>]" class="user_field"<?php ( isset( $user_field['default'] ) ? checked( $user_field['default'], 1 ) : '' ); ?><?php disabled( $user_field['disabled'], 1 ); ?> />
									<?php echo $user_field['label']; ?>
									<?php if( $user_field['disabled'] ) { ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span><?php } ?>
									<input type="hidden" name="user_fields_order[<?php echo $user_field['name']; ?>]" class="field_order" value="" />
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_user" class="button-primary" value="<?php _e( 'Export Users', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular User field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Users were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-users-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'User Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_user_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_user_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_user_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-user -->

<?php } ?>
<?php if( $coupon_fields ) { ?>
		<div id="export-coupon" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Coupon Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $coupons ) { ?>
					<p class="description"><?php _e( 'Select the Coupon fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="coupon-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="coupon-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="coupon-fields" class="ui-sortable">

		<?php foreach( $coupon_fields as $coupon_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="coupon_fields[<?php echo $coupon_field['name']; ?>]" class="coupon_field"<?php ( isset( $coupon_field['default'] ) ? checked( $coupon_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $coupon_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Coupons', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Coupon field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Coupons were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-coupons-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Coupon Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_coupon_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_coupon_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_coupon_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-coupon -->

<?php } ?>
<?php if( $subscription_fields ) { ?>
		<div id="export-subscription" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Subscription Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $subscriptions ) { ?>
					<p class="description"><?php _e( 'Select the Subscription fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="subscription-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="subscription-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="subscription-fields" class="ui-sortable">

		<?php foreach( $subscription_fields as $subscription_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="subscription_fields[<?php echo $subscription_field['name']; ?>]" class="subscription_field"<?php ( isset( $subscription_field['default'] ) ? checked( $subscription_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $subscription_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Subscriptions', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Subscription field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Subscriptions were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->
		</div>
		<!-- #export-subscription -->

<?php } ?>
<?php if( $product_vendor_fields ) { ?>
		<div id="export-product_vendor" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Product Vendor Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $product_vendors ) { ?>
					<p class="description"><?php _e( 'Select the Product Vendor fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="product_vendor-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="product_vendor-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="product_vendor-fields" class="ui-sortable">

		<?php foreach( $product_vendor_fields as $product_vendor_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="product_vendor_fields[<?php echo $product_vendor_field['name']; ?>]" class="product_vendor_field"<?php ( isset( $product_vendor_field['default'] ) ? checked( $product_vendor_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $product_vendor_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Product Vendors', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Product Vendor field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Product Vendors were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-product_vendor -->

<?php } ?>
<?php if( $shipping_class_fields ) { ?>
		<div id="export-shipping_class" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Shipping Class Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">
	<?php if( $shipping_classes ) { ?>
					<p class="description"><?php _e( 'Select the Shipping Class fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="shipping_class-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="shipping_class-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table id="shipping_class-fields" class="ui-sortable">

		<?php foreach( $shipping_class_fields as $shipping_class_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="shipping_class_fields[<?php echo $shipping_class_field['name']; ?>]" class="shipping_class_field"<?php ( isset( $shipping_class_field['default'] ) ? checked( $shipping_class_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $shipping_class_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Shipping Classes', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Shipping Class field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Shipping Classes were found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-shipping-classes-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Shipping Class Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_shipping_class_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_shipping_class_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_shipping_class_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-shipping_class -->

<?php } ?>
		<?php do_action( 'woo_ce_before_options' ); ?>

		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Export Options', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'You can find additional export options under the Settings tab at the top of this screen.', 'woo_ce' ); ?></p>

				<?php do_action( 'woo_ce_export_options_before' ); ?>

				<table class="form-table">

					<?php do_action( 'woo_ce_export_options' ); ?>

					<tr>
						<th>
							<label for="offset"><?php _e( 'Volume offset', 'woo_ce' ); ?></label> / <label for="limit_volume"><?php _e( 'Limit volume', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="offset" name="offset" value="<?php echo esc_attr( $offset ); ?>" size="5" class="text" title="<?php _e( 'Volume Offset', 'woo_ce' ); ?>" /> <?php _e( 'to', 'woo_ce' ); ?> <input type="text" size="3" id="limit_volume" name="limit_volume" value="<?php echo esc_attr( $limit_volume ); ?>" size="5" class="text" title="<?php _e( 'Limit Volume', 'woo_ce' ); ?>" />
							<p class="description"><?php _e( 'Volume offset and limit allows for partial exporting of an export type (e.g. records 0 to 500, etc.). This is useful when encountering timeout and/or memory errors during the a large or memory intensive export. To be used effectively both fields must be filled. By default this is not used and is left empty.', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<?php do_action( 'woo_ce_export_options_table_after' ); ?>

				</table>

				<?php do_action( 'woo_ce_export_options_after' ); ?>

			</div>
		</div>
		<!-- .postbox -->

		<?php do_action( 'woo_ce_after_options' ); ?>

		<input type="hidden" name="action" value="export" />
	</form>

	<?php do_action( 'woo_ce_export_after_form' ); ?>

	<?php do_action( 'woo_ce_before_modules' ); ?>

	<div id="export-modules" class="postbox">
		<h3 class="hndle"><?php _e( 'Export Modules', 'woo_ce' ); ?></h3>
		<div class="inside">
			<p><?php _e( 'Export store details from other WooCommerce and WordPress Plugins, simply install and activate one of the below Plugins to enable those additional export options.', 'woo_ce' ); ?></p>
<?php if( $modules ) { ?>
			<div class="table table_content">
				<table class="woo_vm_version_table">
	<?php foreach( $modules as $module ) { ?>
					<tr>
						<td class="export_module">
		<?php if( $module['description'] ) { ?>
							<strong><?php echo $module['title']; ?></strong>: <span class="description"><?php echo $module['description']; ?></span>
		<?php } else { ?>
							<strong><?php echo $module['title']; ?></strong>
		<?php } ?>
						</td>
						<td class="status">
							<div class="<?php woo_ce_modules_status_class( $module['status'] ); ?>">
		<?php if( $module['status'] == 'active' ) { ?>
								<div class="dashicons dashicons-yes" style="color:#008000;"></div><?php woo_ce_modules_status_label( $module['status'] ); ?>
		<?php } else { ?>
			<?php if( $module['url'] ) { ?>
								<?php if( isset( $module['slug'] ) ) { echo '<div class="dashicons dashicons-download" style="color:#0074a2;"></div>'; } else { echo '<div class="dashicons dashicons-admin-links"></div>'; } ?>&nbsp;<a href="<?php echo $module['url']; ?>" target="_blank"<?php if( isset( $module['slug'] ) ) { echo ' title="' . __( 'Install via WordPress Plugin Directory', 'woo_ce' ) . '"'; } else { echo ' title="' . __( 'Visit the Plugin website', 'woo_ce' ) . '"'; } ?>><?php woo_ce_modules_status_label( $module['status'] ); ?></a>
			<?php } ?>
		<?php } ?>
							</div>
						</td>
					</tr>
	<?php } ?>
				</table>
			</div>
			<!-- .table -->
<?php } else { ?>
			<p><?php _e( 'No export modules are available at this time.', 'woo_ce' ); ?></p>
<?php } ?>
		</div>
		<!-- .inside -->
	</div>
	<!-- .postbox -->

	<?php do_action( 'woo_ce_after_modules' ); ?>

</div>
<!-- #poststuff -->