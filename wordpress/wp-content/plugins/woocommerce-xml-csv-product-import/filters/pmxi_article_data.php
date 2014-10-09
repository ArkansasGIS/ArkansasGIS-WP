<?php
function pmwi_pmxi_article_data($articleData, $import, $post_to_update){
	if ( $import->options['custom_type'] == 'product' and ! $import->options['is_update_product_type']){ 
		$articleData['post_type'] = $post_to_update->post_type;		
	}
	return $articleData;
}