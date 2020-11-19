<?php
$this_object = qode_listing_main_search_class_instance();
$types_array = $this_object->getListingTypes();
$classes = $this_object->getBasicParamByKey('holder_classes');
$keyword = $this_object->getBasicParamByKey('listing_search_keyword') === 'yes' ? true : false;
$keyword_text = $this_object->getBasicParamByKey('listing_search_keyword_text');
$type = $this_object->getBasicParamByKey('listing_search_type') === 'yes' ? true : false;
$type_text = $this_object->getBasicParamByKey('listing_search_type_text');
$region_array = $this_object->getListingRegions();
$region = $this_object->getBasicParamByKey('listing_search_region') === 'yes' ? true : false;
$price = $this_object->getBasicParamByKey('listing_search_price') === 'yes' ? true : false;
$price_text = $this_object->getBasicParamByKey('listing_search_price_text');
$price_slider = $this_object->getBasicParamByKey('listing_search_price_view') === 'yes' ? true : false;
$button_text = $this_object->getBasicParamByKey('listing_search_button_text');

?>
<form method="get" action="<?php echo esc_url(get_post_type_archive_link( 'job_listing' )); ?>">
	<div class="qode-ls-main-search-holder clearfix <?php echo esc_attr($classes); ?>">
        
        <?php if ($keyword){ ?>

            <div class="qode-ls-main-search-holder-part keyword">

                <div class="qode-ls-search-icon">
                    <?php echo bridge_qode_icon_collections()->renderIconHTML( 'icon_search', 'font_elegant' ); ?>
                </div>
                <input type="text" class="qode-ls-main-search-keyword" name="qode-ls-main-search-keyword" placeholder="<?php echo esc_attr($keyword_text); ?>">

            </div>
        
        <?php }
        if ($type) { ?>

            <div class="qode-ls-main-search-holder-part type">

                <div class="qode-ls-search-icon">
                    <?php echo bridge_qode_icon_collections()->renderIconHTML( 'icon_briefcase', 'dripicons' ); ?>
                </div>

                <select name="qode-ls-main-search-listing-type" data-placeholder="<?php esc_html_e('All Types','qode-listing') ?>" data-allow-clear="true" data-minimum-results-for-search="5">

                    <option value="all">
                        <?php echo esc_attr($type_text);?>
                    </option>
                    <?php foreach($types_array as $type_key => $type_value){

                        if($type_key !== ''){?>
                            <option value="<?php echo esc_attr($type_key) ?>">
                                <?php echo esc_attr($type_value); ?>
                            </option>
                        <?php }

                    } ?>
                </select>
            </div>
        
        <?php } 

        if ($region) { ?>

        <div class="qode-ls-main-search-holder-part region">

            <div class="qode-ls-search-icon">
                <?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-map', 'dripicons' ); ?>
            </div>

            <select name="qode-ls-main-search-listing-region" data-placeholder="<?php esc_html_e('Choose a Location','qode-listing') ?>" data-allow-clear="true" data-minimum-results-for-search="5">

                <option value="all">
                    <?php esc_html_e('Choose a Location', 'qode-listing')?>
                </option>

                <?php foreach($region_array as $region_key => $region_value){

                    if($region_key !== ''){ ?>
                        <option value="<?php echo esc_attr($region_key) ?>">
                            <?php echo esc_attr($region_value); ?>
                        </option>
                    <?php }

                } ?>

            </select>

        </div>
        
        <?php }
        if ($price) { ?>

            <div class="qode-ls-main-search-holder-part price">

                <?php if($price_slider) { ?>
                <div class="qode-ls-slider-wrapper qode-listing-price-holder">

                    <div class="qode-ls-search-icon">
                        <?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-jewel', 'dripicons' ); ?>
                    </div>

                    <span class="qode-price-slider-text">
                        <?php echo esc_attr($price_text)?>
                    </span>

                    <div class="qode-price-slider-holder">
                        <span class="qode-price-slider-response">
                            <?php esc_html_e('$0', 'qode-listing'); ?>
                        </span>
                        <input class="qode-price-slider" type="range" min="0" max="100000" step="10" value="0" data-orientation="horizontal" data-rangeslider>
                        <input type="hidden" name="qode-ls-main-search-price-max" class="qode-price-slider-value" value="">
                    </div>
                </div>
            </div>

         <?php } else {?>

                <div class="qode-ls-search-icon">
                    <?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-jewel', 'dripicons' ); ?>
                </div>

                <input type="text" name="qode-ls-main-search-price-max" placeholder="<?php echo esc_attr($price_text)?>"/>
            </div>
        <?php } }?>

		<div class="qode-ls-main-search-holder-part submit">

			<?php echo bridge_core_get_button_html(array(
				'text' => $button_text,
				'html_type' => 'button',
			)); ?>

		</div>

	</div>
</form>