<div class="qode-ls-single-section qode-ls-single-content grid_section clearfix">
	<div class="section_inner clearfix">
		<div class="qode-ls-single-section-inner left">
			<?php
				qode_listing_single_template_part('parts/content');
				qode_listing_single_template_part('parts/tags', '', $params);
				qode_listing_single_template_part('parts/video', '', $params);
				qode_listing_single_template_part('parts/comments');
			?>
		</div>

		<div class="qode-ls-single-section-inner right">
			<?php
				qode_listing_single_template_part('parts/map', '', $params);
				qode_listing_single_template_part('parts/user-listings', '', $params);
			?>
		</div>
	</div>
</div>