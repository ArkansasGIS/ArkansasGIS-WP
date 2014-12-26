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
				$rastersku .= $product_cat.'.'.$product->get_sku().'%20';
				$rastersource = $product->get_attribute('imagery');
			}else{
				$vectorsku .= $product_cat.'.'.$product->get_sku().'%20';
				$rastersource = "MRSID";
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
		//// Check what clipper we are using and set the Clipper and WhereClause ->  RDP GEOSTOREDITS
		switch($order->clip_type){
			case 'County':
				$whereclause = "county_nam%20LIKE%20'".$order->county_clipper."'";
				$whereclause = str_replace(" ", "%20", $whereclause);
				$clipper = "Boundaries.COUNTIES_AHTD";
				break;
			case 'City':
				$whereclause = "city_name%20LIKE%20'".$order->city_clipper."'";
				$whereclause = str_replace(" ", "%20", $whereclause);
				$clipper .= "Boundaries.CITY_LIMITS_AHTD";
				break;
			case 'Extent':
				
				//$fmeurl .= '&WhereClause='.urlencode($order->extent_clipper).'&Clipper=DEFAULT';
				break;
			case 'State':
				
				//$fmeurl .= '&WhereClause=&LargeClippee=DEFAULT';
				break;
		}
		
		
		
		
		
   		$fmeurl = "https://guest:agioguest@geostor-agio.fmecloud.com/fmedatadownload/GeoStor/GeoStor_Downloads_2015.fmw?";
   		$fmeurl .= "DestDataset_GENERIC=%22%24(FME_SHAREDRESOURCE_TEMP)%22";
   		$fmeurl .= "&RASTER_FORMAT=".$raster_type;
   		$fmeurl .= "&OUTPUT=%22%24(FME_SHAREDRESOURCE_TEMP)%22";
   		$fmeurl .= "&VECTOR_FORMAT=".$vector_type;
   		$fmeurl .= "&CoordinateSystem=EPSG%3A".$projection;
   		$fmeurl .= "&SourceDataset_POSTGIS=gisdb";
   		$fmeurl .= "&CLIPPEE=".rtrim($vectorsku);
		$fmeurl .= "&RASTER_FEATURE_TYPES=".rtrim($rastersku);
   		$fmeurl .= "&SourceDataset_SCHEMA=gisdb";
   		$fmeurl .= "&SCHEMA_IN_REAL_FORMAT_SCHEMA=POSTGIS";
   		$fmeurl .= "&WHERE=".$whereclause;
   		$fmeurl .= "&CLIPPER=".$clipper;
		$fmeurl .= "&RASTER_SOURCE_FORMAT=MRSID"; //.$rastersource;
   		$fmeurl .= "&opt_showresult=false";
   		$fmeurl .= "&opt_servicemode=async";
   		$fmeurl .= "&opt_requesteremail=".$order->email;
   		$fmeurl .= "&opt_responseformat=xml";
		
		
		
		$fmeerror = false;
		try{
			$result = @file_get_contents($fmeurl);
			
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

		<p><?php _e( 'Unfortunately there was a problem with your Download request.<br>'.var_dump($result), 'woocommerce' ); ?></p>

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
				<strong><?php echo $xmlresponse->jobID[0] ; ?></strong>
			</li>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

<?php else : ?>

	<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>