<?php
if(qode_listing_is_wp_job_manager_installed()){

	require_once 'lib/shortcodes/shortcode-interface.php';
	require_once 'lib/listing-core-functions.php';
	require_once 'lib/listing-core-classes.php';
	require_once 'lib/listing-global-settings.php';
	require_once 'lib/listing-field-creator.php';
	require_once 'lib/custom-field-creator.php';
	require_once 'lib/front-end-field-creator.php';
	require_once 'lib/listing-repeater-field-functions.php';
	require_once 'lib/related-post.php';
	require_once 'modules/dashboard/dashboard-functions.php';
	require_once 'modules/maps/map-functions.php';
	require_once 'modules/maps/map-classes.php';
	require_once 'modules/job_manager/functions.php';
	require_once 'helpers/listing-helper-functions.php';
	require_once 'helpers/helper-functions.php';
	require_once 'helpers/listing-review-functions.php';
	require_once 'helpers/listing-ajax-helper-functions.php';
	require_once 'helpers/taxonomy-meta-fields.php';
	require_once 'admin/meta-box/map.php';
	require_once 'admin/options-map/map.php';
	require_once 'modules/single/functions.php';
	require_once 'modules/archive/functions.php';
	require_once 'modules/archive/classes.php';
    require_once 'modules/widgets/helper.php';
    require_once 'modules/widgets/listing_widget.php';
	//load custom styles
	if(!function_exists('qode_listing_load_custom_styles')) {
		function qode_listing_load_custom_styles() {
			require_once 'assets/custom-styles/listing.php';
		}
		add_action('after_setup_theme','qode_listing_load_custom_styles');
	}
}