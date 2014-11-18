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
			if($product->get_attribute('imagery')){
				$rastersku .= $product_cat.'.'.$product->get_sku().' ';
			}else{
				$vectorsku .= $product_cat.'.'.$product->get_sku().' ';
			}
		}

		if (isset($order->raster_format_type)){
			$raster_type = $order->raster_format_type;
		}else{
			$raster_type = '';
		}
		if (isset($order->vector_format_type)){
			$vector_type = $order->vector_format_type;
		}else{
			$vector_type = '';
		}
		if(isset($order->projection)){
			$projection = $order->projection;
		}else{
			$projection = '';
		}
		// Build the FME url
		$fmeurl = "https://guest:agioguest@geostor-dev-agio-test.fmecloud.com/fmedatadownload/GeoStor/GeoStor_Downloads.fmw?";
		$fmeurl .= "DestDataset_GENERIC=%24(FME_SHAREDRESOURCE_TEMP)";
		$fmeurl .= "&RASTER_FORMAT=".$raster_type;
		$fmeurl .= "&VECTOR_FORMAT=".$vector_type;
		$fmeurl .= "&CoordinateSystem=".$projection;
		
		
		//// Check what clipper we are using and set the Clipper and WhereClause ->  RDP GEOSTOREDITS
		switch($order->clip_type){
			case 'County':
				$fmeurl .= "&WHERE=county_nam%20LIKE%20'".$order->county_clipper."'";
				$fmeurl .= "&Clipper=Boundaries.COUNTIES_AHTD";
				break;
			case 'City':
				$fmeurl .= "&WhereClause=city_nam LIKE '".$order->city_clipper."'";
				$fmeurl .= "&Clipper=Boundaries.CITY_LIMITS_AHTD";
				break;
			case 'Extent':
				
				//$fmeurl .= '&WhereClause='.urlencode($order->extent_clipper).'&Clipper=DEFAULT';
				break;
			case 'State':
				
				//$fmeurl .= '&WhereClause=&LargeClippee=DEFAULT';
				break;
		}
		
		$fmeurl .= "&OUTPUT=%24(FME_SHAREDRESOURCE_TEMP)";
		$fmeurl .= "&SourceDataset_RASTER=gisdb";
		$fmeurl .= "&SourceDataset_POSTGIS=gisdb";
		$fmeurl .= "&RASTER_FEATURE_TYPES=".$rastersku;
		$fmeurl .= "&CLIPPEE=".$vectorsku;
		$fmeurl .= "&opt_showresult=false&opt_servicemode=async&opt_requesteremail=".$order->email;
		$fmeurl .= "&opt_responseformat=xml"; 
		//$fmeurl = "https://guest:agioguest@geostor-dev-agio-test.fmecloud.com/fmedatadownload/GeoStor/GeoStor_Downloads.fmw?DestDataset_GENERIC=%24(FME_SHAREDRESOURCE_TEMP)&RASTER_FORMAT=JPEG2000&RASTER_FEATURE_TYPES=Imagery.ADOP2_COUNTY_MOSAICS_RGB_EXTENT&OUTPUT=%24(FME_SHAREDRESOURCE_TEMP)&VECTOR_FORMAT=SHAPE&CoordinateSystem=EPSG%3A26915&SourceDataset_RASTER=gisdb&CLIPPER=Boundaries.COUNTIES_AHTD&WHERE=county_nam%20LIKE%20%27Chicot%27&SourceDataset_POSTGIS=gisdb&CLIPPEE=Boundaries.CITY_LIMITS_AHTD&opt_showresult=false&opt_servicemode=async&opt_requesteremail=richie.pierce%40arkansas.gov";
		echo "<a href=".$fmeurl.">URL</a>";
		$fmeerror = false;
		try{
			$result = @file_get_contents($fmeurl);
			//var_dump($result);
			$xmlresponse = new SimpleXMLElement($result);
			if($xmlresponse->statusInfo->status == 'success'){
				$fmeerror = false;
			}else{
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
				<strong><?php echo $xmlresponse->jobID[0].'    <a href='.$fmeurl.' > Direct URL</a>';; ?></strong>
			</li>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

<?php else : ?>

	<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>