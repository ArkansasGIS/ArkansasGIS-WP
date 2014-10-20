<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page' );
}
?>
<?php
global $woo_options;

/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/*-----------------------------------------------------------------------------------*/
/* WooCommerce Overrides */
/*-----------------------------------------------------------------------------------*/
if (class_exists('woocommerce')) {

// Disable WooCommerce styles
if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
	// WooCommerce 2.1 or above is active
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	// WooCommerce is less than 2.1
	define( 'WOOCOMMERCE_USE_CSS', false );
}

// If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'prettyPhoto[product-gallery]');
				});
			</script>
		<?php
	}
}

// Adjust markup on all woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'woocommerce_mystile_before_content', 10);
add_action('woocommerce_after_main_content', 'woocommerce_mystile_after_content', 20);

if (!function_exists('woocommerce_mystile_before_content')) {
	function woocommerce_mystile_before_content() {
		?>
		<!-- #content Starts -->
		<?php woo_content_before(); ?>
	    <div id="content" class="col-full">

	        <!-- #main Starts -->
	        <?php woo_main_before(); ?>
	        <div id="main" class="col-left">

	    <?php
	}
}


if (!function_exists('woocommerce_mystile_after_content')) {
	function woocommerce_mystile_after_content() {
		?>

			</div><!-- /#main -->
	        <?php woo_main_after(); ?>

	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}

// Remove pagination (we're using the WooFramework default pagination)
//  < 2.0
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_pagination', 'woocommerceframework_pagination', 10 );
// 2.0 +
if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '>=' ) ) {
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerceframework_pagination', 10 );
}

function woocommerceframework_pagination() {
	if ( is_search() && is_post_type_archive() ) {
		add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		add_filter( 'woo_pagination_args_defaults', 'woocommerceframework_woo_pagination_defaults', 10 );
	}
	woo_pagination();
}

function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '&post_type=product';

	return $settings;
} // End woocommerceframework_add_search_fragment()

function woocommerceframework_woo_pagination_defaults ( $settings ) {
	$settings['use_search_permastruct'] = false;

	return $settings;
} // End woocommerceframework_woo_pagination_defaults()

// Add wrapping div around pagination
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_open', 5 );
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_close', 25 );

if (!function_exists('woocommerce_pagination_wrap_open')) {
	function woocommerce_pagination_wrap_open() {
		echo '<section class="pagination-wrap">';
	}
}

if (!function_exists('woocommerce_pagination_wrap_close')) {
	function woocommerce_pagination_wrap_close() {
		echo '</section>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* HEADER */
/*-----------------------------------------------------------------------------------*/

function woo_wc_search_form( $form ) {

    $form = '<li class="search"><form role="search" method="get" id="searchform" action="' . esc_url(home_url( '/' )) . '" >
    <label class="screen-reader-text" for="s">' . __( 'Search Products:' , 'woothemes' ) . '</label>
    <input type="search" results=5 autosave="'. esc_url(home_url( '/' )) .'" class="input-text" placeholder="'. esc_attr__( 'Search Products', 'woothemes' ) .'" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" class="button" id="searchsubmit" value="'. esc_attr__( 'Search', 'woothemes' ) .'" />
    <input type="hidden" name="post_type" value="product" />
    </form></li>';

    return $form;
}

add_filter( 'get_search_form', 'woo_wc_search_form' );

/*-----------------------------------------------------------------------------------*/
/* PRODUCTS */
/*-----------------------------------------------------------------------------------*/

// Number of products per page
add_filter('loop_shop_per_page', 'wooframework_products_per_page');
if (!function_exists('wooframework_products_per_page')) {
	function wooframework_products_per_page() {
		global $woo_options;
		if ( isset( $woo_options['woocommerce_products_per_page'] ) ) {
			return $woo_options['woocommerce_products_per_page'];
		}
	}
}

// Display product tabs?
add_action('wp_head','wooframework_tab_check');
if ( ! function_exists( 'wooframework_tab_check' ) ) {
	function wooframework_tab_check() {
		global $woo_options;
		if ( isset( $woo_options[ 'woocommerce_product_tabs' ] ) && $woo_options[ 'woocommerce_product_tabs' ] == "false" ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		}
	}
}

// Display related products?
add_action('wp_head','wooframework_related_products');
if ( ! function_exists( 'wooframework_related_products' ) ) {
	function wooframework_related_products() {
		global $woo_options;
		if ( isset( $woo_options[ 'woocommerce_related_products' ] ) && $woo_options[ 'woocommerce_related_products' ] == "false" ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
	}
}

// Change thumbs on the single page to 4 per column
add_filter( 'woocommerce_product_thumbnails_columns', 'woocommerce_custom_product_thumbnails_columns' );
if (!function_exists('woocommerce_custom_product_thumbnails_columns')) {
	function woocommerce_custom_product_thumbnails_columns() {
		return 4;
	}
}

// Change number or products per row to 4
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4;
	}
}

// Remove add to cart button on archives
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Remove sale flash on archives
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

/*-----------------------------------------------------------------------------------*/
/* SINGLE PRODUCTS */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woocommerce_output_related_products' ) && version_compare( WOOCOMMERCE_VERSION, "2.1" ) < 0 ) {
	function woocommerce_output_related_products() {
	    woocommerce_related_products( -1, 4 );
	}
}

add_filter( 'woocommerce_output_related_products_args', 'mystile_related_products' );
function mystile_related_products() {
	$args = array(
		'posts_per_page' => 4,
		'columns'        => 4,
	);
	return $args;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerceframework_upsell_display', 15 );
if (!function_exists('woocommerceframework_upsell_display')) {
	function woocommerceframework_upsell_display() {
	    woocommerce_upsell_display( -1, 4 );
	}
}

/*-----------------------------------------------------------------------------------*/
/* LAYOUT */
/*-----------------------------------------------------------------------------------*/

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Add the WC sidebar in the right place and remove it from shop archives if specified
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10);

if ( ! function_exists( 'woocommerce_get_sidebar' ) ) {
	function woocommerce_get_sidebar() {

		global $woo_options;

		// Display the sidebar if full width option is disabled on archives
		if ( $woo_options[ 'woocommerce_archives_fullwidth' ] == "true" && (is_archive(array('product'))) ) :
			get_sidebar('shop');
		endif;

		// Display the sidebar if full width option is disabled on product pages
		if ( $woo_options[ 'woocommerce_products_fullwidth' ] == "true" && (is_singular(array('product'))) ) :
			get_sidebar('shop');
		endif;

		// Always display sidebar on products and pages
		if (is_page()) :
			get_sidebar('shop');
		endif;
	}
}

// Add a class to the body if full width shop archives are specified
add_filter( 'body_class','wooframework_layout_body_class', 10 );		// Add layout to body_class output
if ( ! function_exists( 'wooframework_layout_body_class' ) ) {
	function wooframework_layout_body_class( $wc_classes ) {

		global $woo_options;

		$layout = '';

		// Add woocommerce-fullwidth class if full width option is enabled
		if ( $woo_options[ 'woocommerce_archives_fullwidth' ] == "false" && (is_shop() || is_product_category())) {
			$layout = 'layout-full';
		}

		if ( $woo_options[ 'woocommerce_products_fullwidth' ] == "false" && (is_product())) {
			$layout = 'layout-full';
		}

		// Add classes to body_class() output
		$wc_classes[] = $layout;
		return $wc_classes;

	} // End woocommerce_layout_body_class()
}



/*-------------------------------------------------------------------------------------------*/
/* BREADCRUMB */
/*-------------------------------------------------------------------------------------------*/

// Remove WC breadcrumb (we're using the WooFramework breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'star_sidebar');

if (!function_exists('star_sidebar')) {
	function star_sidebar() {
		return 12;
	}
}

/*-------------------------------------------------------------------------------------------*/
/* HOMEPAGE CONTENT */
/*-------------------------------------------------------------------------------------------*/

add_action('mystile_homepage_content', 'mystile_product_categories', 10);
add_action('mystile_homepage_content', 'mystile_featured_products', 10);
add_action('mystile_homepage_content', 'mystile_recent_products', 30);

function mystile_product_categories() {
	global $woo_options;
	$params = apply_filters( 'mystile_homepage_product_categories_params', 'number=""' );
	if (class_exists('woocommerce') && $woo_options[ 'woo_homepage_product_categories' ] == "true" ) {
		echo '<h1>'.__('Product Categories', 'woothemes').'</h1>';
		echo do_shortcode('[product_categories '. $params .']');
		woocommerce_reset_loop(); // can be removed post WooCommerce 1.6.4
	} // End query to see if products should be displayed
}

function mystile_featured_products() {
	global $woo_options;
	if (class_exists('woocommerce') && $woo_options[ 'woo_homepage_featured_products' ] == "true" ) {
		echo '<h1>'.__('Featured Products', 'woothemes').'</h1>';
		$featuredproductsperpage = $woo_options['woo_homepage_featured_products_perpage'];
		echo do_shortcode('[featured_products per_page="'.$featuredproductsperpage.'"]');
	} // End query to see if products should be displayed
}

function mystile_recent_products() {
	global $woo_options;
	if (class_exists('woocommerce') && $woo_options[ 'woo_homepage_products' ] == "true" ) {
		echo '<h1>'.__('Recent Products', 'woothemes').'</h1>';
		$productsperpage = $woo_options['woo_homepage_products_perpage'];
		echo do_shortcode('[recent_products per_page="'.$productsperpage.'"]');
	} // End query to see if products should be displayed
}

/*-------------------------------------------------------------------------------------------*/
/* AJAX FRAGMENTS */
/*-------------------------------------------------------------------------------------------*/

add_filter( 'add_to_cart_fragments', 'header_add_to_cart_fragment' );
function header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	woocommerce_cart_link();
	$fragments['li.cart'] = ob_get_clean();
	return $fragments;
}

// Handle cart in header fragment for ajax add to cart
function woocommerce_cart_link() {
	global $woocommerce;
	?>
	<li class="cart">
	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>" class="cart-parent">
		<span>
	<?php
	echo $woocommerce->cart->get_cart_total();
	echo '<span class="contents">' . sprintf(_n('%d item', '%d items', $woocommerce->cart->get_cart_contents_count(), 'woothemes'), $woocommerce->cart->get_cart_contents_count()) . '</span>';
	?>
	</span>
	</a>
	</li>
	<?php
}
}