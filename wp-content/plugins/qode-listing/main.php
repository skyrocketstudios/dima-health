<?php
/*
Plugin Name: Qode Listing
Description: Plugin that extends wp_job_manager functionality
Author: Qode Themes
Version: 2.0.3
*/

require_once 'const.php';
require_once 'helpers.php';

if(!function_exists('qode_listing_text_domain')) {
    /**
     * Loads plugin text domain so it can be used in translation
     */
    function qode_listing_text_domain() {
        load_plugin_textdomain('qode-listing', false, QODE_LISTING_REL_PATH.'/languages');
    }

    add_action('plugins_loaded', 'qode_listing_text_domain');
}

if(!function_exists('qode_listing_version_class')) {
	/**
	 * Adds plugins version class to body
	 * @param $classes
	 * @return array
	 */
	function qode_listing_version_class($classes) {
		$classes[] = 'qode-listing-'.QODE_LISTING_VERSION;
		
		return $classes;
	}
	
	add_filter('body_class', 'qode_listing_version_class');
}

if(!function_exists('qode_listing_load_files')) {
	/**
	 * load plugin files on init action
	 */
	function qode_listing_load_files() {
		require_once 'load.php';
	}

	add_action( 'plugins_loaded', 'qode_listing_load_files' );
}