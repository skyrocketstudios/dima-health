<?php
use QodeListing\Archive;
if(!function_exists('qode_listing_is_wp_job_manager_installed')){
	/**
	 * check if is installed Wp Job Manager Plugin
	 */
	function qode_listing_is_wp_job_manager_installed(){
		$flag = false;
		if(defined('JOB_MANAGER_VERSION')){
			$flag = true;
		}
		return $flag;
	}
}


if(!function_exists('qode_listing_theme_installed')) {
	/**
	 * Checks whether theme is installed or not
	 * @return bool
	 */
	function qode_listing_theme_installed() {
		return defined('QODE_ROOT');
	}
}

if(!function_exists('qode_listing_is_wc_paid_listings_installed')){
	/**
	 * check if is installed WC Paid Listings Plugin
	 */
	function qode_listing_is_wc_paid_listings_installed(){
		$flag = false;
		if(defined('JOB_MANAGER_WCPL_VERSION')){
			$flag = true;
		}
		return $flag;
	}

}

if(!function_exists('qode_listing_is_wp_job_manager_locations_installed')){
	/**
	 * check if is installed Wp Job Manager Regions Plugin
	 */
	function qode_listing_is_wp_job_manager_locations_installed(){
		$flag = false;
		if(class_exists('Astoundify_Job_Manager_Regions') && qode_listing_is_wp_job_manager_installed()){
			$flag = true;
		}
		return $flag;
	}

}

if(!function_exists('qode_listing_is_wp_job_manager_tags_installed')){
	/**
	 * check if is installed Wp Job Manager Tags Plugin
	 */
	function qode_listing_is_wp_job_manager_tags_installed(){
		$flag = false;
		if(defined('JOB_MANAGER_TAGS_VERSION')){
			$flag = true;
		}
		return $flag;
	}

}


if(!function_exists('qode_listing_set_ajax_url')){
	/**
	 * load plugin ajax functionality
	 */
	function qode_listing_set_ajax_url() {
		echo '<script type="application/javascript">var QodeListingAjaxUrl = "'.admin_url('admin-ajax.php').'"</script>';
	}

	add_action('wp_enqueue_scripts', 'qode_listing_set_ajax_url');
}

if(!function_exists('qode_listing_load_js_assets')){
	function qode_listing_load_js_assets(){
		wp_enqueue_script( 'qode_rangeslider', QODE_LISTING_URL_PATH.'assets/js/rangeslider.min.js', array('jquery'), false, true );
		wp_enqueue_script( 'select2', QODE_LISTING_URL_PATH.'assets/js/select2.min.js', array(), false, true );
		wp_enqueue_script( 'qode_listing_script', QODE_LISTING_URL_PATH.'assets/js/listing.js', array('jquery', 'underscore', 'jquery-ui-autocomplete'), false, true );

	}
	//set low priority because listing.min.js need to be loaded after modules.min.js and google api script
	add_action('wp_enqueue_scripts', 'qode_listing_load_js_assets', 103);
}

if(!function_exists('qode_listing_load_css_assets')){
	function qode_listing_load_css_assets(){

		wp_enqueue_style('qode_listing_style', QODE_LISTING_URL_PATH.'assets/css/listing.css');
		//if (qode_is_responsive_on()) {
			wp_enqueue_style('qode_listing_style_responsive', QODE_LISTING_URL_PATH.'assets/css/listing-responsive.min.css');
		//}
	}
	add_action('wp_enqueue_scripts', 'qode_listing_load_css_assets');
}

if(!function_exists('qode_listing_remove_yoast_for_taxonomoies')) {
    function qode_listing_remove_yoast_for_taxonomoies() {
        if(is_admin() && isset($GLOBALS) && isset($GLOBALS['_GET']) && isset($GLOBALS['_GET']['taxonomy'])) {
            $taxonomy = $GLOBALS['_GET']['taxonomy'];
            if($taxonomy == 'job_listing_type') {
                remove_action( 'plugins_loaded', 'wpseo_admin_init', 15 );
            }
        }
    }

    add_action( 'plugins_loaded', 'qode_listing_remove_yoast_for_taxonomoies' );
}