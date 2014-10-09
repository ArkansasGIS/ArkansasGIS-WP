<?php
function pmwi_pmxi_do_not_update_existing($post_to_update_id, $import_id, $iteration){
	$children = get_posts( array(
		'post_parent' 	=> $post_to_update_id,
		'posts_per_page'=> -1,
		'post_type' 	=> 'product_variation',
		'fields' 		=> 'ids',
		'post_status'	=> 'publish'
	) );

	if ( $children ) {		
		$postRecord = new PMXI_Post_Record();						
		foreach ( $children as $child ) {			
			$postRecord->clear();
			$postRecord->getBy(array(
				'post_id' => $child,
				'import_id' => $import_id
			));
			if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $iteration))->update();
		}
	}

}
?>