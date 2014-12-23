<?php
// File Security Check
if ( ! function_exists( 'wp' ) && ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?><?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	
?>

    <?php if ( $woo_options[ 'woo_homepage_banner' ] == "true" ) { ?>
    	<!-- TD: 20141223- Changes the <div> in order to clear up banner on homepage if selected
    	<div class="homepage-banner">-->
       <div class="">
    		<?php
				if ( $woo_options[ 'woo_homepage_banner' ] == "true" ) { $banner = $woo_options['woo_homepage_banner_path']; }
				if ( $woo_options[ 'woo_homepage_banner' ] == "true" && is_ssl() ) { $banner = preg_replace("/^http:/", "https:", $woo_options['woo_homepage_banner_path']); }
			?>
			    <img src="<?php echo $banner; ?>" alt="" />
    		<h1><span><?php echo $woo_options['woo_homepage_banner_headline']; ?></span></h1>
    		<div class="description"><?php echo wpautop($woo_options['woo_homepage_banner_standfirst']); ?></div>
    	</div>
    	
    <?php } ?>
    
    <div id="content" class="col-full <?php if ( $woo_options[ 'woo_homepage_banner' ] == "true" ) echo 'with-banner'; ?> <?php if ( $woo_options[ 'woo_homepage_sidebar' ] == "false" ) echo 'no-sidebar'; ?>">
    
    	<?php woo_main_before(); ?>
    
		<section id="main" class="col-left">  
		
		<?php 
		//
		// RDP added to show Recent Data Uploads   GEOSTOREDITS
		//
		if (class_exists('woocommerce') && $woo_options[ 'woo_homepage_products' ] == "true" ) {
			echo '<h1>'.__('Recent Data Uploads', 'woothemes').'</h1>';
			$productsperpage = $woo_options['woo_homepage_products_perpage'];
			echo do_shortcode('[product_category category="data" per_page="'.$productsperpage.'" orderby="date" order="DES"]');
		} // End query to see if products should be displayed
		//
		// RDP added to show Featured Places...etc
		//
		if (class_exists('woocommerce') && $woo_options[ 'woo_homepage_featured_products' ] == "true" ) {
			echo '<h1>'.__('Featured Places, Persons, Agencies, and Datasets', 'woothemes').'</h1>';
			$featuredproductsperpage = $woo_options['woo_homepage_featured_products_perpage'];
			echo do_shortcode('[featured_products per_page="'.$featuredproductsperpage.'"]');
		} // End query to see if products should be displayed
		//mystile_homepage_content(); 
		?>		
		
		<?php woo_loop_before(); ?>
		
		<?php if ( $woo_options[ 'woo_homepage_blog' ] == "true" ) { 
			$postsperpage = $woo_options['woo_homepage_blog_perpage'];
		?>
		
		<?php
			
			$the_query = new WP_Query( array( 'posts_per_page' => $postsperpage ) );
			
        	if ( have_posts() ) : $count = 0;
        ?>
        
			<?php /* Start the Loop */ ?>
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); $count++; ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php 
				endwhile; 
				// Reset Post Data
				wp_reset_postdata();
			?>
			
			

		<?php else : ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>
        
        <?php } // End query to see if the blog should be displayed ?>
        
        <?php woo_loop_after(); ?>
		                
		</section><!-- /#main -->
		
		<?php woo_main_after(); ?>

        <?php if ( $woo_options[ 'woo_homepage_sidebar' ] == "true" ) get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>