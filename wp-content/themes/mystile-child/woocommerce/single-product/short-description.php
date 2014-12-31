<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * @author Richie Pierce
 * @discription We are using this to define the global product short description
 * @searchtext  GEOSTOREDITS
 */
 global $product;
 $pa_attributes = $product->get_attributes();
  		$terms = get_the_terms( $product->ID, 'product_cat' );
        if($terms && ! is_wp_error($terms)){
	        foreach ($terms as $term) {
	            $product_cat = $term->name;
	            break;
	        }
		}
		if(!$pa_attributes['pa_publisher']){
			if($pa_attributes['publisher']){
				$publisher = $product->get_attribute('publisher');
			}else{
				$publisher = 'Not Available';
			}
		}else{
			$publisher = $product->get_attribute('pa_publisher');
		}
		
		if(!$pa_attributes['pa_pubdate']){
			if($pa_attributes['pubdate']){
				$pubdate = $product->get_attribute('pubdate');
			}else{
				$pubdate = 'Not Available';
			}
		}else{
			$pubdate = $product->get_attribute('pa_pubdate');
		}
		
		
//if ( ! $post->post_excerpt ) return;
?>

<div itemprop="description">
	<?php 
	 echo $product->post->post_excerpt.'<br><br>'; 
	 echo '<strong>Updated: </strong> '.$product->post->post_date.'<br><br>'; 
	 echo '<strong>Publisher: </strong>'.$publisher.'<br><br>'; 
	 echo '<strong>Publication Date: </strong>'.$pubdate.'<br><br>'; 
	 if($pa_attributes['imagery']){
	 	if($product_cat == 'Elevation'){
	 		
	 		echo '<a href="http://www.geostor.org/S3Browser/Default.aspx?bucketname=geostor-elevation.geostor.org&path='.$product->get_attribute('imagery').'" class="button product_type_simple">&nbsp;&nbsp;&nbsp;&nbsp;Click Here Browse The LIDAR Repository for download</a><br><br>'; 
	 		//echo '<a href="http://geostor-vectors.geostor.org/'.$product_cat.'/'.$product->get_sku().'.zip"><img src="'.wp_get_attachment_url(210).'" height="42" width="42">&nbsp;&nbsp;&nbsp;&nbsp;Download the Statewide ZIP file</a><br><br>'; 
	 	}else{
	 		echo '<a href="http://www.geostor.org/S3Browser/Default.aspx?bucketname=geostor-imagery.geostor.org&path='.$product->get_attribute('imagery').'" class="button product_type_simple">&nbsp;&nbsp;&nbsp;&nbsp;Click Here Browse the Imagery Repository for download</a><br><br>'; 
	 	
	 	}
	 }else{
	 	
	 	echo '<a href="http://geostor-vectors.geostor.org/'.$product_cat.'/SHP/'.$product->get_sku().'.zip" class="button product_type_simple"><span>&nbsp;&nbsp;&nbsp;&nbsp;Statewide ZIP file (Shapefile - UTM Zone 15N)</span></a><br><br>'; 
	 	echo '<a href="http://geostor-vectors.geostor.org/'.$product_cat.'/FGDB/'.$product->get_sku().'.zip" class="button product_type_simple"><span>&nbsp;&nbsp;&nbsp;&nbsp;Statewide ZIP file (FGDB - UTM Zone 15N)</span></a><br><br>';
	 	echo '<strong>Or to clip by County or City</strong><br><br>';
	 	// GEOSTOR uncomment for WFS url
	 	//echo '<a href="http://www.geostor.arkansas.gov/ArcGIS/rest/services/FEATURE_SERVICES/'.$product->get_sku().'/MapServer/0"><img src="'.wp_get_attachment_url(211).'" height="42" width="60">&nbsp;&nbsp;&nbsp;&nbsp;Connect To Web Feature Services</a><br><br>'; /* apply_filters( 'woocommerce_short_description', $post->post_excerpt ) */ 
	 }
	 ?>
</div>