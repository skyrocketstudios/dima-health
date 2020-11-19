<?php
if(!function_exists('qode_listing_save_listing_custom_post_type')){
	/**
	 * Add job_listing custom post in save array
	 */
	function qode_listing_save_listing_custom_post_type($post_types){

		$post_types[] = 'job_listing';
		return $post_types;

	}

	add_filter('bridge_qode_filter_meta_box_post_types_save', 'qode_listing_save_listing_custom_post_type');
}

if(!function_exists('qode_listing_remove_meta_boxes')){
	/**
	 * Remove meta boxes for job_listing post type
	 */
	function qode_listing_remove_meta_boxes($post_types){
		$post_types[] = 'job_listing';
		return $post_types;
	}
	add_filter('bridge_qode_filter_meta_box_post_types_remove', 'qode_listing_remove_meta_boxes');
}


if(!function_exists('qode_listing_remove_listing_taxonomy_meta_boxes')){
	/**
	 * Remove Job Listing Taxonomy Meta Boxes(Category and Type)
	 * We have our own meta boxes for categories and types
	 */
	function qode_listing_remove_listing_taxonomy_meta_boxes(){

		remove_meta_box('job_listing_categorydiv', 'job_listing', 'side');
		remove_meta_box('job_listing_typediv', 'job_listing', 'side');
		remove_meta_box('job_listing_type', 'job_listing', 'side');

	}
	add_action('admin_menu', 'qode_listing_remove_listing_taxonomy_meta_boxes');
}

if(!function_exists('qode_listing_edit_listing_fields')){
	/**
	 * Change default wp_job_manager_fields
	 */
	function qode_listing_edit_listing_fields( $fields ) {

		//unset job category because we will reinit after user define Listing Type
		if( isset( $fields['job']['job_category'])){
			unset(  $fields['job']['job_category'] );
		};
		unset( $fields['company'] );
		return $fields;

	}
	add_filter( 'job_manager_job_listing_data_fields', 'qode_listing_edit_listing_fields' );
	add_filter( 'submit_job_form_fields', 'qode_listing_edit_listing_fields' );
}

if(!function_exists('qode_listing_remove_preview_listing_steps')){
	/**
	 * Remove the preview step.
	 * @param  array $steps
	 * @return array
	 */

	function qode_listing_remove_preview_listing_steps( $steps ) {

		unset( $steps['preview'] );
		return $steps;

	}

	add_filter( 'submit_job_steps', 'qode_listing_remove_preview_listing_steps' );
}

if(!function_exists('qode_listing_change_listing_submit_review_text')){
	/**
	 * Change submit button text
	 */
	function qode_listing_change_listing_submit_review_text() {

		return esc_html__( 'Submit Listing', 'qode-listing' );

	}

	add_filter( 'submit_job_form_submit_button_text', 'qode_listing_change_listing_submit_review_text' );
}

if(!function_exists('qode_listing_publish_listing')){
	/**
	 * Since we removed the preview step and it's handler, we need to manually publish listings
	 * @param  int $id
	 */

	function qode_listing_publish_listing( $id ) {

		$listing = get_post( $id );
		if ( in_array( $listing->post_status, array( 'preview', 'expired' ) ) ) {
			// Reset expirey
			delete_post_meta( $listing->ID, '_job_expires' );
			// Update job listing
			$update_listing                  = array();
			$update_listing['ID']            = $listing->ID;
			$update_listing['post_status']   = get_option( 'job_manager_submission_requires_approval' ) ? 'pending' : 'publish';
			$update_listing['post_date']     = current_time( 'mysql' );
			$update_listing['post_date_gmt'] = current_time( 'mysql', 1 );
			wp_update_post( $update_listing );
		}
	}
	add_action( 'job_manager_job_submitted', 'qode_listing_publish_listing' );
}

if(!function_exists('qode_listing_published_send_email')){
	/**
	 * Send email to user if listing is approved
	 * @param  $post_id
	 */
	function qode_listing_published_send_email($post_id) {
		if( get_post_type( $post_id ) !== 'job_listing' ) {
			return;
		}
		$post = get_post($post_id);
		$author = get_userdata($post->post_author);

		$message = esc_html__('Hi ', 'qode-listing').$author->display_name.', '.esc_html__('Your listing ','qode-listing').$post->post_title.' '. esc_html__('has just been approved at ','qode-listing') .get_permalink( $post_id );
		if(isset($author->user_email)){
			wp_mail($author->user_email, esc_html__('Your listing is online', 'qode-listing'), $message);
		}
	}
	add_action('pending_to_publish', 'qode_listing_published_send_email');
	add_action('pending_payment_to_publish', 'qode_listing_published_send_email');
}

if(!function_exists('qode_listing_expired_send_email')){

	/**
	 * Send email to user if listing is expired
	 * @param  $post_id
	 */
	function qode_listing_expired_send_email($post_id) {
		if( get_post_type( $post_id ) !== 'job_listing' ) {
			return;
		}

		$post = get_post($post_id);
		$author = get_userdata($post->post_author);

		$message = esc_html__('Hi ', 'qode-listing').$author->display_name.', '.esc_html__('Your listing ','qode-listing').$post->post_title.' '. esc_html__('has now expired: ','qode-listing') .get_permalink( $post_id );

		if(isset($author->user_email)){
			wp_mail($author->user_email, esc_html__('Your listing has expired', 'qode-listing'), $message);
		}
	}
	add_action('expired_job_listing', 'qode_listing_expired_send_email');
}

if(!function_exists('qode_listing_resume_published_send_email')){
	/**
	 * Send email to user if listing is approved
	 * @param  $post_id
	 */
	function qode_listing_resume_published_send_email($post_id) {
		if( get_post_type( $post_id ) !== 'resume') {
			return;
		}

		$post = get_post($post_id);
		$author = get_userdata($post->post_author);

		$message = esc_html__('Hi ', 'qode-listing').$author->display_name.', '.esc_html__('Your resume ','qode-listing').$post->post_title.' '. esc_html__('has just been approved at ','qode-listing') .get_permalink( $post_id );

		if(isset($author->user_email)){
			wp_mail($author->user_email, esc_html__('Your listing has expired', 'qode-listing'), $message);
		}

	}
	add_action('pending_to_publish', 'qode_listing_resume_published_send_email');
	add_action('pending_payment_to_publish', 'qode_listing_resume_published_send_email');
}

if(!function_exists('qode_listing_give_user_package_on_registration')) {
	/**
	 * Add free package to a new user
	 *
	 */
	function qode_listing_give_user_package_on_registration( $user_id ) {
		global $wpdb;

		if(qode_listing_is_wc_paid_listings_installed()){
			$free_package = qode_listing_get_free_package();

			if(count($free_package)){

				$wpdb->insert(
					"{$wpdb->prefix}wcpl_user_packages",
					array(
						'user_id'          => $user_id,
						'product_id'       => $free_package['id'],  // This should be set to the ID of a package in WooCommerce if you want it to show a package name!
						'package_count'    => 0,
						'package_duration' => $free_package['package_duration'],
						'package_limit'    => $free_package['package_limit'],
						'package_featured' => $free_package['package_featured'],
						'package_type'     => 'job_listing'
					)
				);
			}
		}

	}
	add_action( 'user_register', 'qode_listing_give_user_package_on_registration' );
}

if(!function_exists('qode_listing_change_listing_slug')){
	/**
	 * Change Job Listing Slug
	 *
	 */
	function qode_listing_change_listing_slug( $args ) {
		if(qode_listing_theme_installed()) {
			$slug = bridge_qode_options()->getOptionValue('listing_item_single_slug');
			if ($slug !== '') {
				$args['rewrite']['slug'] = _x($slug, 'Listing permalink - resave permalinks after changing this', 'qode-listing');
			}
			return $args;
		}
	}

	add_filter( 'register_post_type_job_listing', 'qode_listing_change_listing_slug' );

}

if(!function_exists('qode_listing_override_listing_args')){
	/**
	 * Change Job Listing Args
	 *
	 */
	function qode_listing_override_listing_args($args){

		$singular  = __( 'Listing', 'qode-listing' );
		$plural    = __( 'Listings', 'qode-listing' );

		$args['labels'] = array(
			'name' 					=> $plural,
			'singular_name' 		=> $singular,
			'menu_name'             => __( 'Listings', 'qode-listing' ),
			'all_items'             => sprintf( __( 'All %s', 'qode-listing' ), $plural ),
			'add_new' 				=> __( 'Add New', 'qode-listing' ),
			'add_new_item' 			=> sprintf( __( 'Add %s', 'qode-listing' ), $singular ),
			'edit' 					=> __( 'Edit', 'qode-listing' ),
			'edit_item' 			=> sprintf( __( 'Edit %s', 'qode-listing' ), $singular ),
			'new_item' 				=> sprintf( __( 'New %s', 'qode-listing' ), $singular ),
			'view' 					=> sprintf( __( 'View %s', 'qode-listing' ), $singular ),
			'view_item' 			=> sprintf( __( 'View %s', 'qode-listing' ), $singular ),
			'search_items' 			=> sprintf( __( 'Search %s', 'qode-listing' ), $plural ),
			'not_found' 			=> sprintf( __( 'No %s found', 'qode-listing' ), $plural ),
			'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'qode-listing' ), $plural ),
			'parent' 				=> sprintf( __( 'Parent %s', 'qode-listing' ), $singular ),
			'featured_image'        => __( 'Featured image', 'qode-listing' ),
			'set_featured_image'    => __( 'Set featured image', 'qode-listing' ),
			'remove_featured_image' => __( 'Remove featured image', 'qode-listing' ),
			'use_featured_image'    => __( 'Use as featured image', 'qode-listing' ),
		);
		$args['show_in_nav_menus'] = true;
		return $args;
	}
	add_filter('register_post_type_job_listing', 'qode_listing_override_listing_args');
}

if(!function_exists('qode_listing_set_listing_post_type_support')){
	/**
	 * Add Listing Post Type Support(just comments for now)
	 *
	 */
	function qode_listing_set_listing_post_type_support() {
		add_post_type_support( 'job_listing', 'comments' );
	}
	add_action( 'init', 'qode_listing_set_listing_post_type_support' );
}


if(!function_exists('qode_listing_override_listing_type_args')){
	/**
	 * Change Job Listing Type Args
	 *
	 */
	function qode_listing_override_listing_type_args($args){

		$singular  = __( 'Listing type', 'qode-listing' );
		$plural    = __( 'Listing types', 'qode-listing' );

		$args['label']	= $plural;
		$args['labels'] = array(
			'name' 				=> $plural,
			'singular_name' 	=> $singular,
			'menu_name'         => ucwords( $plural ),
			'search_items' 		=> sprintf( __( 'Search %s', 'qode-listing' ), $plural ),
			'all_items' 		=> sprintf( __( 'All %s', 'qode-listing' ), $plural ),
			'parent_item' 		=> sprintf( __( 'Parent %s', 'qode-listing' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'qode-listing' ), $singular ),
			'edit_item' 		=> sprintf( __( 'Edit %s', 'qode-listing' ), $singular ),
			'update_item' 		=> sprintf( __( 'Update %s', 'qode-listing' ), $singular ),
			'add_new_item' 		=> sprintf( __( 'Add New %s', 'qode-listing' ), $singular ),
			'new_item_name' 	=> sprintf( __( 'New %s Name', 'qode-listing' ),  $singular )
		);
		return $args;
	}
	add_filter('register_taxonomy_job_listing_type_args', 'qode_listing_override_listing_type_args');
}

if(!function_exists('qode_listing_override_listing_categories_args')){
	/**
	 * Change Job Listing Type Args
	 *
	 */
	function qode_listing_override_listing_categories_args($args){

		$singular  = __( 'Listing category', 'qode-listing' );
		$plural    = __( 'Listing categories', 'qode-listing' );

		$args['label']	= $plural;
		$args['labels'] = array(
			'name' 				=> $plural,
			'singular_name' 	=> $singular,
			'menu_name'         => ucwords( $plural ),
			'search_items' 		=> sprintf( __( 'Search %s', 'qode-listing' ), $plural ),
			'all_items' 		=> sprintf( __( 'All %s', 'qode-listing' ), $plural ),
			'parent_item' 		=> sprintf( __( 'Parent %s', 'qode-listing' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'qode-listing' ), $singular ),
			'edit_item' 		=> sprintf( __( 'Edit %s', 'qode-listing' ), $singular ),
			'update_item' 		=> sprintf( __( 'Update %s', 'qode-listing' ), $singular ),
			'add_new_item' 		=> sprintf( __( 'Add New %s', 'qode-listing' ), $singular ),
			'new_item_name' 	=> sprintf( __( 'New %s Name', 'qode-listing' ),  $singular )
		);
		return $args;
	}
	add_filter('register_taxonomy_job_listing_category_args', 'qode_listing_override_listing_categories_args');
}
if(!function_exists('qode_listing_replace_tags_labels')){
	function qode_listing_replace_tags_labels() {

		global $wp_taxonomies;

		if ( ! isset( $wp_taxonomies['job_listing_tag'] ) ) {
			return;
		}

		// get the arguments of the already-registered taxonomy
		$job_listing_tag_args = get_taxonomy( 'job_listing_tag' ); // returns an object

		$labels = &$job_listing_tag_args->labels;

		$labels->name                       = esc_html__( 'Listing Tags', 'qode-listing' );
		$labels->singular_name              = esc_html__( 'Listing Tag', 'qode-listing' );
		$labels->search_items               = esc_html__( 'Search Listing Tags', 'qode-listing' );
		$labels->popular_items              = esc_html__( 'Popular Tags', 'qode-listing' );
		$labels->all_items                  = esc_html__( 'All Listing Tags', 'qode-listing' );
		$labels->parent_item                = esc_html__( 'Parent Listing Tag', 'qode-listing' );
		$labels->parent_item_colon          = esc_html__( 'Parent Listing Tag:', 'qode-listing' );
		$labels->edit_item                  = esc_html__( 'Edit Listing Tag', 'qode-listing' );
		$labels->view_item                  = esc_html__( 'View Tag', 'qode-listing' );
		$labels->update_item                = esc_html__( 'Update Listing Tag', 'qode-listing' );
		$labels->add_new_item               = esc_html__( 'Add New Listing Tag', 'qode-listing' );
		$labels->new_item_name              = esc_html__( 'New Listing Tag Name', 'qode-listing' );
		$labels->separate_items_with_commas = esc_html__( 'Separate tags with commas', 'qode-listing' );
		$labels->add_or_remove_items        = esc_html__( 'Add or remove tags', 'qode-listing' );
		$labels->choose_from_most_used      = esc_html__( 'Choose from the most used tags', 'qode-listing' );
		$labels->not_found                  = esc_html__( 'No tags found.', 'qode-listing' );
		$labels->no_terms                   = esc_html__( 'No tags', 'qode-listing' );
		$labels->menu_name                  = esc_html__( 'Listing Tags', 'qode-listing' );
		$labels->name_admin_bar             = esc_html__( 'Listing Tag', 'qode-listing' );

		$job_listing_tag_args->rewrite = array(
			'slug'       => _x( 'listing-tag', 'permalink', 'qode-listing' ),
			'with_front' => false,
			'ep_mask' => 0,
			'hierarchical' => false
		);


		// re-register the taxonomy
		register_taxonomy( 'job_listing_tag', array( 'job_listing' ), (array) $job_listing_tag_args );

	}
	add_action( 'init', 'qode_listing_replace_tags_labels' , 11);
}

if(!function_exists('qode_listing_replace_region_labels')){
	function qode_listing_replace_region_labels() {

		global $wp_taxonomies;

		if ( ! isset( $wp_taxonomies['job_listing_region'] ) ) {
			return;
		}

		// get the arguments of the already-registered taxonomy
		$job_listing_region_args = get_taxonomy( 'job_listing_region' ); // returns an object

		$labels = &$job_listing_region_args->labels;

		$labels->name                       = esc_html__( 'Listing Regions', 'qode-listing' );
		$labels->singular_name              = esc_html__( 'Listing Region', 'qode-listing' );
		$labels->search_items               = esc_html__( 'Search Listing Regions', 'qode-listing' );
		$labels->popular_items              = esc_html__( 'Popular Listing Regions', 'qode-listing' );
		$labels->all_items                  = esc_html__( 'All Listing Regions', 'qode-listing' );
		$labels->parent_item                = esc_html__( 'Parent Listing Region', 'qode-listing' );
		$labels->parent_item_colon          = esc_html__( 'Parent Listing Region:', 'qode-listing' );
		$labels->edit_item                  = esc_html__( 'Edit Listing Region', 'qode-listing' );
		$labels->view_item                  = esc_html__( 'View Listing Region', 'qode-listing' );
		$labels->update_item                = esc_html__( 'Update Listing Region', 'qode-listing' );
		$labels->add_new_item               = esc_html__( 'Add New Listing Region', 'qode-listing' );
		$labels->new_item_name              = esc_html__( 'New Listing Region Name', 'qode-listing' );
		$labels->separate_items_with_commas = esc_html__( 'Separate regions with commas', 'qode-listing' );
		$labels->add_or_remove_items        = esc_html__( 'Add or remove regions', 'qode-listing' );
		$labels->choose_from_most_used      = esc_html__( 'Choose from the most used regions', 'qode-listing' );
		$labels->not_found                  = esc_html__( 'No regions found.', 'qode-listing' );
		$labels->no_terms                   = esc_html__( 'No regions', 'qode-listing' );
		$labels->menu_name                  = esc_html__( 'Listing Regions', 'qode-listing' );
		$labels->name_admin_bar             = esc_html__( 'Listing Region', 'qode-listing' );
		$job_listing_region_args->label = esc_html__( 'Listing Regions', 'qode-listing' );

		$job_listing_region_args->rewrite = array(
			'slug'       => _x( 'listing-region', 'permalink', 'qode-listing' ),
			'with_front' => false,
			'ep_mask' => 0,
			'hierarchical' => true
		);

		// re-register the taxonomy
		register_taxonomy( 'job_listing_region', array( 'job_listing' ), (array) $job_listing_region_args );
	}
	add_action( 'init', 'qode_listing_replace_region_labels', 11 );
}