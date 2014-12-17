<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'zdmv5lp26tfbp7jcwiw51ix9sj389e712' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme installation
				'includes/theme-woocommerce.php',		// WooCommerce options
				'includes/theme-plugin-integrations.php'	// Plugin integrations
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/
// Added to get access to MySQL database tables
global $wpdb;

//  Action to add 'Add To Cart' button with the product thumbnails
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );

add_filter('gettext',  'translate_text');
add_filter('ngettext',  'translate_text');
	  
	function translate_text($translated) {
	$translated = str_ireplace('Products',  'Data',  $translated);
	$translated = str_ireplace('Product',  'Data',  $translated);
	$translated = str_ireplace('Free',  'Downloadable Data',  $translated);
	$translated = str_ireplace('Customer',  'User',  $translated);
	$translated = str_ireplace('Billing',  'Download',  $translated);
	$translated = str_ireplace('Orders',  'Downloads',  $translated);
	$translated = str_ireplace('Order',  'Download',  $translated);
	$translated = str_ireplace('Checkout',  'Download',  $translated);
	$translated = str_ireplace('Cart',  'Bin',  $translated);

	     
	     return $translated;
	     
	 }


// RDP added Hook for FME details on My Account Page GEOSTOREDITS
add_filter('woocommerce_order_details_after_order_table','custom_order_details_after_order_table');

function custom_order_details_after_order_table($order){
	$post_meta = get_post_meta($order->id);
	echo '<table class="shop_table order_details">';
	echo '<thead><tr><th class="product-name">FME</th><th class="product-name"></th></tr></thead>';
	echo '<tbody>';
	echo '<tr class="order_item">';
	echo '<td class="product-name">Format</td>';
	echo '<td class="product-name">'.get_post_meta($order->id,'_format_type',true).'</td>';
	echo '</tr>';
	echo '<tr class="order_item">';
	echo '<td class="product-name">Coordinate System</td>';
	echo '<td class="product-name">'.get_post_meta($order->id,'_projection',true).'</td>';
	echo '</tr>';
	echo '<tr class="order_item">';
	echo '<td class="product-name">Clipper</td>';
	echo '<td class="product-name">'.get_post_meta($order->id,'_clip_type',true).'</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
}

// RDP added for automatic order status to complete
add_filter( 'woocommerce_payment_complete_order_status', 'custom_update_order_status', 10, 2 );

function custom_update_order_status( $order_status, $order_id ) {
 
 $order = new WC_Order( $order_id );
 
 if ( 'processing' == $order_status && ( 'on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status ) ) {
 
 return 'completed';
 
 }
 
 return $order_status;
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	global $wpdb;
     unset($fields['billing']['billing_address_1']);
     unset($fields['billing']['billing_country']);
      unset($fields['billing']['billing_first_name']);
     unset($fields['billing']['billing_last_name']);
     unset($fields['billing']['billing_company']);
     unset($fields['billing']['billing_address_2']);
      unset($fields['billing']['billing_city']);
     unset($fields['billing']['billing_state']);
     unset($fields['billing']['billing_postcode']);
     unset($fields['billing']['billing_email']);
     unset($fields['billing']['billing_phone']);
     $fields['billing']['dl_type']= array(
	    'type' => 'select',
	    'label'     => __('Download Type', 'woocommerce'),
    	'placeholder'   => _x('Download Type', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'DT',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'async'        => __( 'E-Mail', 'woocommerce' ),
      		'sync'       => __( 'Desktop', 'woocommerce' ) 	
                        )                       
    );
	
    $fields['billing']['vector_format_type']= array(
	    'type' => 'select',
	    'label'     => __('Vector Format', 'woocommerce'),
    	'placeholder'   => _x('Vector Format', 'placeholder', 'woocommerce'),
    	'required'  => true,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'vector_format_type',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'SHAPE'        => __( 'Shapefile', 'woocommerce' ),
      		'FILEGDB'       => __( 'File Geodatabase (Esri)', 'woocommerce' ),
      		'ACAD'       => __( 'AutoCAD DXF/DWG', 'woocommerce' ),
      		'DWF'       => __( 'AutoCAD DWF', 'woocommerce' ),
      		'MAPINFO'       => __( 'MapInfo TAB', 'woocommerce' ),
      		'GEOJSON'       => __( 'Geo JSON', 'woocommerce' ),
      		'PDF2D'       => __( 'GeoPDF', 'woocommerce' ),
      		'OGCKML'       => __( 'Keyhole Markup Language (KML)', 'woocommerce' )
                        )                       
    );
	
	$fields['billing']['raster_format_type']= array(
	    'type' => 'select',
	    'label'     => __('Raster Format', 'woocommerce'),
    	'placeholder'   => _x('Raster Format', 'placeholder', 'woocommerce'),
    	'required'  => true,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'raster_format_type',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'JPEG2000'        => __( 'Jpeg 2000', 'woocommerce' ),
      		'TIFF'       => __( 'Geo Tif', 'woocommerce' )
                        )                       
    );
    
   $fields['billing']['clip_type']= array(
	    'type' => 'select',
	    'label'     => __('Clip by: ', 'woocommerce'),
    	'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
    	'required'  => true,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'clip_type',
        'class'     => array('form-row-wide'),
        // THIS calls the toggleClipper function from the geostor_custom.js
        'custom_attributes' => array('onchange' => 'toggleClipper();'),
 	    'options' => array(
 	    	''        => __( 'Select a Clipper', 'woocommerce' ),
     		'County'        => __( 'County', 'woocommerce' ),
      		'City'        => __( 'City', 'woocommerce' ),
      		'State'        => __( 'Statewide', 'woocommerce' )
                        )                       
    );
	
	// Query the database County table to get select values GEOSTOREDITS
	$counties = $wpdb->get_results("SELECT COUNTY_NAME from county");
	// Create the County clipper pulldown on the cart page GEOSTOREDITS
	$fields['billing']['county_clipper']= array(
	    'type' => 'select',
	    'label'     => __('County', 'woocommerce'),
    	'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'county_clipper',
 	    'options' => array(
 	    	''        => __( 'Select a County', 'woocommerce' )
                        )                       
    );
	// Add the countyies to the county clipper puldown
	foreach($counties as $county){
		$fields['billing']['county_clipper']['options'][$county->COUNTY_NAME] =  __($county->COUNTY_NAME,'woocommerce');
	}

	// Query the database City table to get select values GEOSTOREDITS
	$cities = $wpdb->get_results("SELECT CITY_NAME from city");
	$fields['billing']['city_clipper']= array(
	    'type' => 'select',
	    'label'     => __('City', 'woocommerce'),
    	'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'city_clipper',
 	    'options' => array(
 	    	''        => __( 'Select a City', 'woocommerce' )
                        )                       
    );

	// Add the cities to the city clipper puldown
	foreach($cities as $city){
		$fields['billing']['city_clipper']['options'][$city->CITY_NAME] =  __($city->CITY_NAME,'woocommerce');
	}

	$fields['billing']['extent_clipper'] = array(
        'label'     => __('Extent:', 'woocommerce'),
        'placeholder'   => _x('Extent:', 'placeholder', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-wide'),
        'id' => 'extent_clipper',
        'clear'     => true
     );
     
	
     $fields['billing']['projection']= array(
	    'type' => 'select',
	    'label'     => __('Projection', 'woocommerce'),
    	'placeholder'   => _x('Projection', 'placeholder', 'woocommerce'),
    	'required'  => true,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'Proj',
        'class'     => array('form-row-wide'),
 	    'options' => array(
 	    	''        => __( 'Select A Projection', 'woocommerce' ),
     		'26915'        => __( 'NAD83 UTM- Zone 15N', 'woocommerce' ),
      		'4324'       => __( 'WGS84 Lat/Long', 'woocommerce' ), 	
      		'4269'       => __( 'NAD83 Lat/Long', 'woocommerce' ), 
      		'3433'       => __( 'Arkansas State Plane North Feet', 'woocommerce' ), 
      		'3434'       => __( 'Arkansas State Plane South Feet', 'woocommerce' )
                        )                       
    );
  
    $fields['billing']['email'] = array(
        'label'     => __('E-Mail:', 'woocommerce'),
        'placeholder'   => _x('E-Mail', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );
     
     
     return $fields;
}



/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>