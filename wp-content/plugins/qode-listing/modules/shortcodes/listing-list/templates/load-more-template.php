<?php
$this_object = qode_listing_list_class_instance();
$load_more_option = $this_object->getBasicParamByKey('load_more');
$enable_load_more = ($load_more_option === 'yes') ? true : false;
if($enable_load_more){
	$button_params = array(
		'text' => esc_html__('Load More', 'qode-listing'),
		'custom_class' => 'qode-ls-list-load-more',
		'type' => 'solid',
		'html_type' => 'button',
		'color'     => '#353535',
		'background_color'     => '#fff',
		'border_color'     => '#f4f4f4'
	);
	$html = '<div class="qode-ls-list-load-more-holder">';
	$html .= bridge_core_get_button_v2_html($button_params);
	$html .= '</div>';
	echo $html;
}