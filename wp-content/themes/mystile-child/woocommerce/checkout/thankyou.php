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
		if($order->clip_type == 'State'){
			$statewide = true;
		}else{
			$statewide = false;
		}
		

		$downloaderror = false;
		$productlinks = "";
		if($statewide){
			if (isset($order->vector_format_type)){
				if($order->vector_format_type == 'SHAPE'){
					$vector_type = 'SHP';
				}else{
					$vector_type = 'FGDB';
				}
				
			}else{
				$vector_type = 'SHP';
			}
			foreach($order->get_items() as $item) { 
				$product = get_product( $item['product_id'] ); 
				$terms = get_the_terms( $item['product_id'], 'product_cat' );
		        if($terms && ! is_wp_error($terms)){
			        foreach ($terms as $term) {
			            $product_cat = $term->name;
			            break;
			        }
				}
				$productlinks .= '<a href="http://geostor-vectors.geostor.org/'.$product_cat.'/'.$vector_type.'/'.$product->get_sku().'.gdb.zip" class="button product_type_simple"><span>'.$product->get_sku().'</span></a><br><br>';
			}
		}else{
			// This creates a "for loop" to find each sku (Feature)
			$itemcount = 0;
			foreach($order->get_items() as $item) { 
				$product = get_product( $item['product_id'] ); 
				$terms = get_the_terms( $item['product_id'], 'product_cat' );
		        if($terms && ! is_wp_error($terms)){
			        foreach ($terms as $term) {
			            $product_cat = $term->name;
			            break;
			        }
				}	
				$vectorsku .= $product_cat.'.'.$product->get_sku().'%20';
				$itemcount++;
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
					$statewide = false;
					break;
				case 'City':
					$whereclause = "city_name%20LIKE%20'".$order->city_clipper."'";
					$whereclause = str_replace(" ", "%20", $whereclause);
					$clipper .= "Boundaries.CITY_LIMITS_AHTD";
					$statewide = false;
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
	        $fmeurl .= "&TM_priority=".$itemcount;
	   		$fmeurl .= "&opt_showresult=false";
	   		$fmeurl .= "&opt_servicemode=async";
	   		$fmeurl .= "&opt_requesteremail=".$order->email;
	   		$fmeurl .= "&opt_responseformat=xml";
			
			
			
			try{
				$result = @file_get_contents($fmeurl);
				
				$xmlresponse = new SimpleXMLElement($result);
				if($xmlresponse->statusInfo->status == 'success'){
					$downloaderror = false;
				}else{
					$downloaderror = true;
				}
			}catch (Exception $e){
				$downloaderror = true;
				print_r($e->getMessage());
			} 
		}
   		
	?>
	<!-- End FME Request GEOSTOREDITS -->
	
	<?php if ( in_array( $order->status, array( 'failed' ) ) || $downloaderror == true ) : ?>

		<p><?php _e( 'Unfortunately there was a problem with your Download request.<br>', 'woocommerce' ); ?></p>
		<p><?php _e( var_dump($result), 'woocommerce' ); ?></p>
		<p><?php _e( $fmeurl, 'woocommerce' ); ?></p>
		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please contact GeoStor for assistance.', 'woocommerce' );
			else
				_e( 'Please contact GeoStor for assistance.', 'woocommerce' );
		?></p>

	<?php else : ?>
		<?php if($statewide) : ?>
			<p><?php _e( 'Thank you. Due to space constraints, statewide processing is not available<br>Below you will find links to the Statewide zip file in the format and projection you requested.', 'woocommerce' ); ?></p>
		 
			<ul class="order_details">
				<br>
				<?php echo $productlinks; ?>	
			</ul>
			<div class="clear"></div>
		
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
					<strong><?php echo '';$xmlresponse->jobID[0] ; ?></strong>
				</li>
			</ul>
			<div class="clear"></div>
		<?php endif; ?>
	<?php endif; ?>
<?php else : ?>

	<p><?php _e( 'Thank you. Your Download request has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>