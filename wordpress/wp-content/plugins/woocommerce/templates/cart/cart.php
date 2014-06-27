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

<!-- Added to get SKU (Feature Class Name) into the cart- TD 20140625 -->
<td><?php echo $_product->get_sku(); ?></td>
<!-- Moved to here in order to programatically get the SKU (Feature Class Name) for FME -->
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

				<!-- <input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> --> <!--<input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" /> -->

				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>

			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>
<!--  Added this here in order to take advantage of formatting on the cart page- TD 20140625 -->
<form action="http://cm-sas-geo-fme1.sas.arkgov.net/fmedatadownload/geostor_dev/geostor_vector-dl_dev.fmw" method="get" id="FME">
E-mail  <input type="radio" name="opt_servicemode" value="async"> or 
Desktop (This may take a while...)  <input type="radio" name="opt_servicemode" value="sync"><BR><BR>
Download File Type  <select name="Format" form="FME">
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
</select><BR><BR>

Clip By  <select name="Clipper" form="FME" oninput="showMsg(this)">
     <option selected="true" disabled="disabled">Select...</option>  
     <option value="ADMIN.DBO.COUNTIES_AHTD">County</option>
     <option value="ADMIN.DBO.CITY_LIMITS_AHTD">City</option>
</select> 
<select name="WhereClause" form="FME" id="CountyClip">
     <option value="where COUNTY_NAM = &#039;Arkansas&#039;" class="cnty">Arkansas</option>
<option value="where COUNTY_NAM = &#039;Ashley&#039;" class="cnty">Ashley</option>
<option value="where COUNTY_NAM = &#039;Baxter&#039;" class="cnty">Baxter</option>
<option value="where COUNTY_NAM = &#039;Benton&#039;" class="cnty">Benton</option>
<option value="where COUNTY_NAM = &#039;Boone&#039;" class="cnty">Boone</option>
<option value="where COUNTY_NAM = &#039;Bradley&#039;" class="cnty">Bradley</option>
<option value="where COUNTY_NAM = &#039;Calhoun&#039;" class="cnty">Calhoun</option>
<option value="where COUNTY_NAM = &#039;Carroll&#039;" class="cnty">Carroll</option>
<option value="where COUNTY_NAM = &#039;Chicot&#039;" class="cnty">Chicot</option>
<option value="where COUNTY_NAM = &#039;Clark&#039;" class="cnty">Clark</option>
<option value="where COUNTY_NAM = &#039;Clay&#039;" class="cnty">Clay</option>
<option value="where COUNTY_NAM = &#039;Cleburne&#039;" class="cnty">Cleburne</option>
<option value="where COUNTY_NAM = &#039;Cleveland&#039;" class="cnty">Cleveland</option>
<option value="where COUNTY_NAM = &#039;Columbia&#039;" class="cnty">Columbia</option>
<option value="where COUNTY_NAM = &#039;Conway&#039;" class="cnty">Conway</option>
<option value="where COUNTY_NAM = &#039;Craighead&#039;" class="cnty">Craighead</option>
<option value="where COUNTY_NAM = &#039;Crawford&#039;" class="cnty">Crawford</option>
<option value="where COUNTY_NAM = &#039;Crittenden&#039;" class="cnty">Crittenden</option>
<option value="where COUNTY_NAM = &#039;Cross&#039;" class="cnty">Cross</option>
<option value="where COUNTY_NAM = &#039;Dallas&#039;" class="cnty">Dallas</option>
<option value="where COUNTY_NAM = &#039;Desha&#039;" class="cnty">Desha</option>
<option value="where COUNTY_NAM = &#039;Drew&#039;" class="cnty">Drew</option>
<option value="where COUNTY_NAM = &#039;Faulkner&#039;" class="cnty">Faulkner</option>
<option value="where COUNTY_NAM = &#039;Franklin&#039;" class="cnty">Franklin</option>
<option value="where COUNTY_NAM = &#039;Fulton&#039;" class="cnty">Fulton</option>
<option value="where COUNTY_NAM = &#039;Garland&#039;" class="cnty">Garland</option>
<option value="where COUNTY_NAM = &#039;Grant&#039;" class="cnty">Grant</option>
<option value="where COUNTY_NAM = &#039;Greene&#039;" class="cnty">Greene</option>
<option value="where COUNTY_NAM = &#039;Hempstead&#039;" class="cnty">Hempstead</option>
<option value="where COUNTY_NAM = &#039;Hot Spring&#039;" class="cnty">Hot Spring</option>
<option value="where COUNTY_NAM = &#039;Howard&#039;" class="cnty">Howard</option>
<option value="where COUNTY_NAM = &#039;Independence&#039;" class="cnty">Independence</option>
<option value="where COUNTY_NAM = &#039;Izard&#039;" class="cnty">Izard</option>
<option value="where COUNTY_NAM = &#039;Jackson&#039;" class="cnty">Jackson</option>
<option value="where COUNTY_NAM = &#039;Jefferson&#039;" class="cnty">Jefferson</option>
<option value="where COUNTY_NAM = &#039;Johnson&#039;" class="cnty">Johnson</option>
<option value="where COUNTY_NAM = &#039;Lafayette&#039;" class="cnty">Lafayette</option>
<option value="where COUNTY_NAM = &#039;Lawrence&#039;" class="cnty">Lawrence</option>
<option value="where COUNTY_NAM = &#039;Lee&#039;" class="cnty">Lee</option>
<option value="where COUNTY_NAM = &#039;Lincoln&#039;" class="cnty">Lincoln</option>
<option value="where COUNTY_NAM = &#039;Little River&#039;" class="cnty">Little River</option>
<option value="where COUNTY_NAM = &#039;Logan&#039;" class="cnty">Logan</option>
<option value="where COUNTY_NAM = &#039;Lonoke&#039;" class="cnty">Lonoke</option>
<option value="where COUNTY_NAM = &#039;Madison&#039;" class="cnty">Madison</option>
<option value="where COUNTY_NAM = &#039;Marion&#039;" class="cnty">Marion</option>
<option value="where COUNTY_NAM = &#039;Miller&#039;" class="cnty">Miller</option>
<option value="where COUNTY_NAM = &#039;Mississippi&#039;" class="cnty">Mississippi</option>
<option value="where COUNTY_NAM = &#039;Monroe&#039;" class="cnty">Monroe</option>
<option value="where COUNTY_NAM = &#039;Montgomery&#039;" class="cnty">Montgomery</option>
<option value="where COUNTY_NAM = &#039;Nevada&#039;" class="cnty">Nevada</option>
<option value="where COUNTY_NAM = &#039;Newton&#039;" class="cnty">Newton</option>
<option value="where COUNTY_NAM = &#039;Ouachita&#039;" class="cnty">Ouachita</option>
<option value="where COUNTY_NAM = &#039;Perry&#039;" class="cnty">Perry</option>
<option value="where COUNTY_NAM = &#039;Phillips&#039;" class="cnty">Phillips</option>
<option value="where COUNTY_NAM = &#039;Pike&#039;" class="cnty">Pike</option>
<option value="where COUNTY_NAM = &#039;Poinsett&#039;" class="cnty">Poinsett</option>
<option value="where COUNTY_NAM = &#039;Polk&#039;" class="cnty">Polk</option>
<option value="where COUNTY_NAM = &#039;Pope&#039;" class="cnty">Pope</option>
<option value="where COUNTY_NAM = &#039;Prairie&#039;" class="cnty">Prairie</option>
<option value="where COUNTY_NAM = &#039;Pulaski&#039;" class="cnty">Pulaski</option>
<option value="where COUNTY_NAM = &#039;Randolph&#039;" class="cnty">Randolph</option>
<option value="where COUNTY_NAM = &#039;St. Francis&#039;" class="cnty">St. Francis</option>
<option value="where COUNTY_NAM = &#039;Saline&#039;" class="cnty">Saline</option>
<option value="where COUNTY_NAM = &#039;Scott&#039;" class="cnty">Scott</option>
<option value="where COUNTY_NAM = &#039;Searcy&#039;" class="cnty">Searcy</option>
<option value="where COUNTY_NAM = &#039;Sebastian&#039;" class="cnty">Sebastian</option>
<option value="where COUNTY_NAM = &#039;Sevier&#039;" class="cnty">Sevier</option>
<option value="where COUNTY_NAM = &#039;Sharp&#039;" class="cnty">Sharp</option>
<option value="where COUNTY_NAM = &#039;Stone&#039;" class="cnty">Stone</option>
<option value="where COUNTY_NAM = &#039;Union&#039;" class="cnty">Union</option>
<option value="where COUNTY_NAM = &#039;Van Buren&#039;" class="cnty">Van Buren</option>
<option value="where COUNTY_NAM = &#039;Washington&#039;" class="cnty">Washington</option>
<option value="where COUNTY_NAM = &#039;White&#039;" class="cnty">White</option>
<option value="where COUNTY_NAM = &#039;Woodruff&#039;" class="cnty">Woodruff</option>
<option value="where COUNTY_NAM = &#039;Yell&#039;" class="cnty">Yell</option>
</select>
<select name="WhereClause" form="FME" id="CityClip" >
     <option value="where CITY_NAME = &#039;Adona&#039;">Adona</option>
<option value="where CITY_NAME = &#039;Alexander&#039;">Alexander</option>
<option value="where CITY_NAME = &#039;Alicia&#039;">Alicia</option>
<option value="where CITY_NAME = &#039;Allport&#039;">Allport</option>
<option value="where CITY_NAME = &#039;Alma&#039;">Alma</option>
<option value="where CITY_NAME = &#039;Almyra&#039;">Almyra</option>
<option value="where CITY_NAME = &#039;Alpena&#039;">Alpena</option>
<option value="where CITY_NAME = &#039;Altheimer&#039;">Altheimer</option>
<option value="where CITY_NAME = &#039;Altus&#039;">Altus</option>
<option value="where CITY_NAME = &#039;Amagon&#039;">Amagon</option>
<option value="where CITY_NAME = &#039;Amity&#039;">Amity</option>
<option value="where CITY_NAME = &#039;Anthonyville&#039;">Anthonyville</option>
<option value="where CITY_NAME = &#039;Antoine&#039;">Antoine</option>
<option value="where CITY_NAME = &#039;Arkadelphia&#039;">Arkadelphia</option>
<option value="where CITY_NAME = &#039;Arkansas City&#039;">Arkansas City</option>
<option value="where CITY_NAME = &#039;Ash Flat&#039;">Ash Flat</option>
<option value="where CITY_NAME = &#039;Ashdown&#039;">Ashdown</option>
<option value="where CITY_NAME = &#039;Atkins&#039;">Atkins</option>
<option value="where CITY_NAME = &#039;Aubrey&#039;">Aubrey</option>
<option value="where CITY_NAME = &#039;Augusta&#039;">Augusta</option>
<option value="where CITY_NAME = &#039;Austin&#039;">Austin</option>
<option value="where CITY_NAME = &#039;Avoca&#039;">Avoca</option>
<option value="where CITY_NAME = &#039;Bald Knob&#039;">Bald Knob</option>
<option value="where CITY_NAME = &#039;Banks&#039;">Banks</option>
<option value="where CITY_NAME = &#039;Barling&#039;">Barling</option>
<option value="where CITY_NAME = &#039;Bassett&#039;">Bassett</option>
<option value="where CITY_NAME = &#039;Batesville&#039;">Batesville</option>
<option value="where CITY_NAME = &#039;Bauxite&#039;">Bauxite</option>
<option value="where CITY_NAME = &#039;Bay&#039;">Bay</option>
<option value="where CITY_NAME = &#039;Bearden&#039;">Bearden</option>
<option value="where CITY_NAME = &#039;Beaver&#039;">Beaver</option>
<option value="where CITY_NAME = &#039;Beebe&#039;">Beebe</option>
<option value="where CITY_NAME = &#039;Beedeville&#039;">Beedeville</option>
<option value="where CITY_NAME = &#039;Bella Vista&#039;">Bella Vista</option>
<option value="where CITY_NAME = &#039;Bellefonte&#039;">Bellefonte</option>
<option value="where CITY_NAME = &#039;Belleville&#039;">Belleville</option>
<option value="where CITY_NAME = &#039;Ben Lomond&#039;">Ben Lomond</option>
<option value="where CITY_NAME = &#039;Benton&#039;">Benton</option>
<option value="where CITY_NAME = &#039;Bentonville&#039;">Bentonville</option>
<option value="where CITY_NAME = &#039;Bergman&#039;">Bergman</option>
<option value="where CITY_NAME = &#039;Berryville&#039;">Berryville</option>
<option value="where CITY_NAME = &#039;Bethel Heights&#039;">Bethel Heights</option>
<option value="where CITY_NAME = &#039;Big Flat&#039;">Big Flat</option>
<option value="where CITY_NAME = &#039;Bigelow&#039;">Bigelow</option>
<option value="where CITY_NAME = &#039;Biggers&#039;">Biggers</option>
<option value="where CITY_NAME = &#039;Birdsong&#039;">Birdsong</option>
<option value="where CITY_NAME = &#039;Biscoe&#039;">Biscoe</option>
<option value="where CITY_NAME = &#039;Black Oak&#039;">Black Oak</option>
<option value="where CITY_NAME = &#039;Black Rock&#039;">Black Rock</option>
<option value="where CITY_NAME = &#039;Black Springs&#039;">Black Springs</option>
<option value="where CITY_NAME = &#039;Blevins&#039;">Blevins</option>
<option value="where CITY_NAME = &#039;Blue Eye&#039;">Blue Eye</option>
<option value="where CITY_NAME = &#039;Blue Mountain&#039;">Blue Mountain</option>
<option value="where CITY_NAME = &#039;Bluff City&#039;">Bluff City</option>
<option value="where CITY_NAME = &#039;Blytheville&#039;">Blytheville</option>
<option value="where CITY_NAME = &#039;Bodcaw&#039;">Bodcaw</option>
<option value="where CITY_NAME = &#039;Bonanza&#039;">Bonanza</option>
<option value="where CITY_NAME = &#039;Bono&#039;">Bono</option>
<option value="where CITY_NAME = &#039;Booneville&#039;">Booneville</option>
<option value="where CITY_NAME = &#039;Bradford&#039;">Bradford</option>
<option value="where CITY_NAME = &#039;Bradley&#039;">Bradley</option>
<option value="where CITY_NAME = &#039;Branch&#039;">Branch</option>
<option value="where CITY_NAME = &#039;Briarcliff&#039;">Briarcliff</option>
<option value="where CITY_NAME = &#039;Brinkley&#039;">Brinkley</option>
<option value="where CITY_NAME = &#039;Brookland&#039;">Brookland</option>
<option value="where CITY_NAME = &#039;Bryant&#039;">Bryant</option>
<option value="where CITY_NAME = &#039;Buckner&#039;">Buckner</option>
<option value="where CITY_NAME = &#039;Bull Shoals&#039;">Bull Shoals</option>
<option value="where CITY_NAME = &#039;Burdette&#039;">Burdette</option>
<option value="where CITY_NAME = &#039;Cabot&#039;">Cabot</option>
<option value="where CITY_NAME = &#039;Caddo Valley&#039;">Caddo Valley</option>
<option value="where CITY_NAME = &#039;Caldwell&#039;">Caldwell</option>
<option value="where CITY_NAME = &#039;Cale&#039;">Cale</option>
<option value="where CITY_NAME = &#039;Calico Rock&#039;">Calico Rock</option>
<option value="where CITY_NAME = &#039;Calion&#039;">Calion</option>
<option value="where CITY_NAME = &#039;Camden&#039;">Camden</option>
<option value="where CITY_NAME = &#039;Cammack Village&#039;">Cammack Village</option>
<option value="where CITY_NAME = &#039;Campbell Station&#039;">Campbell Station</option>
<option value="where CITY_NAME = &#039;Cane Hill&#039;">Cane Hill</option>
<option value="where CITY_NAME = &#039;Caraway&#039;">Caraway</option>
<option value="where CITY_NAME = &#039;Carlisle&#039;">Carlisle</option>
<option value="where CITY_NAME = &#039;Carthage&#039;">Carthage</option>
<option value="where CITY_NAME = &#039;Casa&#039;">Casa</option>
<option value="where CITY_NAME = &#039;Cash&#039;">Cash</option>
<option value="where CITY_NAME = &#039;Caulksville&#039;">Caulksville</option>
<option value="where CITY_NAME = &#039;Cave City&#039;">Cave City</option>
<option value="where CITY_NAME = &#039;Cave Springs&#039;">Cave Springs</option>
<option value="where CITY_NAME = &#039;Cedarville&#039;">Cedarville</option>
<option value="where CITY_NAME = &#039;Centerton&#039;">Centerton</option>
<option value="where CITY_NAME = &#039;Central City&#039;">Central City</option>
<option value="where CITY_NAME = &#039;Charleston&#039;">Charleston</option>
<option value="where CITY_NAME = &#039;Cherokee Village&#039;">Cherokee Village</option>
<option value="where CITY_NAME = &#039;Cherry Valley&#039;">Cherry Valley</option>
<option value="where CITY_NAME = &#039;Chester&#039;">Chester</option>
<option value="where CITY_NAME = &#039;Chidester&#039;">Chidester</option>
<option value="where CITY_NAME = &#039;Cincinnatti&#039;">Cincinnatti</option>
<option value="where CITY_NAME = &#039;Clarendon&#039;">Clarendon</option>
<option value="where CITY_NAME = &#039;Clarkedale&#039;">Clarkedale</option>
<option value="where CITY_NAME = &#039;Clarksville&#039;">Clarksville</option>
<option value="where CITY_NAME = &#039;Clinton&#039;">Clinton</option>
<option value="where CITY_NAME = &#039;Coal Hill&#039;">Coal Hill</option>
<option value="where CITY_NAME = &#039;College City&#039;">College City</option>
<option value="where CITY_NAME = &#039;Colt&#039;">Colt</option>
<option value="where CITY_NAME = &#039;Concord&#039;">Concord</option>
<option value="where CITY_NAME = &#039;Conway&#039;">Conway</option>
<option value="where CITY_NAME = &#039;Corinth&#039;">Corinth</option>
<option value="where CITY_NAME = &#039;Corning&#039;">Corning</option>
<option value="where CITY_NAME = &#039;Cotter&#039;">Cotter</option>
<option value="where CITY_NAME = &#039;Cotton Plant&#039;">Cotton Plant</option>
<option value="where CITY_NAME = &#039;Cove&#039;">Cove</option>
<option value="where CITY_NAME = &#039;Coy&#039;">Coy</option>
<option value="where CITY_NAME = &#039;Crawfordsville&#039;">Crawfordsville</option>
<option value="where CITY_NAME = &#039;Crossett&#039;">Crossett</option>
<option value="where CITY_NAME = &#039;Cushman&#039;">Cushman</option>
<option value="where CITY_NAME = &#039;Daisy&#039;">Daisy</option>
<option value="where CITY_NAME = &#039;Damascus&#039;">Damascus</option>
<option value="where CITY_NAME = &#039;Danville&#039;">Danville</option>
<option value="where CITY_NAME = &#039;Dardanelle&#039;">Dardanelle</option>
<option value="where CITY_NAME = &#039;Datto&#039;">Datto</option>
<option value="where CITY_NAME = &#039;De Queen&#039;">De Queen</option>
<option value="where CITY_NAME = &#039;De Valls Bluff&#039;">De Valls Bluff</option>
<option value="where CITY_NAME = &#039;De Witt&#039;">De Witt</option>
<option value="where CITY_NAME = &#039;Decatur&#039;">Decatur</option>
<option value="where CITY_NAME = &#039;Delaplaine&#039;">Delaplaine</option>
<option value="where CITY_NAME = &#039;Delight&#039;">Delight</option>
<option value="where CITY_NAME = &#039;Dell&#039;">Dell</option>
<option value="where CITY_NAME = &#039;Denning&#039;">Denning</option>
<option value="where CITY_NAME = &#039;Dermott&#039;">Dermott</option>
<option value="where CITY_NAME = &#039;Des Arc&#039;">Des Arc</option>
<option value="where CITY_NAME = &#039;Diamond City&#039;">Diamond City</option>
<option value="where CITY_NAME = &#039;Diamondhead&#039;">Diamondhead</option>
<option value="where CITY_NAME = &#039;Diaz&#039;">Diaz</option>
<option value="where CITY_NAME = &#039;Dierks&#039;">Dierks</option>
<option value="where CITY_NAME = &#039;Donaldson&#039;">Donaldson</option>
<option value="where CITY_NAME = &#039;Dover&#039;">Dover</option>
<option value="where CITY_NAME = &#039;Dumas&#039;">Dumas</option>
<option value="where CITY_NAME = &#039;Durham&#039;">Durham</option>
<option value="where CITY_NAME = &#039;Dutch Mills&#039;">Dutch Mills</option>
<option value="where CITY_NAME = &#039;Dyer&#039;">Dyer</option>
<option value="where CITY_NAME = &#039;Dyess&#039;">Dyess</option>
<option value="where CITY_NAME = &#039;Earle&#039;">Earle</option>
<option value="where CITY_NAME = &#039;East Camden&#039;">East Camden</option>
<option value="where CITY_NAME = &#039;East End&#039;">East End</option>
<option value="where CITY_NAME = &#039;Edmondson&#039;">Edmondson</option>
<option value="where CITY_NAME = &#039;Egypt&#039;">Egypt</option>
<option value="where CITY_NAME = &#039;El Dorado&#039;">El Dorado</option>
<option value="where CITY_NAME = &#039;Elaine&#039;">Elaine</option>
<option value="where CITY_NAME = &#039;Elkins&#039;">Elkins</option>
<option value="where CITY_NAME = &#039;Elm Springs&#039;">Elm Springs</option>
<option value="where CITY_NAME = &#039;Emerson&#039;">Emerson</option>
<option value="where CITY_NAME = &#039;Emmet&#039;">Emmet</option>
<option value="where CITY_NAME = &#039;England&#039;">England</option>
<option value="where CITY_NAME = &#039;Enola&#039;">Enola</option>
<option value="where CITY_NAME = &#039;Etowah&#039;">Etowah</option>
<option value="where CITY_NAME = &#039;Eudora&#039;">Eudora</option>
<option value="where CITY_NAME = &#039;Eureka Springs&#039;">Eureka Springs</option>
<option value="where CITY_NAME = &#039;Evansville&#039;">Evansville</option>
<option value="where CITY_NAME = &#039;Evening Shade&#039;">Evening Shade</option>
<option value="where CITY_NAME = &#039;Everton&#039;">Everton</option>
<option value="where CITY_NAME = &#039;Fairfield Bay&#039;">Fairfield Bay</option>
<option value="where CITY_NAME = &#039;Fargo&#039;">Fargo</option>
<option value="where CITY_NAME = &#039;Farmington&#039;">Farmington</option>
<option value="where CITY_NAME = &#039;Fayetteville&#039;">Fayetteville</option>
<option value="where CITY_NAME = &#039;Felsenthal&#039;">Felsenthal</option>
<option value="where CITY_NAME = &#039;Fifty-Six&#039;">Fifty-Six</option>
<option value="where CITY_NAME = &#039;Fisher&#039;">Fisher</option>
<option value="where CITY_NAME = &#039;Flippin&#039;">Flippin</option>
<option value="where CITY_NAME = &#039;Fordyce&#039;">Fordyce</option>
<option value="where CITY_NAME = &#039;Foreman&#039;">Foreman</option>
<option value="where CITY_NAME = &#039;Forrest City&#039;">Forrest City</option>
<option value="where CITY_NAME = &#039;Fort Smith&#039;">Fort Smith</option>
<option value="where CITY_NAME = &#039;Fouke&#039;">Fouke</option>
<option value="where CITY_NAME = &#039;Fountain Hill&#039;">Fountain Hill</option>
<option value="where CITY_NAME = &#039;Fountain Lake&#039;">Fountain Lake</option>
<option value="where CITY_NAME = &#039;Fourche&#039;">Fourche</option>
<option value="where CITY_NAME = &#039;Franklin&#039;">Franklin</option>
<option value="where CITY_NAME = &#039;Friendship&#039;">Friendship</option>
<option value="where CITY_NAME = &#039;Fulton&#039;">Fulton</option>
<option value="where CITY_NAME = &#039;Garfield&#039;">Garfield</option>
<option value="where CITY_NAME = &#039;Garland&#039;">Garland</option>
<option value="where CITY_NAME = &#039;Garner&#039;">Garner</option>
<option value="where CITY_NAME = &#039;Gassville&#039;">Gassville</option>
<option value="where CITY_NAME = &#039;Gateway&#039;">Gateway</option>
<option value="where CITY_NAME = &#039;Gentry&#039;">Gentry</option>
<option value="where CITY_NAME = &#039;Georgetown&#039;">Georgetown</option>
<option value="where CITY_NAME = &#039;Gilbert&#039;">Gilbert</option>
<option value="where CITY_NAME = &#039;Gillett&#039;">Gillett</option>
<option value="where CITY_NAME = &#039;Gillham&#039;">Gillham</option>
<option value="where CITY_NAME = &#039;Gilmore&#039;">Gilmore</option>
<option value="where CITY_NAME = &#039;Glenwood&#039;">Glenwood</option>
<option value="where CITY_NAME = &#039;Goshen&#039;">Goshen</option>
<option value="where CITY_NAME = &#039;Gosnell&#039;">Gosnell</option>
<option value="where CITY_NAME = &#039;Gould&#039;">Gould</option>
<option value="where CITY_NAME = &#039;Grady&#039;">Grady</option>
<option value="where CITY_NAME = &#039;Grannis&#039;">Grannis</option>
<option value="where CITY_NAME = &#039;Gravette&#039;">Gravette</option>
<option value="where CITY_NAME = &#039;Green Forest&#039;">Green Forest</option>
<option value="where CITY_NAME = &#039;Greenbrier&#039;">Greenbrier</option>
<option value="where CITY_NAME = &#039;Greenland&#039;">Greenland</option>
<option value="where CITY_NAME = &#039;Greenway&#039;">Greenway</option>
<option value="where CITY_NAME = &#039;Greenwood&#039;">Greenwood</option>
<option value="where CITY_NAME = &#039;Greers Ferry&#039;">Greers Ferry</option>
<option value="where CITY_NAME = &#039;Griffithville&#039;">Griffithville</option>
<option value="where CITY_NAME = &#039;Grubbs&#039;">Grubbs</option>
<option value="where CITY_NAME = &#039;Guion&#039;">Guion</option>
<option value="where CITY_NAME = &#039;Gum Springs&#039;">Gum Springs</option>
<option value="where CITY_NAME = &#039;Gurdon&#039;">Gurdon</option>
<option value="where CITY_NAME = &#039;Guy&#039;">Guy</option>
<option value="where CITY_NAME = &#039;Hackett&#039;">Hackett</option>
<option value="where CITY_NAME = &#039;Hamburg&#039;">Hamburg</option>
<option value="where CITY_NAME = &#039;Hampton&#039;">Hampton</option>
<option value="where CITY_NAME = &#039;Hardy&#039;">Hardy</option>
<option value="where CITY_NAME = &#039;Harrell&#039;">Harrell</option>
<option value="where CITY_NAME = &#039;Harrisburg&#039;">Harrisburg</option>
<option value="where CITY_NAME = &#039;Harrison&#039;">Harrison</option>
<option value="where CITY_NAME = &#039;Hartford&#039;">Hartford</option>
<option value="where CITY_NAME = &#039;Hartman&#039;">Hartman</option>
<option value="where CITY_NAME = &#039;Haskell&#039;">Haskell</option>
<option value="where CITY_NAME = &#039;Hatfield&#039;">Hatfield</option>
<option value="where CITY_NAME = &#039;Havana&#039;">Havana</option>
<option value="where CITY_NAME = &#039;Haynes&#039;">Haynes</option>
<option value="where CITY_NAME = &#039;Hazen&#039;">Hazen</option>
<option value="where CITY_NAME = &#039;Heber Springs&#039;">Heber Springs</option>
<option value="where CITY_NAME = &#039;Hector&#039;">Hector</option>
<option value="where CITY_NAME = &#039;Helena-West Helena&#039;">Helena-West Helena</option>
<option value="where CITY_NAME = &#039;Hermitage&#039;">Hermitage</option>
<option value="where CITY_NAME = &#039;Hickory Ridge&#039;">Hickory Ridge</option>
<option value="where CITY_NAME = &#039;Higden&#039;">Higden</option>
<option value="where CITY_NAME = &#039;Higginson&#039;">Higginson</option>
<option value="where CITY_NAME = &#039;Highfill&#039;">Highfill</option>
<option value="where CITY_NAME = &#039;Highland&#039;">Highland</option>
<option value="where CITY_NAME = &#039;Hindsville&#039;">Hindsville</option>
<option value="where CITY_NAME = &#039;Holiday Island&#039;">Holiday Island</option>
<option value="where CITY_NAME = &#039;Holland&#039;">Holland</option>
<option value="where CITY_NAME = &#039;Holly Grove&#039;">Holly Grove</option>
<option value="where CITY_NAME = &#039;Hope&#039;">Hope</option>
<option value="where CITY_NAME = &#039;Horatio&#039;">Horatio</option>
<option value="where CITY_NAME = &#039;Horseshoe Bend&#039;">Horseshoe Bend</option>
<option value="where CITY_NAME = &#039;Horseshoe Lake&#039;">Horseshoe Lake</option>
<option value="where CITY_NAME = &#039;Hot Springs&#039;">Hot Springs</option>
<option value="where CITY_NAME = &#039;Hot Springs Village&#039;">Hot Springs Village</option>
<option value="where CITY_NAME = &#039;Houston&#039;">Houston</option>
<option value="where CITY_NAME = &#039;Hoxie&#039;">Hoxie</option>
<option value="where CITY_NAME = &#039;Hughes&#039;">Hughes</option>
<option value="where CITY_NAME = &#039;Humnoke&#039;">Humnoke</option>
<option value="where CITY_NAME = &#039;Humphrey&#039;">Humphrey</option>
<option value="where CITY_NAME = &#039;Hunter&#039;">Hunter</option>
<option value="where CITY_NAME = &#039;Huntington&#039;">Huntington</option>
<option value="where CITY_NAME = &#039;Huntsville&#039;">Huntsville</option>
<option value="where CITY_NAME = &#039;Huttig&#039;">Huttig</option>
<option value="where CITY_NAME = &#039;Imboden&#039;">Imboden</option>
<option value="where CITY_NAME = &#039;Jacksonport&#039;">Jacksonport</option>
<option value="where CITY_NAME = &#039;Jacksonville&#039;">Jacksonville</option>
<option value="where CITY_NAME = &#039;Jasper&#039;">Jasper</option>
<option value="where CITY_NAME = &#039;Jennette&#039;">Jennette</option>
<option value="where CITY_NAME = &#039;Jericho&#039;">Jericho</option>
<option value="where CITY_NAME = &#039;Jerome&#039;">Jerome</option>
<option value="where CITY_NAME = &#039;Johnson&#039;">Johnson</option>
<option value="where CITY_NAME = &#039;Joiner&#039;">Joiner</option>
<option value="where CITY_NAME = &#039;Jonesboro&#039;">Jonesboro</option>
<option value="where CITY_NAME = &#039;Judsonia&#039;">Judsonia</option>
<option value="where CITY_NAME = &#039;Junction City&#039;">Junction City</option>
<option value="where CITY_NAME = &#039;Keiser&#039;">Keiser</option>
<option value="where CITY_NAME = &#039;Kensett&#039;">Kensett</option>
<option value="where CITY_NAME = &#039;Keo&#039;">Keo</option>
<option value="where CITY_NAME = &#039;Kibler&#039;">Kibler</option>
<option value="where CITY_NAME = &#039;Kingsland&#039;">Kingsland</option>
<option value="where CITY_NAME = &#039;Knobel&#039;">Knobel</option>
<option value="where CITY_NAME = &#039;Knoxville&#039;">Knoxville</option>
<option value="where CITY_NAME = &#039;La Grange&#039;">La Grange</option>
<option value="where CITY_NAME = &#039;Lafe&#039;">Lafe</option>
<option value="where CITY_NAME = &#039;Lake City&#039;">Lake City</option>
<option value="where CITY_NAME = &#039;Lake View&#039;">Lake View</option>
<option value="where CITY_NAME = &#039;Lake Village&#039;">Lake Village</option>
<option value="where CITY_NAME = &#039;Lakeview&#039;">Lakeview</option>
<option value="where CITY_NAME = &#039;Lamar&#039;">Lamar</option>
<option value="where CITY_NAME = &#039;Lavaca&#039;">Lavaca</option>
<option value="where CITY_NAME = &#039;Leachville&#039;">Leachville</option>
<option value="where CITY_NAME = &#039;Lead Hill&#039;">Lead Hill</option>
<option value="where CITY_NAME = &#039;Leola&#039;">Leola</option>
<option value="where CITY_NAME = &#039;Lepanto&#039;">Lepanto</option>
<option value="where CITY_NAME = &#039;Leslie&#039;">Leslie</option>
<option value="where CITY_NAME = &#039;Letona&#039;">Letona</option>
<option value="where CITY_NAME = &#039;Lewisville&#039;">Lewisville</option>
<option value="where CITY_NAME = &#039;Lexa&#039;">Lexa</option>
<option value="where CITY_NAME = &#039;Lincoln&#039;">Lincoln</option>
<option value="where CITY_NAME = &#039;Litteral&#039;">Litteral</option>
<option value="where CITY_NAME = &#039;Little Flock&#039;">Little Flock</option>
<option value="where CITY_NAME = &#039;Little Rock&#039;">Little Rock</option>
<option value="where CITY_NAME = &#039;Lockesburg&#039;">Lockesburg</option>
<option value="where CITY_NAME = &#039;London&#039;">London</option>
<option value="where CITY_NAME = &#039;Lonoke&#039;">Lonoke</option>
<option value="where CITY_NAME = &#039;Lonsdale&#039;">Lonsdale</option>
<option value="where CITY_NAME = &#039;Lost Bridge Village&#039;">Lost Bridge Village</option>
<option value="where CITY_NAME = &#039;Louann&#039;">Louann</option>
<option value="where CITY_NAME = &#039;Lowell&#039;">Lowell</option>
<option value="where CITY_NAME = &#039;Luxora&#039;">Luxora</option>
<option value="where CITY_NAME = &#039;Lynn&#039;">Lynn</option>
<option value="where CITY_NAME = &#039;Madison&#039;">Madison</option>
<option value="where CITY_NAME = &#039;Magazine&#039;">Magazine</option>
<option value="where CITY_NAME = &#039;Magness&#039;">Magness</option>
<option value="where CITY_NAME = &#039;Magnolia&#039;">Magnolia</option>
<option value="where CITY_NAME = &#039;Malvern&#039;">Malvern</option>
<option value="where CITY_NAME = &#039;Mammoth Spring&#039;">Mammoth Spring</option>
<option value="where CITY_NAME = &#039;Manila&#039;">Manila</option>
<option value="where CITY_NAME = &#039;Mansfield&#039;">Mansfield</option>
<option value="where CITY_NAME = &#039;Marianna&#039;">Marianna</option>
<option value="where CITY_NAME = &#039;Marie&#039;">Marie</option>
<option value="where CITY_NAME = &#039;Marion&#039;">Marion</option>
<option value="where CITY_NAME = &#039;Marked Tree&#039;">Marked Tree</option>
<option value="where CITY_NAME = &#039;Marmaduke&#039;">Marmaduke</option>
<option value="where CITY_NAME = &#039;Marshall&#039;">Marshall</option>
<option value="where CITY_NAME = &#039;Marvell&#039;">Marvell</option>
<option value="where CITY_NAME = &#039;Maumelle&#039;">Maumelle</option>
<option value="where CITY_NAME = &#039;Mayflower&#039;">Mayflower</option>
<option value="where CITY_NAME = &#039;Maynard&#039;">Maynard</option>
<option value="where CITY_NAME = &#039;McCaskill&#039;">McCaskill</option>
<option value="where CITY_NAME = &#039;McCrory&#039;">McCrory</option>
<option value="where CITY_NAME = &#039;McDougal&#039;">McDougal</option>
<option value="where CITY_NAME = &#039;McGehee&#039;">McGehee</option>
<option value="where CITY_NAME = &#039;McNab&#039;">McNab</option>
<option value="where CITY_NAME = &#039;McNeil&#039;">McNeil</option>
<option value="where CITY_NAME = &#039;McRae&#039;">McRae</option>
<option value="where CITY_NAME = &#039;Melbourne&#039;">Melbourne</option>
<option value="where CITY_NAME = &#039;Mena&#039;">Mena</option>
<option value="where CITY_NAME = &#039;Menifee&#039;">Menifee</option>
<option value="where CITY_NAME = &#039;Midland&#039;">Midland</option>
<option value="where CITY_NAME = &#039;Midway&#039;">Midway</option>
<option value="where CITY_NAME = &#039;Mineral Springs&#039;">Mineral Springs</option>
<option value="where CITY_NAME = &#039;Minturn&#039;">Minturn</option>
<option value="where CITY_NAME = &#039;Mitchellville&#039;">Mitchellville</option>
<option value="where CITY_NAME = &#039;Monette&#039;">Monette</option>
<option value="where CITY_NAME = &#039;Monticello&#039;">Monticello</option>
<option value="where CITY_NAME = &#039;Montrose&#039;">Montrose</option>
<option value="where CITY_NAME = &#039;Moorefield&#039;">Moorefield</option>
<option value="where CITY_NAME = &#039;Moro&#039;">Moro</option>
<option value="where CITY_NAME = &#039;Morrilton&#039;">Morrilton</option>
<option value="where CITY_NAME = &#039;Morrison Bluff&#039;">Morrison Bluff</option>
<option value="where CITY_NAME = &#039;Morrow&#039;">Morrow</option>
<option value="where CITY_NAME = &#039;Mount Ida&#039;">Mount Ida</option>
<option value="where CITY_NAME = &#039;Mount Pleasant&#039;">Mount Pleasant</option>
<option value="where CITY_NAME = &#039;Mount Vernon&#039;">Mount Vernon</option>
<option value="where CITY_NAME = &#039;Mountain Home&#039;">Mountain Home</option>
<option value="where CITY_NAME = &#039;Mountain Pine&#039;">Mountain Pine</option>
<option value="where CITY_NAME = &#039;Mountain View&#039;">Mountain View</option>
<option value="where CITY_NAME = &#039;Mountainburg&#039;">Mountainburg</option>
<option value="where CITY_NAME = &#039;Mulberry&#039;">Mulberry</option>
<option value="where CITY_NAME = &#039;Murfreesboro&#039;">Murfreesboro</option>
<option value="where CITY_NAME = &#039;Nashville&#039;">Nashville</option>
<option value="where CITY_NAME = &#039;Newark&#039;">Newark</option>
<option value="where CITY_NAME = &#039;Newport&#039;">Newport</option>
<option value="where CITY_NAME = &#039;Nimmons&#039;">Nimmons</option>
<option value="where CITY_NAME = &#039;Norfork&#039;">Norfork</option>
<option value="where CITY_NAME = &#039;Norman&#039;">Norman</option>
<option value="where CITY_NAME = &#039;Norphlet&#039;">Norphlet</option>
<option value="where CITY_NAME = &#039;North Little Rock&#039;">North Little Rock</option>
<option value="where CITY_NAME = &#039;Oak Grove&#039;">Oak Grove</option>
<option value="where CITY_NAME = &#039;Oak Grove Heights&#039;">Oak Grove Heights</option>
<option value="where CITY_NAME = &#039;Oakhaven&#039;">Oakhaven</option>
<option value="where CITY_NAME = &#039;Oden&#039;">Oden</option>
<option value="where CITY_NAME = &#039;Ogden&#039;">Ogden</option>
<option value="where CITY_NAME = &#039;Oil Trough&#039;">Oil Trough</option>
<option value="where CITY_NAME = &#039;OKean&#039;">OKean</option>
<option value="where CITY_NAME = &#039;Okolona&#039;">Okolona</option>
<option value="where CITY_NAME = &#039;Ola&#039;">Ola</option>
<option value="where CITY_NAME = &#039;Omaha&#039;">Omaha</option>
<option value="where CITY_NAME = &#039;Oppelo&#039;">Oppelo</option>
<option value="where CITY_NAME = &#039;Osceola&#039;">Osceola</option>
<option value="where CITY_NAME = &#039;Oxford&#039;">Oxford</option>
<option value="where CITY_NAME = &#039;Ozan&#039;">Ozan</option>
<option value="where CITY_NAME = &#039;Ozark&#039;">Ozark</option>
<option value="where CITY_NAME = &#039;Ozark Acres&#039;">Ozark Acres</option>
<option value="where CITY_NAME = &#039;Palestine&#039;">Palestine</option>
<option value="where CITY_NAME = &#039;Pangburn&#039;">Pangburn</option>
<option value="where CITY_NAME = &#039;Paragould&#039;">Paragould</option>
<option value="where CITY_NAME = &#039;Paris&#039;">Paris</option>
<option value="where CITY_NAME = &#039;Parkdale&#039;">Parkdale</option>
<option value="where CITY_NAME = &#039;Parkin&#039;">Parkin</option>
<option value="where CITY_NAME = &#039;Patmos&#039;">Patmos</option>
<option value="where CITY_NAME = &#039;Patterson&#039;">Patterson</option>
<option value="where CITY_NAME = &#039;Pea Ridge&#039;">Pea Ridge</option>
<option value="where CITY_NAME = &#039;Peach Orchard&#039;">Peach Orchard</option>
<option value="where CITY_NAME = &#039;Perla&#039;">Perla</option>
<option value="where CITY_NAME = &#039;Perry&#039;">Perry</option>
<option value="where CITY_NAME = &#039;Perrytown&#039;">Perrytown</option>
<option value="where CITY_NAME = &#039;Perryville&#039;">Perryville</option>
<option value="where CITY_NAME = &#039;Piggott&#039;">Piggott</option>
<option value="where CITY_NAME = &#039;Pindall&#039;">Pindall</option>
<option value="where CITY_NAME = &#039;Pine Bluff&#039;">Pine Bluff</option>
<option value="where CITY_NAME = &#039;Pineville&#039;">Pineville</option>
<option value="where CITY_NAME = &#039;Plainview&#039;">Plainview</option>
<option value="where CITY_NAME = &#039;Pleasant Plains&#039;">Pleasant Plains</option>
<option value="where CITY_NAME = &#039;Plumerville&#039;">Plumerville</option>
<option value="where CITY_NAME = &#039;Pocahontas&#039;">Pocahontas</option>
<option value="where CITY_NAME = &#039;Pollard&#039;">Pollard</option>
<option value="where CITY_NAME = &#039;Portia&#039;">Portia</option>
<option value="where CITY_NAME = &#039;Portland&#039;">Portland</option>
<option value="where CITY_NAME = &#039;Pottsville&#039;">Pottsville</option>
<option value="where CITY_NAME = &#039;Powhatan&#039;">Powhatan</option>
<option value="where CITY_NAME = &#039;Poyen&#039;">Poyen</option>
<option value="where CITY_NAME = &#039;Prairie Creek&#039;">Prairie Creek</option>
<option value="where CITY_NAME = &#039;Prairie Grove&#039;">Prairie Grove</option>
<option value="where CITY_NAME = &#039;Prattsville&#039;">Prattsville</option>
<option value="where CITY_NAME = &#039;Prescott&#039;">Prescott</option>
<option value="where CITY_NAME = &#039;Pyatt&#039;">Pyatt</option>
<option value="where CITY_NAME = &#039;Quitman&#039;">Quitman</option>
<option value="where CITY_NAME = &#039;Ratcliff&#039;">Ratcliff</option>
<option value="where CITY_NAME = &#039;Ravenden&#039;">Ravenden</option>
<option value="where CITY_NAME = &#039;Ravenden Springs&#039;">Ravenden Springs</option>
<option value="where CITY_NAME = &#039;Reader&#039;">Reader</option>
<option value="where CITY_NAME = &#039;Rector&#039;">Rector</option>
<option value="where CITY_NAME = &#039;Redfield&#039;">Redfield</option>
<option value="where CITY_NAME = &#039;Reed&#039;">Reed</option>
<option value="where CITY_NAME = &#039;Reyno&#039;">Reyno</option>
<option value="where CITY_NAME = &#039;Rison&#039;">Rison</option>
<option value="where CITY_NAME = &#039;Rockport&#039;">Rockport</option>
<option value="where CITY_NAME = &#039;Roe&#039;">Roe</option>
<option value="where CITY_NAME = &#039;Rogers&#039;">Rogers</option>
<option value="where CITY_NAME = &#039;Rondo&#039;">Rondo</option>
<option value="where CITY_NAME = &#039;Rose Bud&#039;">Rose Bud</option>
<option value="where CITY_NAME = &#039;Rosston&#039;">Rosston</option>
<option value="where CITY_NAME = &#039;Rudy&#039;">Rudy</option>
<option value="where CITY_NAME = &#039;Russell&#039;">Russell</option>
<option value="where CITY_NAME = &#039;Russellville&#039;">Russellville</option>
<option value="where CITY_NAME = &#039;Saint Charles&#039;">Saint Charles</option>
<option value="where CITY_NAME = &#039;Saint Francis&#039;">Saint Francis</option>
<option value="where CITY_NAME = &#039;Saint Joe&#039;">Saint Joe</option>
<option value="where CITY_NAME = &#039;Saint Paul&#039;">Saint Paul</option>
<option value="where CITY_NAME = &#039;Salem&#039;">Salem</option>
<option value="where CITY_NAME = &#039;Salem Springs&#039;">Salem Springs</option>
<option value="where CITY_NAME = &#039;Salesville&#039;">Salesville</option>
<option value="where CITY_NAME = &#039;Scranton&#039;">Scranton</option>
<option value="where CITY_NAME = &#039;Searcy&#039;">Searcy</option>
<option value="where CITY_NAME = &#039;Sedgwick&#039;">Sedgwick</option>
<option value="where CITY_NAME = &#039;Shannon Hills&#039;">Shannon Hills</option>
<option value="where CITY_NAME = &#039;Sheridan&#039;">Sheridan</option>
<option value="where CITY_NAME = &#039;Sherrill&#039;">Sherrill</option>
<option value="where CITY_NAME = &#039;Sherwood&#039;">Sherwood</option>
<option value="where CITY_NAME = &#039;Shirley&#039;">Shirley</option>
<option value="where CITY_NAME = &#039;Sidney&#039;">Sidney</option>
<option value="where CITY_NAME = &#039;Siloam Springs&#039;">Siloam Springs</option>
<option value="where CITY_NAME = &#039;Smackover&#039;">Smackover</option>
<option value="where CITY_NAME = &#039;Smithville&#039;">Smithville</option>
<option value="where CITY_NAME = &#039;South Bend&#039;">South Bend</option>
<option value="where CITY_NAME = &#039;South Lead Hill&#039;">South Lead Hill</option>
<option value="where CITY_NAME = &#039;Sparkman&#039;">Sparkman</option>
<option value="where CITY_NAME = &#039;Springdale&#039;">Springdale</option>
<option value="where CITY_NAME = &#039;Springtown&#039;">Springtown</option>
<option value="where CITY_NAME = &#039;Stamps&#039;">Stamps</option>
<option value="where CITY_NAME = &#039;Star City&#039;">Star City</option>
<option value="where CITY_NAME = &#039;Stephens&#039;">Stephens</option>
<option value="where CITY_NAME = &#039;Strawberry&#039;">Strawberry</option>
<option value="where CITY_NAME = &#039;Strong&#039;">Strong</option>
<option value="where CITY_NAME = &#039;Stuttgart&#039;">Stuttgart</option>
<option value="where CITY_NAME = &#039;Subiaco&#039;">Subiaco</option>
<option value="where CITY_NAME = &#039;Success&#039;">Success</option>
<option value="where CITY_NAME = &#039;Sulphur City&#039;">Sulphur City</option>
<option value="where CITY_NAME = &#039;Sulphur Rock&#039;">Sulphur Rock</option>
<option value="where CITY_NAME = &#039;Sulphur Springs&#039;">Sulphur Springs</option>
<option value="where CITY_NAME = &#039;Summers&#039;">Summers</option>
<option value="where CITY_NAME = &#039;Summit&#039;">Summit</option>
<option value="where CITY_NAME = &#039;Sunset&#039;">Sunset</option>
<option value="where CITY_NAME = &#039;Swifton&#039;">Swifton</option>
<option value="where CITY_NAME = &#039;Taylor&#039;">Taylor</option>
<option value="where CITY_NAME = &#039;Texarkana&#039;">Texarkana</option>
<option value="where CITY_NAME = &#039;Thornton&#039;">Thornton</option>
<option value="where CITY_NAME = &#039;Tillar&#039;">Tillar</option>
<option value="where CITY_NAME = &#039;Tinsman&#039;">Tinsman</option>
<option value="where CITY_NAME = &#039;Tollette&#039;">Tollette</option>
<option value="where CITY_NAME = &#039;Tontitown&#039;">Tontitown</option>
<option value="where CITY_NAME = &#039;Traskwood&#039;">Traskwood</option>
<option value="where CITY_NAME = &#039;Trumann&#039;">Trumann</option>
<option value="where CITY_NAME = &#039;Tuckerman&#039;">Tuckerman</option>
<option value="where CITY_NAME = &#039;Tull&#039;">Tull</option>
<option value="where CITY_NAME = &#039;Tupelo&#039;">Tupelo</option>
<option value="where CITY_NAME = &#039;Turrell&#039;">Turrell</option>
<option value="where CITY_NAME = &#039;Twin Groves&#039;">Twin Groves</option>
<option value="where CITY_NAME = &#039;Tyronza&#039;">Tyronza</option>
<option value="where CITY_NAME = &#039;Ulm&#039;">Ulm</option>
<option value="where CITY_NAME = &#039;Valley Springs&#039;">Valley Springs</option>
<option value="where CITY_NAME = &#039;Van Buren&#039;">Van Buren</option>
<option value="where CITY_NAME = &#039;Vandervoort&#039;">Vandervoort</option>
<option value="where CITY_NAME = &#039;Victoria&#039;">Victoria</option>
<option value="where CITY_NAME = &#039;Vilonia&#039;">Vilonia</option>
<option value="where CITY_NAME = &#039;Viola&#039;">Viola</option>
<option value="where CITY_NAME = &#039;Wabbaseka&#039;">Wabbaseka</option>
<option value="where CITY_NAME = &#039;Waldenburg&#039;">Waldenburg</option>
<option value="where CITY_NAME = &#039;Waldo&#039;">Waldo</option>
<option value="where CITY_NAME = &#039;Waldron&#039;">Waldron</option>
<option value="where CITY_NAME = &#039;Walnut Ridge&#039;">Walnut Ridge</option>
<option value="where CITY_NAME = &#039;Ward&#039;">Ward</option>
<option value="where CITY_NAME = &#039;Warren&#039;">Warren</option>
<option value="where CITY_NAME = &#039;Washington&#039;">Washington</option>
<option value="where CITY_NAME = &#039;Watson&#039;">Watson</option>
<option value="where CITY_NAME = &#039;Weiner&#039;">Weiner</option>
<option value="where CITY_NAME = &#039;Weldon&#039;">Weldon</option>
<option value="where CITY_NAME = &#039;West Fork&#039;">West Fork</option>
<option value="where CITY_NAME = &#039;West Memphis&#039;">West Memphis</option>
<option value="where CITY_NAME = &#039;West Point&#039;">West Point</option>
<option value="where CITY_NAME = &#039;Western Grove&#039;">Western Grove</option>
<option value="where CITY_NAME = &#039;Wheatley&#039;">Wheatley</option>
<option value="where CITY_NAME = &#039;Whelen Springs&#039;">Whelen Springs</option>
<option value="where CITY_NAME = &#039;White Hall&#039;">White Hall</option>
<option value="where CITY_NAME = &#039;Wickes&#039;">Wickes</option>
<option value="where CITY_NAME = &#039;Widener&#039;">Widener</option>
<option value="where CITY_NAME = &#039;Wiederkehr Village&#039;">Wiederkehr Village</option>
<option value="where CITY_NAME = &#039;Williford&#039;">Williford</option>
<option value="where CITY_NAME = &#039;Willisville&#039;">Willisville</option>
<option value="where CITY_NAME = &#039;Wilmar&#039;">Wilmar</option>
<option value="where CITY_NAME = &#039;Wilmot&#039;">Wilmot</option>
<option value="where CITY_NAME = &#039;Wilson&#039;">Wilson</option>
<option value="where CITY_NAME = &#039;Wilton&#039;">Wilton</option>
<option value="where CITY_NAME = &#039;Winchester&#039;">Winchester</option>
<option value="where CITY_NAME = &#039;Winslow&#039;">Winslow</option>
<option value="where CITY_NAME = &#039;Winthrop&#039;">Winthrop</option>
<option value="where CITY_NAME = &#039;Wooster&#039;">Wooster</option>
<option value="where CITY_NAME = &#039;Wrightsville&#039;">Wrightsville</option>
<option value="where CITY_NAME = &#039;Wynne&#039;">Wynne</option>
<option value="where CITY_NAME = &#039;Yellville&#039;">Yellville</option>
<option value="where CITY_NAME = &#039;Zinc&#039;">Zinc</option>

</select><BR><BR>
Projection  <select name="CoordinateSystem" form="FME">
     <option value="EPSG:26915">NAD83 UTM- Zone 15N</option>
     <option value="LL-WGS84">WGS84 Lat/Long</option>
     <option value="LL-83">NAD83 Lat/Long</option>
     <option value="AR83-NF">Arkansas State Plane North Feet</option>
     <option value="AR83-SF">Arkansas State Plane South Feet</option>
</select><BR><BR>


Email  <input name="opt_requesteremail" type="text" value="" /><BR><BR>
<input name="opt_responseformat" type="hidden" value="html"/><hr>

</form>
<input form="FME" type="submit" value="Submit Request" /> <input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />



<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	<?php woocommerce_cart_totals(); ?>

	<!--<?php woocommerce_shipping_calculator(); ?> -->

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
