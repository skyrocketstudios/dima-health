<?php
if(get_the_content() !== ''){?>
	<div class="qode-ls-content-part-holder clearfix">

		<div class="qode-ls-content-part left">
			<h6 class="qode-ls-content-part-title">
				<?php esc_html_e('The property','qode-listing'); ?>
			</h6>
		</div>

		<div class="qode-ls-content-part right">
			<?php echo do_shortcode(get_the_content()); ?>
		</div>

	</div>
<?php }