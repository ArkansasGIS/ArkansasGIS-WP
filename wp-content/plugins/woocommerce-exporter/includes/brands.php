<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for Coupon Sorting widget on Store Exporter screen
	function woo_ce_brands_brand_sorting() {

		$orderby = woo_ce_get_option( 'brand_orderby', 'ID' );
		$order = woo_ce_get_option( 'brand_order', 'DESC' );

		ob_start(); ?>
<p><label><?php _e( 'Brand Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="brand_orderby" disabled="disabled">
		<option value="id"><?php _e( 'Term ID', 'woo_ce' ); ?></option>
		<option value="name"><?php _e( 'Brand Name', 'woo_ce' ); ?></option>
	</select>
	<select name="brand_order" disabled="disabled">
		<option value="ASC"><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Brands within the exported file. By default this is set to export Product Brands by Term ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Brand export columns
function woo_ce_get_brand_fields( $format = 'full' ) {

	$export_type = 'brand';

	$fields = array();
	$fields[] = array(
		'name' => 'term_id',
		'label' => __( 'Term ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Brand Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'slug',
		'label' => __( 'Brand Slug', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'parent_id',
		'label' => __( 'Parent Term ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'description',
		'label' => __( 'Brand Description', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'image',
		'label' => __( 'Brand Image', 'woo_ce' )
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

function woo_ce_override_brand_field_labels( $fields = array() ) {

	$labels = woo_ce_get_option( 'brand_labels', array() );
	if( !empty( $labels ) ) {
		foreach( $fields as $key => $field ) {
			if( isset( $labels[$field['name']] ) )
				$fields[$key]['label'] = $labels[$field['name']];
		}
	}
	return $fields;

}
add_filter( 'woo_ce_brand_fields', 'woo_ce_override_brand_field_labels', 11 );
?>