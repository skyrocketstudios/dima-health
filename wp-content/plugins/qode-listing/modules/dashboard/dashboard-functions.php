<?php

if(!function_exists('qode_listing_set_listing_dashboard_navigation_pages')){

	/**
	 * Create listing dashboard navigation items
	 *
	 * @param array items - dashboard navigation items
	 * @param string dashboard_url - dashboard page url
	 *
	 * @return array
	 * see qode_membership_dashboard_navigation_pages in qode-membership plugin
	 */

	function qode_listing_set_listing_dashboard_navigation_pages( $items, $dashboard_url){

		$items['add-new-listing'] = array(
			'url'			=> esc_url( add_query_arg( array( 'user-action' => 'add-new-listing' ), $dashboard_url ) ),
			'text'			=> esc_html__( 'Add Listing', 'qode-listing' ),
			'user_action'	=> 'add-new-listing',
			'icon'			=> '<span class="icon dripicons-document-edit"></span>'
		);

		$items['my-listings'] = array(
			'url'			=> esc_url( add_query_arg( array( 'user-action' => 'my-listings' ), $dashboard_url ) ),
			'text'			=> esc_html__( 'My Listings', 'qode-listing' ),
			'user_action'	=> 'my-listings',
			'icon'			=> '<span class="icon dripicons-document-remove"></span>'
		);
		if(qode_listing_is_wc_paid_listings_installed()){
			$items['my-packages'] = array(
				'url'			=> esc_url( add_query_arg( array( 'user-action' => 'my-packages' ), $dashboard_url ) ),
				'text'			=> esc_html__( 'My Packages', 'qode-listing' ),
				'user_action'	=> 'my-packages',
				'icon'			=> '<span class="icon dripicons-briefcase"></span>'
			);
		}

		return $items;
	}

	add_filter('qode_membership_dashboard_navigation_pages' , 'qode_listing_set_listing_dashboard_navigation_pages', 10 , 2);
}

if(!function_exists('qode_listing_get_listing_dashboard_pages')){

	/**
	 * Create listing dashboard pages
	 *
	 * @param array $pages - dashboard navigation pages
	 *
	 * @return array
	 * see qode_membership_dashboard_pages in qode-membership plugin
	 */


	function qode_listing_get_listing_dashboard_pages($pages){

		$pages['add-new-listing'] = qode_listing_get_listing_module_template_part('modules/dashboard', 'add-new-listing');
		$pages['my-listings'] = qode_listing_get_listing_module_template_part('modules/dashboard', 'my-listings');
		if(qode_listing_is_wc_paid_listings_installed()){
			$pages['my-packages'] = qode_listing_get_listing_module_template_part('modules/dashboard', 'my-packages');
		}
		return $pages;
	}
	add_filter('qode_membership_dashboard_pages', 'qode_listing_get_listing_dashboard_pages', 10, 1);
}


if(!function_exists('qode_listing_set_user_dashboard_template_params')){
	/**
	 * Set post content for user dashboard pages
	 *
	 * User Dashboard Template need to have [job_dashboard] as post content in order to user wp_job_manager actions(Edit, Mark Filled, Duplicate, Delete)
	 *
	 * see shortcode_action_handler in wp_job_manager plugin
	 */
	function qode_listing_set_user_dashboard_template_params(){

		$page_args = array(
			'post_type' => 'page',
			'post_status' => 'publish',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'user-dashboard.php'
		);
		$user_dashboard_page = get_pages($page_args);

		if(is_array($user_dashboard_page) && count($user_dashboard_page)){
			foreach($user_dashboard_page as $page) {
				$current_page['ID'] = $page->ID;
				$current_page['post_content'] = '[job_dashboard]';
				wp_update_post( $current_page );
			}
		}

	}
	add_action('init', 'qode_listing_set_user_dashboard_template_params');
}