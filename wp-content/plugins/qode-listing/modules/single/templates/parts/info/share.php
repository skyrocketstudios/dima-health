<?php
$share_type = isset($share_type) ? $share_type : 'dropdown';
$global_share_option = bridge_qode_options()->getOptionValue('enable_social_share');
$listing_share_option = bridge_qode_options()->getOptionValue('enable_social_share_on_job_listing');

if($global_share_option === 'yes' && $listing_share_option === 'yes') { ?>
	<div class="qode-ls-header-info qode-ls-social-share">
		<?php echo bridge_core_get_social_share_html(array('type' => $share_type)) ?>
	</div>
<?php }