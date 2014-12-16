<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for disabled Shipping Class Sorting widget on Store Exporter screen
	function woo_ce_shipping_class_order_sorting() {

		$shipping_class_orderby = 'ID';
		$shipping_class_order = 'DESC';

		ob_start(); ?>
<p><label><?php _e( 'Shipping Class Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="shipping_class_orderby" disabled="disabled">
		<option value="id"<?php selected( 'id', $shipping_class_orderby ); ?>><?php _e( 'Term ID', 'woo_ce' ); ?></option>
		<option value="name"<?php selected( 'name', $shipping_class_orderby ); ?>><?php _e( 'Shipping Class Name', 'woo_ce' ); ?></option>
	</select>
	<select name="shipping_class_order" disabled="disabled">
		<option value="ASC"<?php selected( 'ASC', $shipping_class_order ); ?>><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"<?php selected( 'DESC', $shipping_class_order ); ?>><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Shipping Classes within the exported file. By default this is set to export Shipping Classes by Term ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Shipping Classes export columns
function woo_ce_get_shipping_class_fields( $format = 'full' ) {

	$export_type = 'shipping_class';

	$fields = array();
	$fields[] = array(
		'name' => 'term_id',
		'label' => __( 'Term ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Shipping Class Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'slug',
		'label' => __( 'Shipping Class Slug', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'description',
		'label' => __( 'Shipping Class Description', 'woo_ce' )
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
			return $fields;
			break;

	}

}
?>