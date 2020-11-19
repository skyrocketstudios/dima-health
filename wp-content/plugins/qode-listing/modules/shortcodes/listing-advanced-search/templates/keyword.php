<?php
$this_object = qode_listing_adv_search_class_instance();

$keyword_flag = $this_object->getBasicParamByKey('keyword_search') === 'yes' ? true : false ;

if($keyword_flag){ ?>

    <div class="qode-ls-adv-search-keyword-holder">

        <div class="qode-ls-adv-search-keyword-holder-inner clearfix">
			<div class="qode-ls-adv-search-keyword-field">
				<input type="text" class="qode-ls-adv-search-keyword" name="qode-ls-adv-search-keyword" placeholder="<?php esc_html_e('Type your search here...', 'qode-listing'); ?>">
			</div>
            <div class="qode-ls-adv-search-submit-button">
	            <button type="submit" class="qode-btn-custom-hover-bg qode-ls-adv-search-keyword-button">
		            <span class="qode-btn-text"><?php esc_html_e('Find Listings', 'qode-listing'); ?></span>
		            <span class="qode-btn-icon"><?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-arrow-thin-right' , 'dripicons' );?></span>
	            </button>
            </div>
        </div>
    </div>

<?php }