<?php
function woo_ce_file_encoding( $content = '' ) {

	global $export;

	if( function_exists( 'mb_convert_encoding' ) ) {
		$to_encoding = $export->encoding;
		$from_encoding = 'auto';
		if( !empty( $to_encoding ) )
			$content = mb_convert_encoding( trim( $content ), $to_encoding, $from_encoding );
		if( $to_encoding <> 'UTF-8' )
			$content = utf8_encode( $content );
	}
	return $content;

}

function woo_ce_display_memory( $memory = 0 ) {

	$output = '-';
	if( !empty( $output ) )
		$output = sprintf( __( '%s MB', 'woo_ce' ), $memory );
	echo $output;

}

function woo_ce_display_time_elapsed( $from, $to ) {

	$output = __( '1 second', 'woo_ce' );
	$time = $to - $from;
	$tokens = array (
		31536000 => __( 'year', 'woo_ce' ),
		2592000 => __( 'month', 'woo_ce' ),
		604800 => __( 'week', 'woo_ce' ),
		86400 => __( 'day', 'woo_ce' ),
		3600 => __( 'hour', 'woo_ce' ),
		60 => __( 'minute', 'woo_ce' ),
		1 => __( 'second', 'woo_ce' )
	);
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		$output = $numberOfUnits . ' ' . $text . ( ( $numberOfUnits > 1 ) ? 's' : '' );
	}
	return $output;

}

// This function escapes all cells in 'Excel' CSV escape formatting of a CSV file, also converts HTML entities to plain-text
function woo_ce_escape_csv_value( $string = '', $delimiter = ',', $format = 'all' ) {

	$string = str_replace( '"', '""', $string );
	$string = wp_specialchars_decode( $string );
	$string = str_replace( PHP_EOL, "\r\n", $string );
	switch( $format ) {

		case 'all':
			$string = '"' . $string . '"';
			break;

		case 'excel':
			if( strpos( $string, '"' ) !== false or strpos( $string, ',' ) !== false or strpos( $string, "\r" ) !== false or strpos( $string, "\n" ) !== false )
				$string = '"' . $string . '"';
			break;

	}
	return $string;

}

function woo_ce_count_object( $object = 0, $exclude_post_types = array() ) {

	$count = 0;
	if( is_object( $object ) ) {
		if( $exclude_post_types ) {
			$size = count( $exclude_post_types );
			for( $i = 0; $i < $size; $i++ ) {
				if( isset( $object->$exclude_post_types[$i] ) )
					unset( $object->$exclude_post_types[$i] );
			}
		}
		if( !empty( $object ) ) {
			foreach( $object as $key => $item )
				$count = $item + $count;
		}
	} else {
		$count = $object;
	}
	return $count;

}

function woo_ce_convert_product_ids( $product_ids = null ) {

	global $export;

	$output = '';
	if( $product_ids ) {
		if( is_array( $product_ids ) ) {
			$size = count( $product_ids );
			for( $i = 0; $i < $size; $i++ )
				$output .= $product_ids[$i] . $export->category_separator;
			$output = substr( $output, 0, -1 );
		} else if( strstr( $product_ids, ',' ) ) {
			$output = str_replace( ',', $export->category_separator, $product_ids );
		}
	}
	return $output;

}

function woo_ce_format_visibility( $visibility = '' ) {

	$output = '';
	if( $visibility ) {
		switch( $visibility ) {

			case 'visible':
				$output = __( 'Catalog & Search', 'woo_ce' );
				break;

			case 'catalog':
				$output = __( 'Catalog', 'woo_ce' );
				break;

			case 'search':
				$output = __( 'Search', 'woo_ce' );
				break;

			case 'hidden':
				$output = __( 'Hidden', 'woo_ce' );
				break;

		}
	}
	return $output;

}

function woo_ce_format_download_type( $download_type = '' ) {

	$output = __( 'Standard', 'woo_ce' );
	if( $download_type ) {
		switch( $download_type ) {

			case 'application':
				$output = __( 'Application', 'woo_ce' );
				break;

			case 'music':
				$output = __( 'Music', 'woo_ce' );
				break;

		}
	}
	return $output;

}

function woo_ce_format_product_status( $product_status = '' ) {

	$output = $product_status;
	switch( $product_status ) {

		case 'publish':
			$output = __( 'Publish', 'woo_ce' );
			break;

		case 'draft':
			$output = __( 'Draft', 'woo_ce' );
			break;

		case 'trash':
			$output = __( 'Trash', 'woo_ce' );
			break;

	}
	return $output;

}

function woo_ce_format_comment_status( $comment_status ) {

	$output = $comment_status;
	switch( $comment_status ) {

		case 'open':
			$output = __( 'Open', 'woo_ce' );
			break;

		case 'closed':
			$output = __( 'Closed', 'woo_ce' );
			break;

	}
	return $output;

}

function woo_ce_format_gpf_availability( $availability = null ) {

	$output = '';
	if( $availability ) {
		switch( $availability ) {

			case 'in stock':
				$output = __( 'In Stock', 'woo_ce' );
				break;

			case 'available for order':
				$output = __( 'Available For Order', 'woo_ce' );
				break;

			case 'preorder':
				$output = __( 'Pre-order', 'woo_ce' );
				break;

		}
	}
	return $output;

}

function woo_ce_format_gpf_condition( $condition ) {

	switch( $condition ) {

		case 'new':
			$output = __( 'New', 'woo_ce' );
			break;

		case 'refurbished':
			$output = __( 'Refurbished', 'woo_ce' );
			break;

		case 'used':
			$output = __( 'Used', 'woo_ce' );
			break;

	}
	return $output;

}

function woo_ce_format_switch( $input = '', $output_format = 'answer' ) {

	$input = strtolower( $input );
	switch( $input ) {

		case '1':
		case 'yes':
		case 'on':
		case 'open':
		case 'active':
			$input = '1';
			break;

		case '0':
		case 'no':
		case 'off':
		case 'closed':
		case 'inactive':
		default:
			$input = '0';
			break;

	}
	$output = '';
	switch( $output_format ) {

		case 'int':
			$output = $input;
			break;

		case 'answer':
			switch( $input ) {

				case '1':
					$output = __( 'Yes', 'woo_ce' );
					break;

				case '0':
					$output = __( 'No', 'woo_ce' );
					break;

			}
			break;

		case 'boolean':
			switch( $input ) {

				case '1':
					$output = 'on';
					break;

				case '0':
					$output = 'off';
					break;

			}
			break;

	}
	return $output;

}

function woo_ce_format_stock_status( $stock_status = '', $stock = '' ) {

	$output = '';
	if( empty( $stock_status ) && !empty( $stock ) ) {
		if( $stock )
			$stock_status = 'instock';
		else
			$stock_status = 'outofstock';
	}
	if( $stock_status ) {
		switch( $stock_status ) {

			case 'instock':
				$output = __( 'In Stock', 'woo_ce' );
				break;

			case 'outofstock':
				$output = __( 'Out of Stock', 'woo_ce' );
				break;

		}
	}
	return $output;

}

function woo_ce_format_tax_status( $tax_status = null ) {

	$output = '';
	if( $tax_status ) {
		switch( $tax_status ) {
	
			case 'taxable':
				$output = __( 'Taxable', 'woo_ce' );
				break;
	
			case 'shipping':
				$output = __( 'Shipping Only', 'woo_ce' );
				break;

			case 'none':
				$output = __( 'None', 'woo_ce' );
				break;

		}
	}
	return $output;

}

function woo_ce_format_tax_class( $tax_class = '' ) {

	global $export;

	$output = '';
	if( $tax_class ) {
		switch( $tax_class ) {

			case '*':
				$tax_class = __( 'Standard', 'woo_ce' );
				break;

			case 'reduced-rate':
				$tax_class = __( 'Reduced Rate', 'woo_ce' );
				break;

			case 'zero-rate':
				$tax_class = __( 'Zero Rate', 'woo_ce' );
				break;

		}
		$output = $tax_class;
	}
	return $output;

}

function woo_ce_format_product_filters( $product_filters = array() ) {

	$output = array();
	if( !empty( $product_filters ) ) {
		foreach( $product_filters as $product_filter )
			$output[] = $product_filter;
	}
	return $output;

}

function woo_ce_format_user_role_filters( $user_role_filters = array() ) {

	$output = array();
	if( !empty( $user_role_filters ) ) {
		foreach( $user_role_filters as $user_role_filter )
			$output[] = $user_role_filter;
	}
	return $output;

}

function woo_ce_format_user_role_label( $user_role = '' ) {

	global $wp_roles;

	$output = $user_role;
	if( $user_role ) {
		$user_roles = woo_ce_get_user_roles();
		if( isset( $user_roles[$user_role] ) )
			$output = ucfirst( $user_roles[$user_role]['name'] );
		unset( $user_roles );
	}
	return $output;

}

function woo_ce_format_product_type( $type_id = '' ) {

	$output = $type_id;
	if( $output ) {
		$product_types = apply_filters( 'woo_ce_format_product_types', array(
			'simple' => __( 'Simple Product', 'woocommerce' ),
			'downloadable' => __( 'Downloadable', 'woocommerce' ),
			'grouped' => __( 'Grouped Product', 'woocommerce' ),
			'virtual' => __( 'Virtual', 'woocommerce' ),
			'variable' => __( 'Variable', 'woocommerce' ),
			'external' => __( 'External/Affiliate Product', 'woocommerce' ),
			'variation' => __( 'Variation', 'woo_ce' )
		) );
		if( isset( $product_types[$type_id] ) )
			$output = $product_types[$type_id];
	}
	return $output;

}

function woo_ce_format_price( $price = '' ) {

	// Check that a valid price has been provided and that wc_format_localized_price() exists
	if( isset( $price ) && $price != '' && function_exists( 'wc_format_localized_price' ) )
		return wc_format_localized_price( $price );
	else
		return $price;

}

function woo_ce_format_sale_price_dates( $sale_date = '' ) {

	$output = $sale_date;
	if( $sale_date )
		$output = woo_ce_format_date( date( 'Y-m-d H:i:s', $sale_date ) );
	return $output;

}

function woo_ce_format_date( $date = '' ) {

	$output = $date;
	$date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );
	if( !empty( $date ) && $date_format != '' )
		$output = mysql2date( $date_format, $date );
	return $output;

}

function woo_ce_format_product_category_label( $product_category = '', $parent_category = '' ) {

	$output = $product_category;
	if( !empty( $parent_category ) )
		$output .= ' &raquo; ' . $parent_category;
	return $output;

}

if( !function_exists( 'woo_ce_expand_state_name' ) ) {
	function woo_ce_expand_state_name( $country_prefix = '', $state_prefix = '' ) {

		global $woocommerce;

		$output = $state_prefix;
		if( $output ) {
			if( isset( $woocommerce->countries ) ) {
				if( $states = $woocommerce->countries->get_states( $country_prefix ) ) {
					if( isset( $states[$state_prefix] ) )
						$output = $states[$state_prefix];
				}
				unset( $states );
			}
		}
		return $output;

	}
}

if( !function_exists( 'woo_ce_expand_country_name' ) ) {
	function woo_ce_expand_country_name( $country_prefix = '' ) {

		global $woocommerce;

		$output = $country_prefix;
		if( $output && method_exists( $woocommerce, 'countries' ) ) {
			$countries = $woocommerce->countries;
			if( isset( $countries[$country_prefix] ) )
				$output = $countries[$country_prefix];
			unset( $countries );
		}
		return $output;

	}
}
?>