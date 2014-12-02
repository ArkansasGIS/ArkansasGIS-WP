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
 
  		$terms = get_the_terms( $product->ID, 'product_cat' );
        if($terms && ! is_wp_error($terms)){
	        foreach ($terms as $term) {
	            $product_cat = $term->name;
	            break;
	        }
		}
		$publisher = $product->get_attribute('pa_publisher');
		if($publisher == ''){
			$publisher = 'Not Assigned';
		}
		$pubdate = $product->get_attribute('pa_pubdate');
		if($pubdate == ''){
			$pubdate = 'Not Assigned';
		}
		$imagery = $product->get_attribute('imagery');
//if ( ! $post->post_excerpt ) return;
?>

<div itemprop="description">
	<?php 
	 echo $product->post->post_excerpt.'<br><br>'; 
	 echo '<strong>Updated: </strong> '.$product->post->post_date.'<br><br>'; 
	 echo '<strong>Publisher: </strong>'.$publisher.'<br><br>'; 
	 echo '<strong>Publication Date: </strong>'.$pubdate.'<br><br>'; 
	 if($imagery){
	 	
	 }else{
	 	echo '<a href="http://geostor-vectors.geostor.org/'.$product_cat.'/'.$product->get_sku().'.zip"><img src="'.wp_get_attachment_url(210).'" height="42" width="42">&nbsp;&nbsp;&nbsp;&nbsp;Download the Statewide ZIP file</a><br><br>'; 
	 	echo '<a href="http://www.geostor.arkansas.gov/ArcGIS/rest/services/FEATURE_SERVICES/'.$product->get_sku().'/MapServer/0"><img src="'.wp_get_attachment_url(211).'" height="42" width="60">&nbsp;&nbsp;&nbsp;&nbsp;Connect To Web Feature Services</a><br><br>'; /* apply_filters( 'woocommerce_short_description', $post->post_excerpt ) */ 
	 }
	 ?>
</div>