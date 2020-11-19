<?php
$this_object = qode_listing_adv_search_class_instance();
$load_more_option = $this_object->getBasicParamByKey('load_more');
$enable_load_more = ($load_more_option === 'yes') ? true : false;
if($enable_load_more){
	$button_params = array(
		'text' => esc_html__('Load More', 'qode-listing'),
		'custom_class' => 'qode-ls-adv-search-load-more qode-listing-button',
		'type' => 'solid',
		'html_type' => 'button'
	);
	$html = '<div class="qode-ls-adv-load-more-holder">';
	$html .= bridge_core_get_button_html($button_params);
	$html .= '</div>';
	echo $html;
}