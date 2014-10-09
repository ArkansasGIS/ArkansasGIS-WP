<?php
function pmwi_pmxi_custom_field_to_update( $field_to_update, $post_type, $options, $m_key ){

	if ($field_to_update === false || $post_type != 'product') return $field_to_update;	

	// Do not update attributes
	if ($options['update_all_data'] == 'no' and ! $options['is_update_attributes'] and ( ! in_array($cur_meta_key, array('_default_attributes', '_product_attributes')) or strpos($cur_meta_key, "attribute_") === false)) return true;

	if ($options['is_update_attributes'] and $options['update_attributes_logic'] == 'full_update') return true;
	if ($options['is_update_attributes'] and $options['update_attributes_logic'] == "only" and ! empty($options['attributes_list']) and is_array($options['attributes_list']) and in_array(str_replace("attribute_", "", $m_key), $options['attributes_list']) ) return true;
	if ($options['is_update_attributes'] and $options['update_attributes_logic'] == "all_except" and ( empty($options['attributes_list']) or ! in_array(str_replace("attribute_", "", $m_key), $options['attributes_list']) )) return true;

	return false;
	
}
?>