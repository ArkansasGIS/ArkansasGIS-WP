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
 
//if ( ! $post->post_excerpt ) return;
?>
<div itemprop="description">
	<?php echo $product->post->post_excerpt.'<br><br>'; ?>
	<?php echo '<strong>Updated: </strong> '.$product->post->post_date.'<br><br>'; ?>
	<?php echo '<strong>Custodian: </strong> We need to add a custodian variable to the products<br><br>'; ?>
	<?php echo '<a href="ftp://ftp.geostor.arkansas.gov/Public_Statewide/'.$product->get_sku().'.zip"><img src="'.wp_get_attachment_url(210).'" height="42" width="42">&nbsp;&nbsp;&nbsp;&nbsp;Download the Statewide ZIP file</a><br><br>'; ?>
	<?php echo '<a href="http://www.geostor.arkansas.gov/ArcGIS/rest/services/FEATURE_SERVICES/'.$product->get_sku().'/MapServer/0"><img src="'.wp_get_attachment_url(211).'" height="42" width="60">&nbsp;&nbsp;&nbsp;&nbsp;Connect To Web Feature Services</a><br><br>'; /* apply_filters( 'woocommerce_short_description', $post->post_excerpt ) */ ?>
</div>