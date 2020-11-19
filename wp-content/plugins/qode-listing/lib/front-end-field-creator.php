<?php
namespace QodeListing\Lib\Front;
class ListingTypeFieldCreator{

	private $id;
	private $post_id;
	private $categories;
	private $custom_fields;
	private $amenities;

	public function __construct($type_id, $post_id = '') {

		$this->id = $type_id;
		$this->post_id = $post_id;
		$this->categories = $this->getTypeCategories();
		$this->custom_fields = $this->getTypeCustomFields();
		$this->amenities = $this->getTypeAmenities();

	}

	private function getTypeCategories(){
		return qode_listing_get_listing_type_categories($this->id);
	}

	private function getTypeCustomFields(){
		return qode_listing_get_listing_type_custom_fields($this->id);
	}

	private function getTypeAmenities(){
		return qode_listing_get_listing_type_amenities($this->id);
	}

	private function getTypeTitle(){
		$type = qode_listing_get_listing_type_by_id($this->id);
		return esc_html__('Listing Type', 'qode-listing').' "'.esc_attr($type->name).'"';
	}

	private function contentFlag(){
		$flag  = false;
		if((is_array($this->categories) && count($this->categories)) || (is_array($this->custom_fields) && count($this->custom_fields)) || is_array($this->amenities) && count($this->amenities)){
			$flag  = true;
		}
		return $flag;
	}

	public function renderListingFormFields(){?>

		<div id="<?php echo esc_attr($this->id) ?>" class="qode-ls-type-field-wrapper" data-ls-type-id="qode-ls-type-field-wrapper-<?php echo esc_attr($this->id) ?>">
			<?php if($this->contentFlag()){ ?>
				<h3>
					<?php echo esc_attr($this->getTypeTitle()); ?>
				</h3>
			<?php }
				$this->renderCategoryField();
				$this->renderCustomFields();
				$this->renderAmenities();
			?>
		</div>

	<?php }

	public function getAdvSearchHtml(){
		$html = '';
		$html .= $this->renderCategoryField('adv_search_html');
		$html .= $this->renderCustomFields('adv_search_html');
		$html .= $this->renderAmenities('adv_search_html');
		$html .= $this->renderSubmitButton();
		return $html;
	}

	public function getArchiveSearchHtml(){
		return $this->renderAmenities('archive_search_html');
	}

	public function getSingleListingCategoryField(){
		return $this->renderCategoryField('single');
	}
	public function getSingleListingCustomFields(){
		return $this->renderCustomFields('single');
	}
	public function getSingleListingAmenities(){
		return $this->renderAmenities('single');
	}

	private function renderCategoryField($html_type = ''){

		if(is_array($this->categories) && count($this->categories)){ ?>

			<h5 class="qode-listing-field-holder-title">
				<?php esc_html_e('Categories', 'qode-listing') ; ?>
			</h5>

			<?php
			$saved_value = '';
			if($this->post_id !== ''){
				$saved_value = get_post_meta($this->post_id, 'qode_listing_type_categories', true);
			}
			new FrontFieldCheckBoxGroup('job_type_categories', '', $this->categories, $html_type, $saved_value);
		}

	}

	private function renderCustomFields($html_type = ''){

		if(is_array($this->custom_fields) && count($this->custom_fields)){ ?>
			<h5 class="qode-listing-field-holder-title">
				<?php esc_html_e('Custom Fields', 'qode-listing') ; ?>
			</h5>
			<?php foreach($this->custom_fields as $custom_field){
				$options = array();
				if($custom_field['field_type'] === 'select'){
					$options = qode_listing_get_listing_type_options_array($custom_field);
				}
				$saved_value = '';
				if($this->post_id !== ''){
					$saved_value = get_post_meta($this->post_id, $custom_field['meta_key'], true);
				}
				switch($custom_field['field_type']){

					case 'text' :
						new FrontFieldText($custom_field['meta_key'], $custom_field['title'],$html_type, $saved_value);
						break;
					case 'textarea' :
						new FrontFieldTextArea($custom_field['meta_key'], $custom_field['title'],$html_type, $saved_value);
						break;
					case 'select' :
						new FrontFieldSelect($custom_field['meta_key'], $custom_field['title'], $options,$html_type, $saved_value);
						break;
					case 'checkbox' :
						new FrontFieldCheckBox($custom_field['meta_key'], $custom_field['title'], $html_type, false, $saved_value);
						break;

				}
			}
		}
	}

	private function renderAmenities($html_type = ''){

		if(is_array($this->amenities) && count($this->amenities)){
			$counter = 0;
			//each column should have 4 items
			$column_number = ceil(count($this->amenities)/4);

			if($html_type !=='single'){ ?>

                <h5 class="qode-listing-field-holder-title">
					<?php esc_html_e('Filter by Amenities', 'qode-listing') ; ?>
				</h5>

				<div class="qode-listing-amenities-wrapper qode-<?php echo esc_attr($column_number)?>-columns clearfix">

					<?php foreach($this->amenities as $key => $amenity){
						$counter++;
						//for each 4n+1 item open inner div
						if($counter % 4 === 1){?>
							<div class="qode-listing-amenities-wrapper-inner">
						<?php }
						$amenity_field_name = qode_listing_get_listing_type_amenity_field_name_refactored($this->id, $key);

						$saved_value = '';
						if($this->post_id !== ''){
							$saved_value = get_post_meta($this->post_id, $amenity_field_name, true);
						}
						new FrontFieldCheckBox($amenity_field_name, $amenity['name'], $html_type, true, $saved_value);

						//for each 4n item close inner div
						if($counter % 4 === 0 || $counter === count($this->amenities)){?>
							</div>
						<?php }
					}?>

				</div>

			<?php } else{
				foreach($this->amenities as $key => $amenity){
					$counter++;
					//for each 4n+1 item open inner div
					$amenity_field_name = qode_listing_get_listing_type_amenity_field_name_refactored($this->id, $key);

					$saved_value = '';
					if($this->post_id !== ''){
						$saved_value = get_post_meta($this->post_id, $amenity_field_name, true);
					}

					new FrontFieldCheckBox($amenity_field_name, $amenity['name'], $html_type, true, $saved_value, $amenity['icon_pack'], $amenity['icon']);

				}
			}
		}
	}

	private function renderSubmitButton(){

		echo bridge_core_get_button_html(array(
			'size'     => 'medium',
			'type'     => 'solid',
			'custom_class' => 'qode-adv-search-submit',
			'text'     => esc_html__('Filter Results', 'qode-listing'),
			'html_type' => 'button'
		));

	}

}

class FrontFieldCheckBoxGroup{

	private $name;
	private $label;
	private $options;
	private $html_type;
	private $value;

	public function __construct($name, $label, $options, $html_type, $value = '') {

		$this->name = $name;
		$this->label = $label;
		$this->options = $options;
		$this->html_type = $html_type;
		$this->value = $value;

		switch($html_type){
			case 'adv_search_html':
				$this->renderAdvSearchHtml();
				break;
			case 'single':
				$this->renderSingleListingHtml();
				break;
			default:
				$this->renderListingFieldHtml();
				break;
		}

	}

	private function renderListingFieldHtml(){

		if(!(is_array($this->options) && count($this->options))) {
			return;
		}
		?>

		<fieldset class="fieldset-<?php echo esc_attr($this->name); ?>">

			<div class="field">
				<input style="display: none" checked type="checkbox" value="" name="<?php echo esc_attr($this->name.'[]'); ?>">
				<?php
				foreach($this->options as $option_key => $option_label){
					$checked = is_array($this->value) && in_array($option_key, $this->value);
					$checked_attr = $checked ? 'checked' : ''; ?>

					<div class="checkbox-inline qode-ls-checkbox-field">

                        <input type="checkbox" <?php echo esc_attr($checked_attr); ?> value="<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($this->name.'[]'); ?>"/>

                        <label class="qode-checkbox-label" for="<?php echo  esc_attr($option_key); ?>">
                            <span class="qode-label-view"></span>
                            <span class="qode-label-text">
                                <?php echo esc_html($option_label); ?>
                            </span>
                        </label>

					</div>

				<?php } ?>
			</div>
		</fieldset>

	<?php }

	private function renderAdvSearchHtml(){	?>

		<div class="qode-ls-adv-search-field-wrapper">
			<?php foreach($this->options as $option_key => $option_label){?>

				<div class="qode-ls-adv-search-field checkbox">

					<input type="checkbox"  id="<?php echo esc_attr($option_key); ?>" class="qode-ls-adv-search-input" value="<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($this->name); ?>"/>
					<label for="<?php echo esc_attr($option_key); ?>">

                        <span class="qode-label-view"></span>
						<span class="qode-label-text">
							<?php echo esc_html($option_label); ?>
						</span>

					</label>
				</div>

			<?php }	?>
		</div>
	<?php }

	private function renderSingleListingHtml(){

	    if(is_array($this->value) && count($this->value)){?>

			<div  class="qode-listing-single-field">

                <label for="<?php echo esc_attr($this->name); ?>">
					<?php echo esc_attr($this->label); ?>
				</label>

				<div class="value">

					<?php foreach($this->value as $item_key => $item_value ) {
						if($item_value !== ''){
							$term = get_term_by('slug', $item_value, 'job_listing_category');
							?>
							<a href="<?php echo get_term_link($item_value, 'job_listing_category') ?>">
							<span>
								<?php echo esc_attr($term->name);  ?>
							</span>
							</a>
						<?php }
					} ?>
				</div>
			</div>
		<?php }
	}

}
class FrontFieldSelect{

	private $name;
	private $label;
	private $options;
	private $html_type;
	private $value;

	public function __construct($name, $label, $options, $html_type, $value = '') {

		$this->name = $name;
		$this->label = $label;
		$this->options = $options;
		$this->html_type = $html_type;
		$this->value = $value;

		switch($html_type){
			case 'adv_search_html':
				$this->renderAdvSearchHtml();
				break;
			case 'single':
				$this->renderSingleListingHtml();
				break;
			default:
				$this->renderListingFieldHtml();
				break;
		}

	}

	private function renderListingFieldHtml(){ ?>

		<fieldset class="fieldset-<?php echo esc_attr($this->name); ?>">

            <label for="<?php echo esc_attr($this->name); ?>">

                <?php echo esc_attr($this->label); ?>
				<small>
                    <?php esc_html_e('(optional)','qode-listing') ?>
                </small>

			</label>

			<div class="field">
				<select name="<?php echo esc_attr($this->name); ?>" id="<?php echo esc_attr($this->name); ?>">

                    <?php
                    foreach($this->options as $option_key => $option_value){
						$selected = '';
						if ($this->value == $option_key) {
							$selected = 'selected';
						}
						?>
						<option  <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($option_key); ?>">
							<?php echo esc_html($option_value); ?>
						</option>

					<?php } ?>

				</select>
			</div>
		</fieldset>
	<?php }

	private function renderAdvSearchHtml(){ ?>

		<div class="qode-ls-adv-search-field select">

            <label for="<?php echo esc_attr($this->name); ?>">
				<?php echo esc_attr($this->label); ?>
			</label>

			<select class="qode-ls-adv-search-input" name="<?php echo esc_attr($this->name); ?>">
				<option value=""></option>
				<?php foreach($this->options as $option_key => $option_value){ ?>
					<option value="<?php echo esc_attr($option_key); ?>">
						<?php echo esc_html($option_value); ?>
					</option>
				<?php } ?>

			</select>
		</div>

	<?php }

	private function renderSingleListingHtml(){

	    if($this->value !== ''){ ?>
			<div  class="qode-listing-single-field">
				<label for="<?php echo esc_attr($this->name); ?>">
					<?php echo esc_attr($this->label); ?>
				</label>
				<span class="value">
					<?php echo esc_attr($this->value); ?>
				</span>
			</div>
		<?php }
	}
}

class FrontFieldText{

	private $name;
	private $label;
	private $html_type;
	private $value;

	public function __construct($name, $label, $html_type, $value = '') {

		$this->name = $name;
		$this->label = $label;
		$this->html_type = $html_type;
		$this->value = $value;

		switch($html_type){
			case 'adv_search_html':
				$this->renderAdvSearchHtml();
				break;
			case 'single':
				$this->renderSingleListingHtml();
				break;
			default:
				$this->renderListingFieldHtml();
				break;
		}
	}

	private function renderListingFieldHtml(){ ?>

		<fieldset class="fieldset-<?php echo esc_attr($this->name); ?>">

			<label for="<?php echo esc_attr($this->name); ?>">

                <?php echo esc_attr($this->label); ?>
				<small>
                    <?php esc_html_e('(optional)','qode-listing') ?>
                </small>

			</label>

			<div class="field">
				<input type="text"  name="<?php echo esc_attr($this->name); ?>" value="<?php echo esc_attr(htmlspecialchars($this->value)); ?>" />
			</div>

		</fieldset>

	<?php }

	private function renderAdvSearchHtml(){ ?>

		<div class="qode-ls-adv-search-field">

			<label for="<?php echo esc_attr($this->name); ?>">
				<?php echo esc_attr($this->label); ?>
			</label>

			<input type="text" name="<?php echo esc_attr($this->name); ?>" class="qode-ls-adv-search-input"/>

		</div>

	<?php }

	private function renderSingleListingHtml(){

	    if($this->value !== ''){ ?>

			<div  class="qode-listing-single-field text">

				<label for="<?php echo esc_attr($this->name); ?>">
					<?php echo esc_attr($this->label); ?>
				</label>

				<span class="value">
					<?php echo esc_attr($this->value); ?>
				</span>

			</div>
		<?php }
	}
}
class FrontFieldTextArea{

	private $name;
	private $label;
	private $html_type;
	private $value;

	public function __construct($name, $label, $html_type, $value = '') {

		$this->name = $name;
		$this->label = $label;
		$this->html_type = $html_type;
		$this->value = $value;

		switch($html_type){
			case 'adv_search_html':
				$this->renderAdvSearchHtml();
				break;
			case 'single':
				$this->renderSingleListingHtml();
				break;
			default:
				$this->renderListingFieldHtml();
				break;
		}

	}

	private function renderListingFieldHtml(){ ?>

        <fieldset class="fieldset-<?php echo esc_attr($this->name); ?>">

            <label for="<?php echo esc_attr($this->name); ?>">
				<?php echo esc_attr($this->label); ?>
				<small>
                    <?php esc_html_e('(optional)','qode-listing') ?>
                </small>
			</label>

			<div class="field">
				<textarea name="<?php echo esc_attr($this->name); ?>" rows="5"><?php echo esc_html(htmlspecialchars($this->value)); ?></textarea> 
			</div>
		</fieldset>

	<?php }

	private function renderAdvSearchHtml(){ ?>

		<div class="qode-ls-adv-search-field textarea">

			<label for="<?php echo esc_attr($this->name); ?>">
				<?php echo esc_attr($this->label); ?>
			</label>

			<textarea name="<?php echo esc_attr($this->name); ?>" rows="5" class="qode-ls-adv-search-input"></textarea>
		</div>
	<?php }

	private function renderSingleListingHtml(){

	    if($this->value !== ''){ ?>

			<div  class="qode-listing-single-field">

				<label for="<?php echo esc_attr($this->name); ?>">
					<?php echo esc_attr($this->label); ?>
				</label>

				<span class="value">
					<?php echo esc_attr($this->value); ?>
				</span>

			</div>

		<?php }
	}
}

class FrontFieldCheckBox{

	private $name;
	private $label;
	private $html_type;
	private $amenity_flag;
	private $value;
	private $icon_pack;
	private $icon;

	public function __construct($name, $label, $html_type, $amenity_flag = false, $value = '', $icon_pack = '' ,$icon = '') {

		$this->name = $name;
		$this->label = $label;
		$this->html_type = $html_type;
		$this->amenity_flag = $amenity_flag;
		$this->value = $value;
		$this->icon_pack = $icon_pack;
		$this->icon = $icon;

		switch($this->html_type){
			case 'adv_search_html':
				$this->renderAdvSearchHtml();
				break;
			case 'archive_search_html':
				$this->renderArchiveSearchHtml();
				break;
			case 'single':
				$this->renderSingleListingHtml();
				break;
			default:
				$this->renderListingFieldHtml();
				break;
		}

	}

	private function renderListingFieldHtml(){
		$checked = "";

		if ('1' == $this->value){
			$checked = "checked";
		}
		?>

		<fieldset class="fieldset-<?php echo esc_attr($this->name); ?>">

            <div class="field qode-ls-checkbox-field">
				<input type="checkbox" <?php echo esc_attr($checked); ?> name="<?php echo esc_attr($this->name); ?>"/>

				<label class="qode-checkbox-label" for="<?php echo esc_attr($this->name); ?>">
					<span class="qode-label-view"></span>
					<span class="qode-label-text">
						<?php echo esc_html($this->label); ?>
					</span>
				</label>
			</div>
		</fieldset>
	<?php }

	private function renderAdvSearchHtml(){
		$amenity_class = '';
		if($this->amenity_flag){
			$amenity_class = 'qode-amenity-field';
		}
		?>

		<div class="qode-ls-adv-search-field checkbox">
			<input type="checkbox" class="qode-ls-adv-search-input <?php echo esc_attr($amenity_class); ?>" name="<?php echo esc_attr($this->name); ?>"/>

			<label for="<?php echo esc_attr($this->name); ?>">
				<span class="qode-label-view"></span>
				<span class="qode-label-text">
					<?php echo esc_html($this->label); ?>
				</span>
			</label>
		</div>

	<?php }

	private function renderArchiveSearchHtml(){
		$amenity_class = '';
		if($this->amenity_flag){
			$amenity_class = 'qode-amenity-field';
		}
		?>

		<div class="qode-listing-type-amenity-field">
			<input type="checkbox" class="qode-listing-type-amenity-field <?php echo esc_attr($amenity_class); ?>" name="<?php echo esc_attr($this->name); ?>"/>

			<label for="<?php echo esc_attr($this->name); ?>">
				<span class="qode-label-view"></span>
				<span class="qode-label-text">
					<?php echo esc_html($this->label); ?>
				</span>
			</label>
		</div>

	<?php }

	private function renderSingleListingHtml(){
		if($this->value === '1'){ ?>

			<div  class="qode-listing-single-field">
				<div class="qode-listing-single-field-inner qode-ls-icon">
					<?php
						echo bridge_qode_icon_collections()->renderIconHTML( $this->icon, $this->icon_pack );
					?>
				</div>
				<div class="qode-listing-single-field-inner qode-ls-text">
					<?php echo esc_attr($this->label); ?>
				</div>
			</div>

		<?php }
	}
}