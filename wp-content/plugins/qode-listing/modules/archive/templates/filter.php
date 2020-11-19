<?php
$types_obj = qode_listing_get_listing_types();
$types_array = $types_obj['key_value'];
$submit_button = array(
	'type' => 'solid',
	'size' => 'medium',
	'custom_class' => 'qode-qbutton-full-width qode-archive-submit-button',
	'text' => esc_html__('Filter Results', 'qode-listing'),
	'html_type' => 'button'
);

$selected_type  = '';
if(isset($_GET['qode-ls-main-search-listing-type'])){
	$selected_type = $_GET['qode-ls-main-search-listing-type'];
}elseif(is_tax( 'job_listing_type' )){
	$selected_type = get_queried_object_id();
}

$keyword  = '';
if(isset($_GET['qode-ls-main-search-keyword'])){
	$keyword = $_GET['qode-ls-main-search-keyword'];
}

?>

<div class="qode-listing-archive-filter-holder clearfix">

	<div class="qode-listing-archive-filter-item">

		<label for="qode-archive-keyword-search">
			<?php esc_html_e('Enter Keyword', 'qode-listing') ?>
		</label>

		<input type="text" name="qode-archive-keyword-search" class="qode-listing-search-input qode-archive-keyword-search" value="<?php echo esc_attr($keyword) ?>">
	</div>

	<div class="qode-listing-archive-filter-item">

		<label for="qode-archive-type-search">
			<?php esc_html_e('Listing Type', 'qode-listing') ?>
		</label>

		<select name="qode-archive-type-search" class="qode-listing-search-input qode-archive-type-search">
			<option value="all"><?php esc_html_e('All Types', 'qode-listing');?></option>
			<?php foreach($types_array as $type_key => $type_value){
				$selected = '';

				if($type_key == $selected_type){
					$selected = 'selected';
				}

				if($type_key !== ''){ ?>
					<option value="<?php echo esc_attr($type_key); ?>" <?php echo esc_attr($selected) ?>>
						<?php echo esc_attr($type_value); ?>
					</option>
				<?php } ?>

			<?php } ?>
		</select>
	</div>

	<div class="qode-listing-archive-filter-item qode-listing-places-search-holder">
		<label for="qode-archive-keyword-search">
			<?php esc_html_e('Enter Address', 'qode-listing') ?>
		</label>

		<div class="qode-listing-address-holder">

			<div class="qode-archive-current-location">
				<?php
					echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-pamphlet', 'dripicons');
				?>
			</div>

			<input type="text" id="qode-archive-places-search" class="qode-archive-places-search" name="qode-listing-places-search" placeholder="<?php esc_html_e('Enter Address', 'qode-listing'); ?>">
		</div>

	</div>

	<div class="qode-listing-archive-filter-item qode-full-width-item qode-listing-radius-field">

		<div class="qode-listing-places-dist-holder">

			<div class="qode-rangle-slider-response-holder">
				<span>
					<?php esc_html_e('Choose km radius: ', 'qode-listing'); ?>
				</span>
				<span class="qode-rangle-slider-response">0</span>
			</div>
			<input	class="qode-rangle-slider" type="range" min="0" max="100" step="1" value="0" data-orientation="horizontal" data-rangeslider>

			<div class="qode-listing-places-range qode-listing-places-min">
				<?php esc_html_e('0km', 'qode-listing') ?>
			</div>
			<div class="qode-listing-places-range qode-listing-places-max">
				<?php esc_html_e('100km', 'qode-listing') ?>
			</div>
		</div>
	</div>

    <!--	selected type amenities will be stored in this holder-->
	<div class="qode-listing-type-amenities-holder qode-listing-archive-filter-item qode-full-width-item"></div>

	<div class="qode-listing-archive-filter-item qode-full-width-item">
		<?php echo bridge_core_get_button_html($submit_button);?>
	</div>
</div>