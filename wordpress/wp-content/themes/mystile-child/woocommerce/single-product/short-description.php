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
 
 $product_cats = wp_get_post_terms($product->post->ID);
 if($product_cats && ! is_wp_error($product_cats)){
 	$single_cat = array_shift($product_cats);
 }
//if ( ! $post->post_excerpt ) return;
?>

<div itemprop="description">
	<?php 
	 echo $product->post->post_excerpt.'<br><br>'; 
	 echo '<strong>Updated: </strong> '.$product->post->post_date.'<br><br>'; 
	 echo '<strong>Custodian: </strong> We need to add a custodian variable to the products<br><br>'; 
	 echo '<a href="https://s3.amazonaws.com/geostor-vectors/'.$single_cat->name.'/'.$product->get_sku().'.zip"><img src="'.wp_get_attachment_url(210).'" height="42" width="42">&nbsp;&nbsp;&nbsp;&nbsp;Download the Statewide ZIP file</a><br><br>'; 
	 echo '<a href="http://www.geostor.arkansas.gov/ArcGIS/rest/services/FEATURE_SERVICES/'.$product->get_sku().'/MapServer/0"><img src="'.wp_get_attachment_url(211).'" height="42" width="60">&nbsp;&nbsp;&nbsp;&nbsp;Connect To Web Feature Services</a><br><br>'; /* apply_filters( 'woocommerce_short_description', $post->post_excerpt ) */ ?>
</div>