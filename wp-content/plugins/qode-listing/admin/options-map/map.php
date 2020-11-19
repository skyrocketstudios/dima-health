<?php

if ( ! function_exists('qode_listing_options_map') ) {

	function qode_listing_options_map() {
		if(qode_listing_theme_installed()) {
			bridge_qode_add_admin_page(array(
				'slug' => '_listing',
				'title' => esc_html__('Listing', 'qode-listing'),
				'icon' => 'fa fa-bookmark'
			));

			$panel_general = bridge_qode_add_admin_panel(array(
				'title'	=> esc_html__('General', 'qode-listing'),
				'name'	=> 'panel_general',
				'page'	=> '_listing'
			));

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_general,
					'type' => 'yesno',
					'name' => 'listings_woocommerce_currency',
					'default_value' => 'no',
					'label' => esc_html__('Enable WooCommerce currency for Listings', 'qode-listing'),
					'description' => esc_html__('Enable this option if you want to use WooCommerce currency set in WooCommerce -> Settings -> Currency as currency for Listings','qode-listing'),
				)
			);

			$panel_archive = bridge_qode_add_admin_panel(array(
				'title' => esc_html__('Archive', 'qode-listing'),
				'name' => 'panel_archive',
				'page' => '_listing'
			));

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_archive,
					'type' => 'text',
					'name' => 'listings_per_page',
					'default_value' => '',
					'label' => esc_html__('Number of listings per page', 'qode-listing'),
					'args' => array(
						'col_width' => 3
					)
				)
			);
			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_archive,
					'type' => 'yesno',
					'name' => 'listings_archive_load_more',
					'default_value' => 'yes',
					'label' => esc_html__('Load More on Archive Pages', 'qode-listing'),
					'description' => '',
				)
			);

			$panel_single = bridge_qode_add_admin_panel(array(
				'title' => esc_html__('Single', 'qode-listing'),
				'name' => 'panel_single',
				'page' => '_listing'
			));

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_single,
					'type' => 'text',
					'name' => 'listing_item_single_slug',
					'default_value' => '',
					'label' => esc_html__('Listing Single Slug', 'qode-listing'),
					'description' => esc_html__('Enter if you wish to use a different Single Listing slug (Note: After entering slug, navigate to Settings -> Permalinks and click "Save" in order for changes to take effect)', 'qode-listing'),
					'args' => array(
						'col_width' => 3
					)
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_single,
					'type' => 'yesno',
					'name' => 'listing_content_bottom',
					'default_value' => 'yes',
					'label' => esc_html__('Enable content bottom area', 'qode-listing'),
					'description' => '',
				)
			);


			$panel_maps = bridge_qode_add_admin_panel(array(
				'title' => 'Maps',
				'name' => 'panel_maps',
				'page' => '_listing'
			));

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'textarea',
					'name' => 'listing_map_style',
					'default_value' => '',
					'label' => esc_html__('Maps Style', 'qode-listing'),
					'description' => esc_html__('Insert map style json', 'qode-listing'),
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'yesno',
					'name' => 'listing_maps_scrollable',
					'default_value' => 'yes',
					'label' => esc_html__('Scrollable Maps', 'qode-listing'),
					'description' => '',
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'yesno',
					'name' => 'listing_maps_draggable',
					'default_value' => 'yes',
					'label' => esc_html__('Draggable Maps', 'qode-listing'),
					'description' => '',
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'yesno',
					'name' => 'listing_maps_street_view_control',
					'default_value' => 'yes',
					'label' => esc_html__('Maps Street View Controls', 'qode-listing'),
					'description' => '',
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'yesno',
					'name' => 'listing_maps_zoom_control',
					'default_value' => 'yes',
					'label' => esc_html__('Maps Zoom Control', 'qode-listing'),
					'description' => '',
				)
			);

			bridge_qode_add_admin_field(
				array(
					'parent' => $panel_maps,
					'type' => 'yesno',
					'name' => 'listing_maps_type_control',
					'default_value' => 'yes',
					'label' => esc_html__('Maps Type Control', 'qode-listing'),
					'description' => '',
				)
			);
		}
	}
	add_action( 'bridge_qode_action_options_map', 'qode_listing_options_map', 115);
}