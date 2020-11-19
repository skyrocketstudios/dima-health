<?php
if(qode_listing_is_wp_job_manager_tags_installed()){

	$tags_html = $article_obj->getTaxHtml('job_listing_tag', 'qode-ls-tags-wrapper');

	if($tags_html !== ''){ ?>

		<div class="qode-ls-content-part-holder clearfix">

			<div class="qode-ls-content-part left">

				<h6 class="qode-ls-content-part-title">
					<?php esc_html_e('Property tags','qode-listing'); ?>
				</h6>

			</div>

			<div class="qode-ls-content-part right">
				<?php echo wp_kses_post($tags_html); ?>
			</div>

		</div>

	<?php }

}