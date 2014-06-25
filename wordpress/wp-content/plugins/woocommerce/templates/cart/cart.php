<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>
<script>
window.onload=function(){
var CountyHide = document.getElementById("CountyClip");
CountyHide.setAttribute("disabled","disabled");
CountyHide.style.visibility = "hidden"; 
var CityHide = document.getElementById("CityClip");
CityHide.setAttribute("disabled","disabled");
CityHide.style.visibility = "hidden";
};

function hidecounty(){
var CountyHide = document.getElementById("CountyClip");
CountyHide.setAttribute("disabled","disabled");
CountyHide.style.visibility = "hidden";
}

function hidecity(){
var CityHide = document.getElementById("CityClip");
CityHide.setAttribute("disabled","disabled");
CityHide.style.visibility = "hidden";
}

function showcounty(){
var CountyShow = document.getElementById("CountyClip");
CountyShow.removeAttribute("disabled");
CountyShow.style.visibility = "visible";
}

function showcity(){
var CityShow = document.getElementById("CityClip");
CityShow.removeAttribute("disabled");
CityShow.style.visibility = "visible";
}

function showMsg(select)
{
if(select.value == "ADMIN.DBO.COUNTIES_AHTD"){ 
showcounty();
hidecity();
}else if(select.value == "ADMIN.DBO.CITY_LIMITS_AHTD"){ 
hidecounty();
showcity();
}}
</script>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">


<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
<th class="product-sku"><?php _e( 'Feature Class', 'woocommerce' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
						?>
					</td>

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					</td>

					<td class="product-name">
						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
						?>
					</td>


<td><?php echo $_product->get_sku(); ?></td>

<input name="SmallClippee" type="hidden" form="FME" value="<?php echo $_product->get_sku(); ?>" />


					<td class="product-price">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>
					</td>

					<td class="product-subtotal">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e( 'Coupon', 'woocommerce' ); ?>:</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> <!--<input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" /> -->

				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<form action="http://cm-sas-geo-fme1.sas.arkgov.net/fmedatadownload/geostor_dev/geostor_vector-dl_dev.fmw" method="get" id="FME">
<input type="radio" name="opt_servicemode" value="async">     E-mail<br>
<input type="radio" name="opt_servicemode" value="sync">     Desktop (This may take a while...)
<hr>
<select name="Format" form="FME">
<option value="SHAPE">Shapefile</option>
<option value="GEODATABASE_FILE">File Geodatabase (Esri)</option>
<option value="GEODATABASE_MDB">Personal Geodatabase (Esri)</option>
<option value="DGNV8">Microstation Design V8</option>
<option value="ACAD">AutoCAD DXF/DWG</option>
<option value="DWF">AutoCAD DWF</option>
<option value="MITAB">MapInfo TAB</option>
<option value="GIF">GIF Image</option>
<option value="PDF2D">GeoPDF</option>
<option value="OGCKML">Keyhole Markup Language (KML)</option>
</select> Download File Type

<select name="Clipper" form="FME" oninput="showMsg(this)">
     <option selected="true" disabled="disabled">Select...</option>  
     <option value="ADMIN.DBO.COUNTIES_AHTD">County</option>
     <option value="ADMIN.DBO.CITY_LIMITS_AHTD">City</option>
</select> Clip By
<select name="WhereClause" form="FME" id="CountyClip">
     <option value="where COUNTY_NAM = &#039;White&#039;" class="cnty">White</option>
     <option value="where COUNTY_NAM = &#039;Pulaski&#039;" class="cnty">Pulaski</option>
     <option value="where COUNTY_NAM = &#039;Newton&#039;" class="cnty">Newton</option>
</select>
<select name="WhereClause" form="FME" id="CityClip" >
     <option value="where CITY_NAME = &#039;Conway&#039;">Conway</option>
     <option value="where CITY_NAME = &#039;Judsonia&#039;">Judsonia</option>
     <option value="where CITY_NAME = &#039;Little Rock&#039;">Little Rock</option>
</select>
<select name="CoordinateSystem" form="FME">
     <option value="EPSG:26915">NAD83 UTM- Zone 15N</option>
     <option value="LL-WGS84">WGS84 Lat/Long</option>
     <option value="LL-83">NAD83 Lat/Long</option>
     <option value="AR83-NF">Arkansas State Plane North Feet</option>
     <option value="AR83-SF">Arkansas State Plane South Feet</option>
</select>Projection


<input name="opt_requesteremail" type="text" value="If selected..." />Email
<input name="opt_responseformat" type="hidden" value="html"/>
<hr><input type="submit" value="Submit Request" />
</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	<?php woocommerce_cart_totals(); ?>

	<?php woocommerce_shipping_calculator(); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
