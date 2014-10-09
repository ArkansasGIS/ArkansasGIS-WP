<?php
function pmwi_is_update_taxonomy( $articleData, $options, $tx_name ){

	if ( ! empty($articleData['ID']) ){
		if ($options['update_all_data'] == "no" and $options['update_categories_logic'] == "all_except" and !empty($options['taxonomies_list']) 
			and is_array($options['taxonomies_list']) and in_array($tx_name, $options['taxonomies_list'])) return false;
		if ($options['update_all_data'] == "no" and $options['update_categories_logic'] == "only" and ((!empty($options['taxonomies_list']) 
			and is_array($options['taxonomies_list']) and ! in_array($tx_name, $options['taxonomies_list'])) or empty($options['taxonomies_list']))) return false;
	}

	return true;
}