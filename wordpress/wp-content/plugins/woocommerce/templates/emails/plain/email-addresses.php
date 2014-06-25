<?php
/**
 * Email Addresses (plain)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo "\n" . __( 'Billing address', 'woocommerce' ) . ":\n";
echo $order->get_formatted_billing_address() . "\n\n";

if ( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) :

	echo __( 'Shipping address', 'woocommerce' ) . ":\n";

	echo $shipping . "\n\n";

endif;