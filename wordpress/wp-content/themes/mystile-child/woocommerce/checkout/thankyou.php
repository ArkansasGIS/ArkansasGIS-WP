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
			$sku .= $product->get_sku().' '; 
		}
		
		// Build the FME url
		$fmeurl = 'http://cm-sas-geo-fme1.sas.arkgov.net/fmedatadownload/geostor_dev/geostor_vector-dl_dev.fmw?';
		$fmeparams = array();
		$fmeparams['opt_servicemode'] = $order->dl_type;
		$fmeparams['Format'] = $order->format_type;
		$fmeparams['CoordinateSystem'] = $order->projection;
		$fmeparams['opt_requesteremail'] = $order->email;
		$fmeparams['SmallClippee'] = $sku;
		$fmeparams['LargeClippee'] = 'DEFAULT';
		
		//// Check what clipper we are using and set the Clipper and WhereClause ->  RDP GEOSTOREDITS
		switch($order->clip_type){
			case 'County':
				$fmeparams['WhereClause'] = 'where COUNTY_NAME = "'.$order->county_clipper.'"';
				$fmeparams['Clipper'] = 'ADMIN.DBO.AHTD_COUNTIES';
				//$fmeurl .= '&WhereClause='.urlencode('COUNTY_NAME = "'.$order->county_clipper.'"').'&Clipper=ADMIN.DBO.AHTD_COUNTIES';
				break;
			case 'City':
				$fmeparams['WhereClause'] = 'where CITY_NAME = "'.$order->city_clipper.'"';
				$fmeparams['Clipper'] = 'ADMIN.DBO.CITY_LIMITS_AHTD';
				//$fmeurl .= '&WhereClause='.urlencode('CITY_NAME = "'.$order->city_clipper.'"').'&Clipper=ADMIN.DBO.AHTD_CITIES';
				break;
			case 'Extent':
				$fmeparams['WhereClause'] = 'where CITY_NAME = "'.$order->city_clipper.'"';
				$fmeparams['Clipper'] = 'ADMIN.DBO.CITY_LIMITS_AHTD';
				//$fmeurl .= '&WhereClause='.urlencode($order->extent_clipper).'&Clipper=DEFAULT';
				break;
			case 'State':
				$fmeparams['WhereClause'] = 'where CITY_NAME = "'.$order->city_clipper.'"';
				$fmeparams['Clipper'] = 'ADMIN.DBO.CITY_LIMITS_AHTD';
				//$fmeurl .= '&WhereClause=&LargeClippee=DEFAULT';
				break;
		}
		$fmeparams['opt_responseformat'] = 'xml';
		$fmeurl .= http_build_query($fmeparams);
		$fmeerror = false;
		$xmlresponse = '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fmeurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if( ! $result = curl_exec($ch)){
			trigger_error(curl_error($ch));
			$fmeerror = true;
		}else{
			$xmlresponse = new SimpleXMLElement($result);
			if($xmlresponse->statusInfo->status == 'failure'){
				$fmeerror = true;
			}
		}
		curl_close($ch);
		
	?>
	<!-- End FME Request GEOSTOREDITS -->
	
	<?php if ( in_array( $order->status, array( 'failed' ) ) || $fmeerror == true ) : ?>

		<p><?php _e( 'Unfortunately here was a problem with your Download request.<br>'.curl_error($ch), 'woocommerce' ); ?></p>

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
				<strong><?php echo $xmlresponse->jobID[0].'    <a href="'.$fmeurl.'" >Test URL</a>';; ?></strong>
			</li>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

<?php else : ?>

	<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>