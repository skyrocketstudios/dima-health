<?php
$load_more_option = bridge_qode_options()->getOptionValue('listings_archive_load_more');
$enable_load_more = $load_more_option === 'yes' ? true : false;

if($enable_load_more){
	echo bridge_core_get_button_html(array(
        'custom_class' => 'qode-listing-archive-load-more',
        'text'  => esc_html__('Load More','qode-listing'),
        'html_type' => 'button',
    ));;
}