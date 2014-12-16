<?php
function woo_ce_get_subscription_fields( $format = 'full' ) {

	$export_type = 'subscription';

	$fields = array();
	$fields[] = array(
		'name' => 'key',
		'label' => __( 'Subscription Key', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'status',
		'label' => __( 'Subscription Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Subscription Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user',
		'label' => __( 'User', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user_id',
		'label' => __( 'User ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'email',
		'label' => __( 'E-mail Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_id',
		'label' => __( 'Order ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_status',
		'label' => __( 'Order Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'post_status',
		'label' => __( 'Post Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'start_date',
		'label' => __( 'Start Date', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'expiration',
		'label' => __( 'Expiration', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'end_date',
		'label' => __( 'End Date', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'trial_end_date',
		'label' => __( 'Trial End Date', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'last_payment',
		'label' => __( 'Last Payment', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'next_payment',
		'label' => __( 'Next Payment', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'renewals',
		'label' => __( 'Renewals', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_id',
		'label' => __( 'Product ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_sku',
		'label' => __( 'Product SKU', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'variation_id',
		'label' => __( 'Variation ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'coupon',
		'label' => __( 'Coupon Code', 'woo_ce' )
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
