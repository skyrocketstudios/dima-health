<div class="qode-ls-single-header">
	<div class="qode-ls-single-section-holder top">
		<div class="qode-ls-single-section grid_section clearfix">
		<div class="section_inner clearfix">
			<div class="qode-ls-single-section-inner  left">

				<div class="qode-ls-single-part-holder left">
					<?php
						qode_listing_single_template_part('parts/image');
					?>
				</div>

				<div class="qode-ls-single-part-holder right">
					<div class="qode-ls-single-part-inner top">
						<?php qode_listing_single_template_part('parts/title'); ?>
					</div>

					<div class="qode-ls-single-part-inner bottom">
						<?php
							qode_listing_single_template_part('parts/info/date');
							qode_listing_single_template_part('parts/info/rating-stars', '', $params);
							qode_listing_single_template_part('parts/info/like');
							qode_listing_single_template_part('parts/info/share');
						?>
					</div>
				</div>

			</div>

			<div class="qode-ls-single-section-inner  right">
				<div class="qode-li-single-section-button-holder">
					<?php
						echo bridge_core_get_button_html(array(
							'text' => esc_html__('Contact this business', 'qode-listing'),
							'custom_class' => 'qode-ls-single-contact-listing',
							'type' => 'solid',
							'html_type' => 'button'
						));
					?>
				</div>
			</div>
		</div>
		</div>
	</div>

	<div class="qode-ls-single-section-holder bottom">
		<div class="qode-ls-single-section grid_section clearfix">
			<div class="section_inner clearfix">
				<div class="qode-ls-single-section-inner left">
					<?php qode_listing_single_template_part('parts/amenities'); ?>
				</div>
				<div class="qode-ls-single-section-inner right">
					<?php qode_listing_single_template_part('parts/price', '', $params); ?>
				</div>
			</div>
		</div>
	</div>
</div>