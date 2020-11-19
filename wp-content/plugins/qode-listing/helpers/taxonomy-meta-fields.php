<?php
/**
 * Location category add taxonomy meta fields
 */
use QodeListing\Lib;

if ( ! function_exists( 'qode_listing_category_add_meta_fields' ) ) {
	/**
	 * Add field to add new listing location form
	 */
	function qode_listing_category_add_meta_fields() { ?>

        <div class="form-field term-icons-wrap">
			<label for="term-icons">
                <?php esc_html_e( 'Icon', 'qode-listing' ); ?>
            </label>

			<?php if ( function_exists('bridge_qode_icon_collections') ) {
				$icon_collections = bridge_qode_icon_collections()->getIconCollections();
				$collections      = array();
				foreach ( $icon_collections as $ic_key => $ic_name ) {
					$collections[] = bridge_qode_icon_collections()->getIconCollection( $ic_key );
				}
			} else {
				$icon_collections = array();
				$collections      = array();
			} ?>

			<div>
				<label for="icon_pack">
                    <?php esc_html_e('Icon Pack', 'qode-listing'); ?>
                </label>
				<select name="icon_pack" id="icon_pack">

					<?php foreach ( $icon_collections as $key => $value ) { ?>

						<option value="<?php echo esc_attr($key); ?>">
                            <?php echo esc_attr($value); ?>
                        </option>

					<?php } ?>

				</select>
			</div>

			<?php foreach ( $collections as $col ) { ?>

				<div class="icon-collection <?php echo str_replace( ' ', '_', strtolower( $col->title ) ); ?>" style="display: none">

                    <label for="<?php echo esc_attr($col->param); ?>">
                        <?php echo esc_attr($col->title);?>
                    </label>

					<select name="<?php echo esc_attr($col->param); ?>" id="<?php echo esc_attr($col->param); ?>">
						<?php
						$icons = bridge_qode_icon_collections()->getIconCollectionIcons( $col );

						foreach ( $icons as $key => $value ) { ?>

							<option value="<?php echo esc_attr($key); ?>">
                                <?php echo esc_attr($value); ?>
                            </option>

						<?php } ?>

					</select>
				</div>

			<?php } ?>

		</div>
		<div class="form-field" style="margin:0 10px 20px 0">

			<label style="margin-bottom: 10px;display: block"	for="listing_type">
                <?php esc_html_e( 'Choose Listing Type ( only for top parent categories )', 'qode-listing' ); ?>
            </label>

			<?php
			$types_array = qode_listing_get_listing_types(true);
			$types = $types_array['key_value'];
			?>

			<select name="listing_type" id="listing_type" style="min-width: 200px;">

                <?php foreach ( $types as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_attr( $value ) ?>
					</option>
				<?php } ?>

			</select>

		</div>

		<?php
		$size_array = array(
			'square-small'        => esc_html__('Square Small', 'qode-listing'),
			'square-big'          => esc_html__('Square Big', 'qode-listing'),
			'rec-portrait'  => esc_html__('Rectangle Portrait', 'qode-listing'),
			'rec-landscape' => esc_html__('Rectangle Landscape', 'qode-listing')
		);
		$types_array  = array(
			'standard' => esc_html__('Standard', 'qode-listing'),
			'simple'   => esc_html__('Simple', 'qode-listing')
		);
		?>

		<div class="form-field term-featured-image-wrap">

            <label for="term-featured-image">
				<?php esc_html_e( 'Featured Image', 'qode-listing' ); ?>
			</label>

			<div class="qode-media-uploader">

				<div class="qode-media-image-holder">
					<img src="" alt="" class="qode-media-image img-thumbnail"/>
				</div>

				<a class="qode-media-upload-btn btn btn-sm btn-primary"
					href="javascript:void(0)"
					data-frame-title="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>"
					data-frame-button-text="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>">
					<?php esc_html_e( 'Upload', 'qode-listing' ); ?>
				</a>

				<a style="display: none;" href="javascript: void(0)" class="qode-media-remove-btn btn btn-default btn-sm">
                    <?php esc_html_e( 'Remove', 'qode-listing' ); ?>
                </a>

				<div style="display: none" class="qode-media-meta-fields">
					<input type="hidden" name="featured_image" class="qode-media-upload-url"/>
				</div>

			</div>
		</div>

		<div class="form-field" style="margin:0 10px 20px 0">

			<label style="margin-bottom: 10px;display: block" for="gallery_size">
				<?php esc_html_e( 'Choose Gallery Size ( only for top listing categories shortcode )', 'qode-listing' ); ?>
			</label>

			<select name="gallery_size" id="gallery_size" style="min-width: 200px;">

				<?php foreach ( $size_array as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_attr( $value ) ?>
					</option>
				<?php } ?>

			</select>
		</div>

		<div class="form-field" style="margin:0 10px 20px 0">

            <label style="margin-bottom: 10px;display: block" for="gallery_type">
				<?php esc_html_e( 'Choose Gallery Type ( only for top listing categories shortcode )', 'qode-listing' ); ?>
			</label>

			<select name="gallery_type" id="gallery_type" style="min-width: 200px;">

				<?php foreach ( $types_array as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_attr( $value ) ?>
					</option>
				<?php } ?>

			</select>
		</div>
		<div class="form-field" style="margin:0 10px 20px 0">

			<label style="margin-bottom: 10px;display: block" for="category_custom_link">
				<?php esc_html_e( 'Set Category Custom Link(only if Simple Gallery Type is set)', 'qode-listing' ); ?>
			</label>
			<input type="text" name="category_custom_link" value="">

		</div>

	<?php }
	add_action( 'job_listing_category_add_form_fields', 'qode_listing_category_add_meta_fields', 10, 2 );

}

if ( ! function_exists( 'qode_listing_category_save_meta_fields' ) ) {
	/**
	 * Save listing location taxonomy meta field
	 *
	 * @param $term_id
	 * @param $taxonomy_id
	 */
	function qode_listing_category_save_meta_fields( $term_id, $taxonomy_id ) {
		if ( isset( $_POST ) ) {
			$icons_array = array(
				'icon_pack'
			);
			$icons_array_keys = bridge_qode_icon_collections()->getIconCollectionsKeys();
			foreach ($icons_array_keys as $icons_array_key) {
				$icons_array[] = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($icons_array_key);
			}
			foreach ($icons_array as $icon_obj){

				if(isset($_POST[$icon_obj])){
					add_term_meta($term_id, $icon_obj, $_POST[$icon_obj], true );
				}

			}

			//check if current term has ancestors
			$ancestors = get_ancestors( $term_id, 'job_listing_category' );

			//if current tax has ancestors, get top parent and set for child category listing type id from top parent
			if ( is_array( $ancestors ) && count( $ancestors ) ) {

				//get top parent id(always last element in ancestors array)
				$top_parent_id              = end( $ancestors );
				$top_parent_listing_type_id = get_term_meta( $top_parent_id, 'listing_type', true );

				if ( $top_parent_listing_type_id !== '' && !$top_parent_listing_type_id) {
					add_term_meta( $term_id, 'listing_type', esc_attr( $top_parent_listing_type_id ), true );
				}

			} else {
				if ( isset ( $_POST['listing_type'] ) ) {
					add_term_meta( $term_id, 'listing_type', esc_attr( $_POST['listing_type'] ), true );
				}
			}

			if(isset($_POST['gallery_size'])){
				add_term_meta($term_id, 'gallery_size', $_POST['gallery_size'], true );
			}

			if(isset($_POST['gallery_type'])){
				add_term_meta($term_id, 'gallery_type', $_POST['gallery_type'], true );
			}

			if(isset($_POST['category_custom_link'])){
				add_term_meta($term_id, 'category_custom_link', $_POST['category_custom_link'], true );
			}

			if ( isset($_POST['featured_image'])) {

				$image = esc_url( $_POST['featured_image'] );
				add_term_meta( $term_id, 'featured_image', $image, true );
			}
		}
	}
	add_action( 'create_job_listing_category', 'qode_listing_category_save_meta_fields', 10, 2 );

}

if ( ! function_exists( 'qode_listing_category_edit_meta_fields' ) ) {
	/**
	 * Edit Listing location taxonomy featured image
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	function qode_listing_category_edit_meta_fields( $term, $taxonomy ) {

		$icon_pack     = get_term_meta( $term->term_id, 'icon_pack', true );
		$selected_type = get_term_meta( $term->term_id, 'listing_type', true );
		$gallery_type = get_term_meta( $term->term_id, 'gallery_type', true );
		$gallery_size = get_term_meta( $term->term_id, 'gallery_size', true );
		$category_custom_link = get_term_meta( $term->term_id, 'category_custom_link', true );
		$featured_image = get_term_meta( $term->term_id, 'featured_image', true );
		?>

		<tr class="form-field term-featured-image-wrap">
			<th scope="row">
				<label for="term-featured-image">
					<?php esc_html_e( 'Featured Image', 'qode-listing' ); ?>
				</label>
			</th>
			<td>
				<div class="qode-media-uploader">
					<div class="qode-media-image-holder">
						<img src="<?php	if ( isset( $featured_image ) ) { echo esc_url($featured_image); } ?>" alt="" class="qode-media-image img-thumbnail" style="width: 150px"/>
					</div>
					<a class="qode-media-upload-btn btn btn-sm btn-primary"
						href="javascript:void(0)"
						data-frame-title="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>"
						data-frame-button-text="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>">

                        <?php esc_html_e( 'Upload', 'qode-listing' ); ?>

                    </a>

					<a style="display: none;" href="javascript: void(0)" class="qode-media-remove-btn btn btn-default btn-sm">
                        <?php esc_html_e( 'Remove', 'qode-listing' ); ?>
                    </a>

					<div style="display: none" class="qode-media-meta-fields">
						<input type="hidden" name="featured_image" value="<?php if ( isset( $featured_image ) ) { echo esc_url($featured_image); } ?>" class="qode-media-upload-url"/>
					</div>
				</div>
			</td>
		</tr>

		<tr class="form-field term-icons-wrap">

            <th scope="row">
                <label for="term-icons">
                    <?php esc_html_e( 'Icon', 'qode-listing' ); ?>
                </label>
            </th>

			<td>
				<?php if ( function_exists('bridge_qode_icon_collections') ) {

				    $icon_collections = bridge_qode_icon_collections()->getIconCollections();
					$collections      = array();

					foreach ( $icon_collections as $ic_key => $ic_name ) {
						$collections[] = bridge_qode_icon_collections()->getIconCollection( $ic_key );
					}

				} else {
					$icon_collections = array();
					$collections = array();
				} ?>

				<div>
					<label for="icon_pack">
                        <?php esc_html_e('Icon Pack','qode-listing');?>
                    </label>

					<select name="icon_pack" id="icon_pack">

                        <?php
						foreach ( $icon_collections as $key => $value ) { ?>
							<option
                                    value="<?php echo esc_attr($key); ?>"
                                <?php if ( $key == $icon_pack ) {
                                    echo 'selected';
                                } ?>>
                                <?php echo esc_attr($value); ?>
                            </option>
						<?php } ?>

					</select>
				</div>

				<?php foreach ( $collections as $col ) { ?>

                    <div class="icon-collection <?php echo str_replace( ' ', '_', strtolower( $col->title ) ); ?>" style="display: none">

                        <label for="<?php echo esc_attr($col->param); ?>">
                            <?php echo esc_attr($col->title); ?>
                        </label>
						<select name="<?php echo esc_attr($col->param); ?>" id="<?php echo esc_attr($col->param); ?>">
							<?php
							$selected_icon = get_term_meta( $term->term_id, $col->param, true );
							$icons         = bridge_qode_icon_collections()->getIconCollectionIcons( $col );
							foreach ( $icons as $key => $value ) { ?>
								<option value="<?php echo esc_attr($key); ?>" <?php if ( $key == $selected_icon ) {
									echo 'selected';
								    } ?>><?php echo esc_attr($value); ?>
                                </option>
							<?php } ?>

						</select>

					</div>

				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="form-field" style="margin:0 10px 20px 0">

					<label style="margin-bottom: 10px;display: block" for="listing_type">
                        <?php esc_html_e( 'Choose Listing Type ( only for top parent categories )', 'qode-listing' ); ?>
                    </label>

					<?php
					$types_array = qode_listing_get_listing_types(true);
					$types = $types_array['key_value'];
					?>

					<select name="listing_type" id="listing_type" style="min-width: 200px;">
						<?php
						if(is_array($types) && count($types)){
							foreach ( $types as $key => $value ) {
								?>
								<option
									value="<?php echo esc_attr( $key ) ?>" <?php if ( $key == $selected_type ) {
									echo 'selected';
								} ?>>
									<?php echo esc_attr( $value ) ?>
								</option>
							<?php }
						}?>
					</select>
				</div>

		<?php
		$size_array = array(
			'square-small'        => esc_html__('Square Small', 'qode-listing'),
			'square-big'          => esc_html__('Square Big', 'qode-listing'),
			'rec-portrait'  => esc_html__('Rectangle Portrait', 'qode-listing'),
			'rec-landscape' => esc_html__('Rectangle Landscape', 'qode-listing')
		);
		$types_array  = array(
			'standard' => esc_html__('Standard', 'qode-listing'),
			'simple'   => esc_html__('Simple', 'qode-listing')
		);
		?>
				<div class="form-field" style="margin:0 10px 20px 0">
					<label style="margin-bottom: 10px;display: block" for="gallery_size">
						<?php esc_html_e( 'Choose Listing Size ( only for listing categories shortcode )', 'qode-listing' ); ?>
					</label>

					<select name="gallery_size" id="gallery_size" style="min-width: 200px;">
						<?php
						if(is_array($size_array) && count($size_array)){
							foreach ( $size_array as $key => $value ) {
								?>
								<option
									value="<?php echo esc_attr( $key ) ?>" <?php if ( $key == $gallery_size ) {
									echo 'selected';
								} ?>>
									<?php echo esc_attr( $value ) ?>
								</option>
							<?php }
						}?>
					</select>

				</div>
				<div class="form-field" style="margin:0 10px 20px 0">

                    <label style="margin-bottom: 10px;display: block" for="gallery_type">
						<?php esc_html_e( 'Choose Listing Type ( only for listing categories shortcode )', 'qode-listing' ); ?>
					</label>

					<select name="gallery_type" id="gallery_type" style="min-width: 200px;">
						<?php
						if(is_array($types_array) && count($types_array)){
							foreach ( $types_array as $key => $value ) {
								?>
								<option
									value="<?php echo esc_attr( $key ) ?>" <?php if ( $key == $gallery_type ) {
									echo 'selected';
								} ?>>
									<?php echo esc_attr( $value ) ?>
								</option>
							<?php }
						}?>
					</select>
				</div>
				<div class="form-field" style="margin:0 10px 20px 0">

					<label style="margin-bottom: 10px;display: block" for="category_custom_link">
						<?php esc_html_e( 'Set Category Custom Link(only if Simple Gallery Type is set)', 'qode-listing' ); ?>
					</label>

					<input type="text" name="category_custom_link" value="<?php echo esc_attr($category_custom_link);?>">
				</div>
			</td>
		</tr>

	<?php }
	add_action( 'job_listing_category_edit_form_fields', 'qode_listing_category_edit_meta_fields', 11, 2 );

}

if ( ! function_exists( 'qode_listing_category_update_meta_fields' ) ) {
	/**
	 * Update listing location taxonomy meta field
	 *
	 * @param $term_id
	 * @param $taxonomy_id
	 */
	function qode_listing_category_update_meta_fields( $term_id, $taxonomy_id ) {

		if ( isset( $_POST ) ) {

			$icons_array = array(
				'icon_pack'
			);
			$icons_array_keys = bridge_qode_icon_collections()->getIconCollectionsKeys();
			foreach ($icons_array_keys as $icons_array_key) {
				$icons_array[] = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($icons_array_key);
			}

			foreach ($icons_array as $icon_obj){

				if(isset($_POST[$icon_obj])){
					update_term_meta($term_id, $icon_obj, $_POST[$icon_obj]);
				}

			}

			//check if current term has ancestors
			$ancestors = get_ancestors( $term_id, 'job_listing_category' );

			//if current tax has ancestors, get top parent and set for child category listing type id from top parent
			if ( is_array( $ancestors ) && count( $ancestors ) ) {

				//get top parent id(always last element in ancestors array)
				$top_parent_id              = end( $ancestors );
				$top_parent_listing_type_id = get_term_meta( $top_parent_id, 'listing_type', true );

				if ( $top_parent_listing_type_id !== '' &&  !$top_parent_listing_type_id) {
					update_term_meta( $term_id, 'listing_type', esc_attr( $top_parent_listing_type_id ) );
				}

			} else {
				if ( isset ( $_POST['listing_type'] ) ) {
					update_term_meta( $term_id, 'listing_type', esc_attr( $_POST['listing_type'] ) );
				}
			}

			if(isset($_POST['gallery_size'])){
				update_term_meta($term_id, 'gallery_size', $_POST['gallery_size']);
			}
			if(isset($_POST['gallery_type'])){
				update_term_meta($term_id, 'gallery_type', $_POST['gallery_type']);
			}
			if(isset($_POST['category_custom_link'])){
				update_term_meta($term_id, 'category_custom_link', $_POST['category_custom_link']);
			}
			if ( isset($_POST['featured_image'])) {

				$image = esc_url( $_POST['featured_image'] );
				update_term_meta( $term_id, 'featured_image', $image );
			}
		}

	}
	add_action( 'edited_job_listing_category', 'qode_listing_category_update_meta_fields', 10, 2 );

}


//create custom fields for job listing types
if ( ! function_exists( 'qode_listing_type_edit_meta_fields' ) ) {
	/**
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	function qode_listing_type_edit_meta_fields( $term, $taxonomy ) {
		$custom_field_array =  get_term_meta( $term->term_id, 'listing_type_custom_fields', true );
		$amenities_array =  get_term_meta( $term->term_id, 'listing_type_amenities', true );

		//generate html for custom field creation
		$custom_field_creator = new Lib\CustomFieldCreator();
		$custom_field_creator->render();

		if(is_array($custom_field_array) && count($custom_field_array)){ ?>

			<?php foreach($custom_field_array as $custom_field){
				switch($custom_field['field_type']){
					case 'text':
						$text_field = new Lib\CustomFieldText($custom_field['title'], $custom_field['default_value'], $custom_field['unique_id']);
						$text_field->render();
						break;
					case 'textarea':
						$text_field = new Lib\CustomFieldTextArea($custom_field['title'], $custom_field['default_value'], $custom_field['unique_id']);
						$text_field->render();
						break;
					case 'checkbox':
						$text_field = new Lib\CustomFieldCheckBox($custom_field['title'], $custom_field['unique_id']);
						$text_field->render();
						break;
					case 'select':
						$select_field = new Lib\CustomFieldSelect($custom_field['title'], '',$custom_field['option_labels'],$custom_field['option_values'], $custom_field['unique_id']);
						$select_field->render();
						break;
				}
			} ?>
		<?php } ?>
		<tr class="form-field term-description-wrap">
			<td style="vertical-align: top">
				<h2>
					<?php esc_html_e('Amenities' , 'qode-listing'); ?>
				</h2>
			</td>
            <td class="qode-custom-field-wrapper-outer">

				<div class="qode-taxonomy-amenities-holder">
					<?php
					if(is_array($amenities_array) && count($amenities_array)){
						foreach($amenities_array as $amenity_array){
							$amenity_field = new Lib\CustomAmenityCreator($amenity_array);
							$amenity_field->render();
						}
					}
					?>
				</div>
				<?php
				//generate html for amenities creation
				do_action('qode_listing_action_add_amenity_trigger');
				?>
			</td>
		</tr>

	<?php }
	add_action( 'job_listing_type_edit_form_fields', 'qode_listing_type_edit_meta_fields', 10, 2 );

}

if ( ! function_exists( 'qode_listing_type_update_meta_fields' ) ) {
	/**
	 *
	 * @param $term_id
	 * @param $taxonomy_id
	 */
	function qode_listing_type_update_meta_fields( $term_id, $taxonomy_id ) {



		if ( isset( $_POST ) ) {

			$custom_fields_array = array();
			if(isset($_POST['qode_custom_field_taxonomy_type'])){
				$custom_fields_array = $_POST['qode_custom_field_taxonomy_type'];
			}

			$custom_fields_save_array = array();
			$save_custom_fields = false;
			$amenity_fields_array = array();
			if(isset($_POST['qode_ls_taxonomy_amenity_list'])){
				$amenity_object_array = $_POST['qode_ls_taxonomy_amenity_list'];
				//split amenity field array into new arrays with 8 elements, because each amenity have 8 fields
				//now,we get amenity arrays(amenity name, unique id, amenity icon pack and amenity icon fields for each icon pack)
				//first element in each array will be name, second will be unique id, third will be icon_pack, and the rest are icon fields
				$count_fields = count(bridge_qode_icon_collections()->getIconCollections()) + 3;
				$amenity_fields_array = array_chunk($amenity_object_array, $count_fields, false);
			}

			$amenity_save_array = array();
			$save_amenity_fields = false;

			if(is_array($custom_fields_array) && count($custom_fields_array)){
                $save_custom_fields = true;

                foreach ($custom_fields_array as $key => $value){

                    $trimed_value = explode('_', $value);
                    $type = $trimed_value[0];
                    $field_id = $trimed_value[1];

                    //save field in database if is set field title
                    if(isset($_POST['qode_custom_field_title'][$field_id]) && $_POST['qode_custom_field_title'][$field_id] !== ''){

                        $custom_fields_save_array[$key]['meta_key'] = 'qode-ls-type-' . $term_id. '-' . $field_id;

						$custom_fields_save_array[$key]['title'] = $_POST['qode_custom_field_title'][$field_id];
                        $custom_fields_save_array[$key]['field_type'] = $type;
                        $custom_fields_save_array[$key]['unique_id'] = $field_id;

                        switch($type){
                            case 'text':
                                //for text field empty default value can be saved
                                $custom_fields_save_array[$key]['default_value'] = '';
                                if(isset($_POST['qode_custom_field_default_value']) && isset($_POST['qode_custom_field_default_value'][$field_id])){
                                    $custom_fields_save_array[$key]['default_value'] = $_POST['qode_custom_field_default_value'][$field_id];
                                }
                                break;
                            case 'textarea':
                                //for text field empty default value can be saved
                                $custom_fields_save_array[$key]['default_value'] = '';
                                if(isset($_POST['qode_custom_field_default_value']) && isset($_POST['qode_custom_field_default_value'][$field_id])){
                                    $custom_fields_save_array[$key]['default_value'] = $_POST['qode_custom_field_default_value'][$field_id];
                                }
                                break;
                            case 'checkbox':
                                //for checkbox we don't save any default value
                                $custom_fields_save_array[$key]['default_value'] = $_POST['qode_custom_field_title'][$field_id];
                                break;
                            case 'select':
                                $custom_fields_save_array[$key]['option_values'] = array();
                                $custom_fields_save_array[$key]['option_labels'] = array();
                                if(isset($_POST['qode_repeater_option_label'])){

                                    $option_label_array  = array();
                                    if(isset($_POST['qode_repeater_option_label'][$field_id])){
                                        $option_label_array = $_POST['qode_repeater_option_label'][$field_id];
                                    }

                                    //for select field option label and value mustn't be empty values
                                    if(count($option_label_array)){

                                        for($i=0; $i<count($option_label_array); $i++){

                                            if($option_label_array[$i] !== ''){
                                                $custom_fields_save_array[$key]['option_values'][$i] = sanitize_title($option_label_array[$i]);
                                                $custom_fields_save_array[$key]['option_labels'][$i] = $option_label_array[$i];
                                            }

                                        }

                                    }
                                }
                                break;
                        }
                    }

                }
            }

			if($save_custom_fields){
				update_term_meta($term_id, 'listing_type_custom_fields', $custom_fields_save_array);
			}else{
				delete_term_meta($term_id, 'listing_type_custom_fields');
			}

			//save amenities if are set for current Listing Type
			if(isset($amenity_fields_array)){
				if(is_array($amenity_fields_array) && count($amenity_fields_array)){
					$save_amenity_fields = true;

					foreach($amenity_fields_array as $amenity){
					   //first element in each array will be array with name, second will be array with icon_pack, and the rest are arrays with icon fields
						if($amenity[0]['name'] !== ''){

							$name = $amenity[0]['name'];
							$unique_id = $amenity[1]['unique_id'];
							$icon_pack  = $amenity[2]['icon_pack'];

							$param = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($icon_pack);
							$icon = '';

							//we need to go through all icon fields and to get icon related to icon_pack
							for($i=3; $i < count($amenity); $i++){
								if(array_key_exists($param, $amenity[$i])){
									$icon = $amenity[$i][$param];
								}
							}

							$amenity_save_array[$unique_id]['name'] = esc_attr($name);
							$amenity_save_array[$unique_id]['unique_id'] = esc_attr($unique_id);
							$amenity_save_array[$unique_id]['icon_pack'] = esc_attr($icon_pack);
							$amenity_save_array[$unique_id]['icon'] = esc_attr($icon);
						}
					}
				}
			}
			if($save_amenity_fields){
				update_term_meta($term_id, 'listing_type_amenities', $amenity_save_array);
			}
			else{
				delete_term_meta($term_id, 'listing_type_amenities');
			}
		}

	}
	add_action( 'edited_job_listing_type', 'qode_listing_type_update_meta_fields', 10, 2 );
}

//create custom fields for job listing regions
if ( ! function_exists( 'qode_listing_region_add_meta_fields' ) ) {
	/**
	 * Add field to add new listing location form
	 */
	function qode_listing_region_add_meta_fields() { ?>

		<div class="form-field term-featured-image-wrap">

			<label for="term-featured-image">
				<?php esc_html_e( 'Featured Image', 'qode-listing' ); ?>
			</label>

			<div class="qode-media-uploader">

				<div class="qode-media-image-holder">
					<img src="" alt="" class="qode-media-image img-thumbnail"/>
				</div>

				<a class="qode-media-upload-btn btn btn-sm btn-primary"
				   href="javascript:void(0)"
				   data-frame-title="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>"
				   data-frame-button-text="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>">
					<?php esc_html_e( 'Upload', 'qode-listing' ); ?>
				</a>

				<a style="display: none;" href="javascript: void(0)" class="qode-media-remove-btn btn btn-default btn-sm">
					<?php esc_html_e( 'Remove', 'qode-listing' ); ?>
				</a>

				<div style="display: none" class="qode-media-meta-fields">
					<input type="hidden" name="featured_image" class="qode-media-upload-url"/>
				</div>

			</div>
		</div>




	<?php }
	add_action( 'job_listing_region_add_form_fields', 'qode_listing_region_add_meta_fields', 10, 2 );

}

if ( ! function_exists( 'qode_listing_region_save_meta_fields' ) ) {
	/**
	 * Save listing location taxonomy meta field
	 *
	 * @param $term_id
	 * @param $taxonomy_id
	 */
	function qode_listing_region_save_meta_fields( $term_id, $taxonomy_id ) {
		if ( isset( $_POST ) ) {

			if ( isset($_POST['featured_image'])) {

				$image = esc_url( $_POST['featured_image'] );
				add_term_meta( $term_id, 'featured_image', $image, true );
			}
		}
	}
	add_action( 'create_job_listing_region', 'qode_listing_region_save_meta_fields', 10, 2 );

}

if ( ! function_exists( 'qode_listing_region_edit_meta_fields' ) ) {
	/**
	 * Edit Listing location taxonomy featured image
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	function qode_listing_region_edit_meta_fields( $term, $taxonomy ) {

		$featured_image = get_term_meta( $term->term_id, 'featured_image', true );
		?>

		<tr class="form-field term-featured-image-wrap">
			<th scope="row">
				<label for="term-featured-image">
					<?php esc_html_e( 'Featured Image', 'qode-listing' ); ?>
				</label>
			</th>
			<td>
				<div class="qode-media-uploader">
					<div class="qode-media-image-holder">
						<img src="<?php	if ( isset( $featured_image ) ) { echo esc_url($featured_image); } ?>" alt="" class="qode-media-image img-thumbnail" style="width: 150px"/>
					</div>
					<a class="qode-media-upload-btn btn btn-sm btn-primary"
					   href="javascript:void(0)"
					   data-frame-title="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>"
					   data-frame-button-text="<?php esc_html_e( 'Select Image', 'qode-listing' ); ?>">

						<?php esc_html_e( 'Upload', 'qode-listing' ); ?>

					</a>

					<a style="display: none;" href="javascript: void(0)" class="qode-media-remove-btn btn btn-default btn-sm">
						<?php esc_html_e( 'Remove', 'qode-listing' ); ?>
					</a>

					<div style="display: none" class="qode-media-meta-fields">
						<input type="hidden" name="featured_image" value="<?php if ( isset( $featured_image ) ) { echo esc_url($featured_image); } ?>" class="qode-media-upload-url"/>
					</div>
				</div>
			</td>
		</tr>

	<?php }
	add_action( 'job_listing_region_edit_form_fields', 'qode_listing_region_edit_meta_fields', 11, 2 );

}

if ( ! function_exists( 'qode_listing_region_update_meta_fields' ) ) {
	/**
	 * Update listing location taxonomy meta field
	 *
	 * @param $term_id
	 * @param $taxonomy_id
	 */
	function qode_listing_region_update_meta_fields( $term_id, $taxonomy_id ) {

		if ( isset( $_POST ) ) {

			if(isset($_POST['gallery_size'])){
				update_term_meta($term_id, 'gallery_size', $_POST['gallery_size']);
			}
			if(isset($_POST['gallery_type'])){
				update_term_meta($term_id, 'gallery_type', $_POST['gallery_type']);
			}
			if ( isset($_POST['featured_image'])) {

				$image = esc_url( $_POST['featured_image'] );
				update_term_meta( $term_id, 'featured_image', $image );
			}
		}

	}
	add_action( 'edited_job_listing_region', 'qode_listing_region_update_meta_fields', 10, 2 );

}