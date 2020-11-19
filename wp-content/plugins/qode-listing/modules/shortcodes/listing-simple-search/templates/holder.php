<?php
$this_object = qode_listing_simple_search_class_instance();
$keyword_text = $this_object->getBasicParamByKey('listing_search_keyword_text');
$button_text = $this_object->getBasicParamByKey('listing_search_button_text');

?>
<form method="get" action="<?php echo esc_url(get_post_type_archive_link( 'job_listing' )); ?>">
	<div class="qode-ls-simple-search-holder clearfix">
        

        <div class="qode-ls-simple-search-holder-part keyword">
            <input type="text" class="qode-ls-simple-search-keyword" name="qode-ls-simple-search-keyword" placeholder="<?php echo esc_attr($keyword_text); ?>">
		</div>
		<div class="qode-ls-simple-search-holder-part submit">
			<button type="submit" class="qode-ls-simple-search-button"><span class="qode-ls-simple-search-button-text"><?php echo esc_attr($button_text); ?></span><span class="qode-ls-simple-search-button-icon"><span class="icon dripicons-arrow-thin-right"></span></span></button>
		</div>

	</div>
</form>