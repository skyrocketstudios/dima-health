<?php
use QodeListing\Lib\Shortcodes;
if(!function_exists('qode_listing_package_shortcode_helper')) {
	function qode_listing_package_shortcode_helper($shortcodes_class_name) {

		$shortcodes = array(
			'QodeListing\Lib\Shortcodes\ListingPackage'
		);

		$shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

		return $shortcodes_class_name;
	}

	add_filter('qode_listing_filter_add_vc_shortcode', 'qode_listing_package_shortcode_helper');
}


if(!function_exists('qode_listing_package_class_instance')){

	function qode_listing_package_class_instance(){
		return Shortcodes\ListingPackage::getInstance();
	}

}

if( !function_exists('qode_listing_set_ls_package_icon_class_name_for_vc_shortcodes') ) {
	/**
	 * Function that set custom icon class name for button shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function qode_listing_set_ls_package_icon_class_name_for_vc_shortcodes($shortcodes_icon_class_array) {
		$shortcodes_icon_class_array[] = '.icon-wpb-ls-package';

		return $shortcodes_icon_class_array;
	}

	add_filter('qode_core_filter_add_vc_shortcodes_custom_icon_class', 'qode_listing_set_ls_package_icon_class_name_for_vc_shortcodes');
}

if(!function_exists('qode_listing_get_listing_packages')){
    
    function qode_listing_get_listing_packages($atts = array()){
	
	$package_params = array(
		'post_type'        => 'product',
		'posts_per_page'   => -1,
		'order'            => 'asc',
		'orderby'          => 'menu_order',
		'suppress_filters' => false,
		'tax_query'        => array(
		    array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => array( 'job_package', 'job_package_subscription' )
			)
		 )
	);
	$params = array_merge($package_params,$atts );	
	$packages = get_posts($params);
	return $packages;
    }
}