<?php
if(!function_exists('qode_listing_map_listing_settings')) {
	function qode_listing_map_listing_settings() {
		if(qode_listing_theme_installed()) {
			$listing_types = qode_listing_get_listing_types(true);
			$listing_types_by_key = $listing_types['key_value'];
			$listing_types_objects = $listing_types['obj'];

			$default_value = '';
			if (isset($listing_types_by_key[0])) {
				$default_value = $listing_types_by_key[0];
			}

			$enable_multi_listings = job_manager_multi_job_type();
			$enable_categories = qode_listing_enable_categories();

			qode_listing_add_listing_field(
				array(
					'id' => 'listing_price',
					'type' => 'text',
					'label' => esc_html__('Price', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('e.q 7000', 'qode-listing'),
					'description' => '',
					'priority' => 8
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_disc_price',
					'type' => 'text',
					'label' => esc_html__('Discount Price', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('e.q 7000', 'qode-listing'),
					'description' => '',
					'priority' => 9
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_gallery_images',
					'type' => 'file',
					'multiple' => true,
					'label' => esc_html__('Gallery', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Image', 'qode-listing'),
					'description' => '',
					'priority' => 10
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_phone',
					'type' => 'text',
					'label' => esc_html__('Phone', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Enter phone number', 'qode-listing'),
					'description' => '',
					'priority' => 11
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_mail',
					'type' => 'text',
					'label' => esc_html__('E-mail', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Enter e-mail', 'qode-listing'),
					'description' => '',
					'priority' => 12
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_self_hosted_video',
					'type' => 'text',
					'label' => esc_html__('Self Hosted Video', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Enter Video Url', 'qode-listing'),
					'description' => esc_html__('Note that if Youtube or Vimeo field is set, this video will not be shown', 'qode-listing'),
					'priority' => 13
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_video',
					'type' => 'text',
					'label' => esc_html__('Video Url', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Enter Video Url', 'qode-listing'),
					'description' => esc_html__('Youtube or Vimeo Video Url', 'qode-listing'),
					'priority' => 14
				)
			);
			qode_listing_add_listing_field(
				array(
					'id' => 'listing_video_text',
					'type' => 'textarea',
					'label' => esc_html__('Video Text', 'qode-listing'),
					'required' => false,
					'placeholder' => esc_html__('Enter Video Text', 'qode-listing'),
					'description' => '',
					'priority' => 15
				)
			);
			$social_network_array = qode_listing_get_listing_social_network_array();

			$counter = 15;
			foreach ($social_network_array as $network) {
				$counter++;
				qode_listing_add_listing_field(
					array(
						'id' => 'listing_' . $network['id'] . '_url',
						'type' => 'text',
						'label' => esc_html($network['name']),
						'required' => false,
						'placeholder' => esc_html($network['label']),
						'description' => '',
						'priority' => $counter
					)
				);
			}


			$listing_meta_box = bridge_qode_create_meta_box(
				array(
					'scope' => array('job_listing'),
					'title' => esc_html__('Listing Meta Box', 'qode-listing'),
					'name' => 'listing-meta-box'
				)
			);

			$listing_meta_box_title = bridge_qode_add_admin_section_title(
				array(
					'parent' => $listing_meta_box,
					'title' => esc_html__('General Settings', 'qode-listing'),
					'name' => 'listing_type_categories_title'
				)
			);

			bridge_qode_create_meta_box_field(
				array(
					'name' => 'qode_listing_content_bottom_meta',
					'type' => 'select',
					'default_value' => '',
					'label' => esc_html__('Enable Content Bottom Area on Single Pages', 'listing_wireframe'),
					'parent' => $listing_meta_box,
					'options' => array(
						'' => esc_html__('Default', 'qode-listing'),
						'yes' => esc_html__('Yes', 'qode-listing'),
						'no' => esc_html__('No', 'qode-listing'),
					),
				)
			);


			$ls_type_hide_array = array();
			$ls_type_show_array = array();
			if (is_array($listing_types_objects) && count($listing_types_objects)) {

				foreach ($listing_types_objects as $ls_type) {

					$ls_type_hide_array[$ls_type->term_id] = '';

					//generate show array for qode_listing_type field(Listing Type select field)
					//set current listing type container to be visible

					$ls_type_show_array[$ls_type->term_id] = '#qodef_ls_type_custom_fields_' . $ls_type->term_id . '_container,';
					$ls_type_show_array[$ls_type->term_id] .= '#qodef_ls_type_amenities_' . $ls_type->term_id . '_container,';
					$ls_type_show_array[$ls_type->term_id] .= '#qodef_ls_type_categories_' . $ls_type->term_id . '_container,';


					foreach ($listing_types_objects as $type) {

						if ($type->term_id !== $ls_type->term_id) {

							//generate hide array for listing type select field
							//hide listing type container(except current listing type)
							$ls_type_hide_array[$ls_type->term_id] .= '#qodef_ls_type_custom_fields_' . $type->term_id . '_container,';
							$ls_type_hide_array[$ls_type->term_id] .= '#qodef_ls_type_amenities_' . $type->term_id . '_container,';
							$ls_type_hide_array[$ls_type->term_id] .= '#qodef_ls_type_categories_' . $type->term_id . '_container,';

						}

					}

					$ls_type_hide_array[$ls_type->term_id] = rtrim($ls_type_hide_array[$ls_type->term_id], ',');
					$ls_type_show_array[$ls_type->term_id] = rtrim($ls_type_show_array[$ls_type->term_id], ',');

				}
				//if multi job selection is enabled, provide multi select field
				//in other case, provide default select button
				$select_button = '';
				if ($enable_multi_listings) {
					//dependency and saving in database will be based on this field
					$select_button = 'qode_listing_item_multi_type';
					bridge_qode_create_meta_box_field(
						array(
							'name' => 'qode_listing_item_multi_type',
							'type' => 'checkboxgroup',
							'default_value' => '',
							'label' => esc_html__('Choose Listing Types', 'listing_wireframe'),
							'parent' => $listing_meta_box,
							'options' => $listing_types_by_key,
							'args' => array(
								'dependence' => true,
								'show' => $ls_type_show_array
							)
						)
					);
				} else {
					//dependency and saving in database will be based on this field
					$select_button = 'qode_listing_item_type';
					bridge_qode_create_meta_box_field(
						array(
							'name' => 'qode_listing_item_type',
							'type' => 'select',
							'label' => esc_html__('Listing Type', 'qode-listing'),
							'description' => esc_html__('Choose a default type for Single Listings', 'qode-listing'),
							'default_value' => $default_value,
							'parent' => $listing_meta_box,
							'options' => $listing_types_by_key,
							'args' => array(
								'dependence' => true,
								'hide' => $ls_type_hide_array,
								'show' => $ls_type_show_array
							)
						)
					);
				}

				foreach ($listing_types_objects as $ls_type) {

					$ls_type_hidden_values = array();

					foreach ($listing_types_objects as $type) {

						if ($type->term_id !== $ls_type->term_id) {
							$ls_type_hidden_values[] = $type->term_id;
						}

					}

					//get job categories related to Listing Type
					$ls_type_cats = qode_listing_get_listing_type_categories($ls_type->term_id);

					if (is_array($ls_type_cats) && count($ls_type_cats) && $enable_categories) {

						$listing_type_cats_container = bridge_qode_add_admin_container(
							array(
								'parent' => $listing_meta_box,
								'name' => 'ls_type_categories_' . $ls_type->term_id . '_container',
								'hidden_property' => $select_button,
								'hidden_value' => '',
								'hidden_values' => $ls_type_hidden_values
							)
						);
						$listing_type_cats_title = bridge_qode_add_admin_section_title(
							array(
								'parent' => $listing_type_cats_container,
								'title' => esc_html__('Listing Type', 'qode-listing') . ' "' . esc_html($ls_type->name) . '" ' . esc_html__('Categories', 'qode-listing'),
								'name' => 'listing_type_categories_title'
							)
						);
						bridge_qode_create_meta_box_field(
							array(
								'name' => 'qode_listing_type_categories',
								'type' => 'checkboxgroup',
								'default_value' => '',
								'label' => esc_html__('Choose Categories', 'qode-listing'),
								'parent' => $listing_type_cats_container,
								'options' => $ls_type_cats
							)
						);
					}

					//generate custom fields for Listing Type
					$custom_field_array = qode_listing_get_listing_type_custom_fields($ls_type->term_id);
					if (is_array($custom_field_array) && count($custom_field_array)) {

						$ls_type_custom_field_container = bridge_qode_add_admin_container(
							array(
								'parent' => $listing_meta_box,
								'name' => 'ls_type_custom_fields_' . $ls_type->term_id . '_container',
								'hidden_property' => $select_button,
								'hidden_value' => '',
								'hidden_values' => $ls_type_hidden_values
							)
						);

						$custom_fields_title = bridge_qode_add_admin_section_title(
							array(
								'parent' => $ls_type_custom_field_container,
								'title' => esc_html__('Listing Type', 'qode-listing') . ' "' . esc_html($ls_type->name) . '" ' . esc_html__('Custom Fields', 'qode-listing'),
								'name' => 'listing_type_custom_fields_title'
							)
						);

						foreach ($custom_field_array as $custom_field) {
							$options = array();
							$field_type = $custom_field['field_type'];
							if ($custom_field['field_type'] === 'select') {
								$field_type = 'selectblank';
								$options = qode_listing_get_listing_type_options_array($custom_field);
							}

							bridge_qode_create_meta_box_field(
								array(
									'type' => $field_type,
									'name' => $custom_field['meta_key'],
									'default_value' => '',
									'label' => $custom_field['title'],
									'options' => $options,
									'parent' => $ls_type_custom_field_container
								)
							);

						}
					}

					//generate amenities for Listing Type
					$amenities_array = qode_listing_get_listing_type_amenities($ls_type->term_id);
					if (is_array($amenities_array) && count($amenities_array)) {
						$listing_type_amenities_container = bridge_qode_add_admin_container(
							array(
								'parent' => $listing_meta_box,
								'name' => 'ls_type_amenities_' . $ls_type->term_id . '_container',
								'hidden_property' => $select_button,
								'hidden_value' => '',
								'hidden_values' => $ls_type_hidden_values
							)
						);

						$amenities_title = bridge_qode_add_admin_section_title(
							array(
								'parent' => $listing_type_amenities_container,
								'title' => esc_html__('Listing Type', 'qode-listing') . ' "' . esc_html($ls_type->name) . '" ' . esc_html__('Amenities', 'qode-listing'),
								'name' => 'listing_type_amenities_title'
							)
						);

						foreach ($amenities_array as $key => $amenity) {

							$amenity_field_name = qode_listing_get_listing_type_amenity_field_name_refactored($ls_type->term_id, $key);
							bridge_qode_create_meta_box_field(
								array(
									'type' => 'checkbox',
									'name' => $amenity_field_name,
									'default_value' => '',
									'label' => $amenity['name'],
									'parent' => $listing_type_amenities_container
								)
							);

						}
					}
				}


			}
		}
	}
	add_action('qode_listing_meta_boxes_map_on_init_action', 'qode_listing_map_listing_settings');
}