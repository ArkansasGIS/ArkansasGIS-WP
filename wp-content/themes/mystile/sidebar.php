<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php 
/**
 * Sidebar Template
 *
 * If a `primary` widget area is active and has widgets, display the sidebar.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;
	
	if ( isset( $woo_options['woo_layout'] ) && ( $woo_options['woo_layout'] != 'layout-full' ) ) {
?>	
<aside id="sidebar" class="col-right">

	<?php woo_sidebar_inside_before(); ?>

	<?php if ( woo_active_sidebar( 'primary' ) ) { ?>
    <div class="primary">
		<?php woo_sidebar( 'primary' );  ?>
	</div>        
	<?php } // End IF Statement ?>   
	
	<?php woo_sidebar_inside_after(); ?> 
	
</aside><!-- /#sidebar -->
<?php } // End IF Statement ?>