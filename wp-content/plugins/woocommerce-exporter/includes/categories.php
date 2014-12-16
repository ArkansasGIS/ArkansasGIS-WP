<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for Category Sorting widget on Store Exporter screen
	function woo_ce_category_order_sorting() {

		$category_orderby = woo_ce_get_option( 'category_orderby', 'ID' );
		$category_order = woo_ce_get_option( 'category_order', 'DESC' );

		ob_start(); ?>
<p><label><?php _e( 'Category Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="category_orderby">
		<option value="id"<?php selected( 'id', $category_orderby ); ?>><?php _e( 'Term ID', 'woo_ce' ); ?></option>
		<option value="name"<?php selected( 'name', $category_orderby ); ?>><?php _e( 'Category Name', 'woo_ce' ); ?></option>
	</select>
	<select name="category_order">
		<option value="ASC"<?php selected( 'ASC', $category_order ); ?>><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"<?php selected( 'DESC', $category_order ); ?>><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Categories within the exported file. By default this is set to export Categories by Term ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Category export columns
function woo_ce_get_category_fields( $format = 'full' ) {

	$export_type = 'category';

	$fields = array();
	$fields[] = array(
		'name' => 'term_id',
		'label' => __( 'Term ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Category Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'slug',
		'label' => __( 'Category Slug', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'parent_id',
		'label' => __( 'Parent Term ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'description',
		'label' => __( 'Category Description', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'display_type',
		'label' => __( 'Display Type', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'image',
		'label' => __( 'Category Image', 'woo_ce' )
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' )
	);
*/

	// Allow Plugin/Theme authors to add support for additional columns
	$fields = apply_filters( 'woo_ce_' . $export_type . '_fields', $fields, $export_type );

	if( $remember = woo_ce_get_option( $export_type . '_fields', array() ) ) {
		$remember = maybe_unserialize( $remember );
		$size = count( $fields );
		for( $i = 0; $i < $size; $i++ ) {
			$fields[$i]['disabled'] = ( isset( $fields[$i]['disabled'] ) ? $fields[$i]['disabled'] : 0 );
			$fields[$i]['default'] = 1;
			if( !array_key_exists( $fields[$i]['name'], $remember ) )
				$fields[$i]['default'] = 0;
		}
	}

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

function woo_ce_override_category_field_labels( $fields = array() ) {

	$labels = woo_ce_get_option( 'category_labels', array() );
	if( !empty( $labels ) ) {
		foreach( $fields as $key => $field ) {
			if( isset( $labels[$field['name']] ) )
				$fields[$key]['label'] = $labels[$field['name']];
		}
	}
	return $fields;

}
add_filter( 'woo_ce_category_fields', 'woo_ce_override_category_field_labels', 11 );

// Returns the export column header label based on an export column slug
function woo_ce_get_category_field( $name = null, $format = 'name' ) {

	$output = '';
	if( $name ) {
		$fields = woo_ce_get_category_fields();
		$size = count( $fields );
		for( $i = 0; $i < $size; $i++ ) {
			if( $fields[$i]['name'] == $name ) {
				switch( $format ) {

					case 'name':
						$output = $fields[$i]['label'];
						break;

					case 'full':
						$output = $fields[$i];
						break;

				}
				$i = $size;
			}
		}
	}
	return $output;

}

// Returns a list of WooCommerce Product Categories to export process
function woo_ce_get_product_categories( $args = array() ) {

	$term_taxonomy = 'product_cat';
	$defaults = array(
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => 0
	);
	$args = wp_parse_args( $args, $defaults );
	$categories = get_terms( $term_taxonomy, $args );
	if( !empty( $categories ) && is_wp_error( $categories ) == false ) {
		foreach( $categories as $key => $category ) {
			$categories[$key]->parent_name = '';
			if( $categories[$key]->parent_id = $category->parent ) {
				if( $parent_category = get_term( $categories[$key]->parent_id, $term_taxonomy ) ) {
					$categories[$key]->parent_name = $parent_category->name;
				}
				unset( $parent_category );
			} else {
				$categories[$key]->parent_id = '';
			}
			$categories[$key]->image = woo_ce_get_category_thumbnail_url( $category->term_id );
			$categories[$key]->display_type = get_woocommerce_term_meta( $category->term_id, 'display_type', true );
		}
		return $categories;
	}

}

function woo_ce_get_category_thumbnail_url( $category_id = 0, $size = 'full' ) {

	if ( $thumbnail_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true ) ) {
		$image_attributes = wp_get_attachment_image_src( $thumbnail_id, $size );
		if( is_array( $image_attributes ) )
			return current( $image_attributes );
	}

}
?>