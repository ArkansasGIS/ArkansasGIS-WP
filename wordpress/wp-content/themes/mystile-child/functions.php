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

add_filter('gettext',  'translate_text');
	add_filter('ngettext',  'translate_text');
	  
	function translate_text($translated) {
		$translated = str_ireplace('Products',  'Data',  $translated);
	     $translated = str_ireplace('Product',  'Data',  $translated);
	$translated = str_ireplace('Free',  'Downloadable Data',  $translated);
	$translated = str_ireplace('Customer',  'User',  $translated);
	$translated = str_ireplace('Billing',  'Download',  $translated);
	$translated = str_ireplace('Checkout',  'Feeless Checkout',  $translated);
	$translated = str_ireplace('Orders',  'Downloads',  $translated);
	$translated = str_ireplace('Order',  'Download',  $translated);

	     
	     return $translated;
	     
	    	}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
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
    $fields['billing']['format_type']= array(
	    'type' => 'select',
	    'label'     => __('Format', 'woocommerce'),
    	'placeholder'   => _x('Format', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'format_type',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'SHAPE'        => __( 'Shapefile', 'woocommerce' ),
      		'GEODATABASE_FILE'       => __( 'File Geodatabase (Esri)', 'woocommerce' ),
      		'GEODATABASE_MDB'       => __( 'Personal Geodatabase (Esri)', 'woocommerce' ),
      		'DGNV8'       => __( 'Microstation Design V8', 'woocommerce' ),
      		'ACAD'       => __( 'AutoCAD DXF/DWG', 'woocommerce' ),
      		'DWF'       => __( 'AutoCAD DWF', 'woocommerce' ),
      		'MITAB'       => __( 'MapInfo TAB', 'woocommerce' ),
      		'GIF'       => __( 'GIF Image', 'woocommerce' ),
      		'PDF2D'       => __( 'GeoPDF', 'woocommerce' ),
      		'OGCKML'       => __( 'Keyhole Markup Language (KML)', 'woocommerce' )	
                        )                       
    );
    
   $fields['billing']['whereclip']= array(
	    'type' => 'select',
	    'label'     => __('Clip by: ', 'woocommerce'),
    	'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'CountyClip',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'where COUNTY_NAM =\'Faulkner\'&Clipper=ADMIN.DBO.COUNTIES_AHTD'        => __( 'Faulkner', 'woocommerce' ),
      		'where COUNTY_NAM =\'Pulaski\'&Clipper=ADMIN.DBO.COUNTIES_AHTD'       => __( 'Pulaski', 'woocommerce' ), 	
      		'where CITY_NAME =\'Conway\'&Clipper=ADMIN.DBO.CITY_LIMITS_AHTD'        => __( 'Cities: Conway', 'woocommerce' )
                        )                       
    );
     

     $fields['billing']['projection']= array(
	    'type' => 'select',
	    'label'     => __('Projection', 'woocommerce'),
    	'placeholder'   => _x('Projection', 'placeholder', 'woocommerce'),
    	'required'  => false,
     	'form' => 'FME',
    	'class'     => array('chosen-container'),
    	'clear'     => true,
	    'id' => 'Proj',
        'class'     => array('form-row-wide'),
 	    'options' => array(
     		'EPSG:26915'        => __( 'NAD83 UTM- Zone 15N', 'woocommerce' ),
      		'LL-WGS84'       => __( 'WGS84 Lat/Long', 'woocommerce' ), 	
      		'LL-83'       => __( 'NAD83 Lat/Long', 'woocommerce' ), 
      		'AR83-NF'       => __( 'Arkansas State Plane North Feet', 'woocommerce' ), 
      		'AR83-SF'       => __( 'Arkansas State Plane South Feet', 'woocommerce' )
                        )                       
    );
  
    $fields['billing']['email'] = array(
        'label'     => __('E-Mail:', 'woocommerce'),
        'placeholder'   => _x('E-Mail', 'placeholder', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );
     
     
     return $fields;
}

// TESTING ONLY!!!  Trying to establish a new hook that will send the data to FME after updating the database:  TD- 20140630
add_action( 'geostor_received', 'geostor_rec_func' );

	function wgeostor_rec_func () {

		echo '<form action="http://cm-sas-geo-fme1.sas.arkgov.net/fmedatadownload/geostor_dev/geostor_vector-dl_dev.fmw" method="get" id="FME">
		<input name="opt_responseformat" type="hidden" value="html"/>
		<input name="CoordinateSystem" type="hidden" value="EPSG:26915"/>
		<input name="opt_requesteremail" type="hidden" value="" />
		<input name="WhereClause" type="hidden" value="where CITY_NAME = &#039;North Little Rock&#039;" />
		<input name="Clipper" type="hidden" value="ADMIN.DBO.CITY_LIMITS_AHTD" />
		<input name="Format" type="hidden" value="SHAPE" />
		<input name="opt_servicemode" type="hidden" value="async">
		<input form="FME" type="submit" value="Submit Request" />' . "
";

	} 


/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>