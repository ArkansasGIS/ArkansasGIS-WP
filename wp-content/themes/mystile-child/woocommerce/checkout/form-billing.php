<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="woocommerce-billing-fields">
	
	<?PHP 
	$feature_type = array();
	foreach (WC()->cart->get_cart() as $cart_item_key => $values){
		$_product = $values['data'];
		if($_product->get_attribute('imagery')){
			array_push($feature_type,'imagery');
		}else{
			array_push($feature_type,'vector');
		}
		
		if(in_array('imagery',$feature_type)){
			$imagery_style = 'block';
		}else{
			$imagery_style = 'none';
		}
		if(in_array('vector',$feature_type)){
			$vector_style = 'block';
		}else{
			$vector_style = 'none';
		}
		
	} ?>
	<?php if ( WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

	<?php else : ?>

		<h3><?php _e( 'Billing Details', 'woocommerce' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>

		<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>

	<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

		<?php if ( $checkout->enable_guest_checkout ) : ?>

			<p class="form-row form-row-wide create-account">
				<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'woocommerce' ); ?></label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

			<div class="create-account">

				<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

				<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

				<?php endforeach; ?>

				<div class="clear"></div>

			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

	<?php endif; ?>
</div>
<!-- RDP added to enable proper clipper from user preferences GEOSTOREDTIS -->
<script type="text/javascript">
	var hasImagery = '<?php echo $_product->get_attribute('imagery'); ?>';
	var cliptype = document.getElementById('clip_type').value;
	document.getElementById('county_clipper_field').style.display = 'none';
	document.getElementById('city_clipper_field').style.display = 'none';
	document.getElementById('extent_clipper_field').style.display = 'none';
	document.getElementById('raster_format_type_field').style.display = "<?=$imagery_style; ?>";
	document.getElementById('vector_format_type_field').style.display = "<?=$vector_style; ?>";
	switch(cliptype) {
		case 'County':
			document.getElementById('county_clipper_field').style.display = 'block';
			break;
		case 'City':
			document.getElementById('city_clipper_field').style.display = 'block';
			break;
		case 'Extent':
			document.getElementById('clip_type').value = '';
			break;
		case 'State':
			document.getElementById('clip_type').value = '';
			break;
	}</script>