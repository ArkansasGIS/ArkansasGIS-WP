<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
if ( $order ) : ?>
	<!--RDP Added for FME Request GEOSTOREDITS   -->
	<?php
		// This creates a "for loop" to find each sku (Feature)
		foreach($order->get_items() as $item) { 
			$product = get_product( $item['product_id'] ); 
			$terms = get_the_terms( $item['product_id'], 'product_cat' );
	        if($terms && ! is_wp_error($terms)){
		        foreach ($terms as $term) {
		            $product_cat = $term->name;
		            break;
		        }
			}	
			$sku .= $product_cat.'.'.$product->get_sku().' '; 
		}
		
		// Build the FME url
		$fmeurl = "https://guest:agioguest@geostor-dev-agio-test.fmecloud.com/fmedatadownload/GeoStor-Vectors/GeoStor_Vectors.fmw?";
		$fmeurl .= "ClippeeSource=gisdb&Clippee=".rtrim($sku," ")."&OUTPUT=%24(FME_SHAREDRESOURCE_TEMP)&Format=".$order->format_type;
		$fmeurl .= "&ClipperSource=gisdb&CoordinateSystem=".$order->projection;
		$fmeurl .= "&opt_showresult=false&opt_servicemode=async&opt_requesteremail=".$order->email;
		
		//// Check what clipper we are using and set the Clipper and WhereClause ->  RDP GEOSTOREDITS
		switch($order->clip_type){
			case 'County':
				$fmeurl .= "&WHERE=county_nam%20LIKE%20'".$order->county_clipper."'";
				$fmeurl .= "&Clipper=Boundaries.COUNTIES_AHTD";
				//$fmeurl .= '&WhereClause='.urlencode('COUNTY_NAME = "'.$order->county_clipper.'"').'&Clipper=ADMIN.DBO.AHTD_COUNTIES';
				break;
			case 'City':
				$fmeurl .= "&WhereClause=city_nam LIKE '".$order->city_clipper."'";
				$fmeurl .= "&Clipper=Boundaries.CITY_LIMITS_AHTD";
				//$fmeurl .= '&WhereClause='.urlencode('CITY_NAME = "'.$order->city_clipper.'"').'&Clipper=ADMIN.DBO.AHTD_CITIES';
				break;
			case 'Extent':
				
				//$fmeurl .= '&WhereClause='.urlencode($order->extent_clipper).'&Clipper=DEFAULT';
				break;
			case 'State':
				
				//$fmeurl .= '&WhereClause=&LargeClippee=DEFAULT';
				break;
		}
		$fmeurl .= "&opt_responseformat=xml";
		$fmeerror = false;
		try{
			$result = file_get_contents($fmeurl);
			$xmlresponse = new SimpleXMLElement($result);
			if($xmlresponse->statusInfo->status == 'failure'){
				$fmeerror = true;
			}
		}catch (Exception $e){
			$fmeerror = true;
			print_r($e->getMessage());
		}
		

	?>
	<!-- End FME Request GEOSTOREDITS -->
	
	<?php if ( in_array( $order->status, array( 'failed' ) ) || $fmeerror == true ) : ?>

		<p><?php _e( 'Unfortunately there was a problem with your Download request.<br>'.curl_error($ch), 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please contact GeoStor for assistance.', 'woocommerce' );
			else
				_e( 'Please contact GeoStor for assistance.', 'woocommerce' );
		?></p>

	<?php else : ?>
		
		<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>
		 
		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="order">
				<?php _e( 'Process ID:', 'woocommerce' ); ?>
				<strong><?php echo $xmlresponse->jobID[0].'    <a href="'.$fmeurl.'" > Direct URL</a>';; ?></strong>
			</li>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

<?php else : ?>

	<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>