<article class="qode-ls-single-item" id="<?php echo get_the_ID();?>">
	<?php
        qode_listing_single_template_part('sections/header-top', '', $params);
        qode_listing_single_template_part('sections/header', '', $params);
        qode_listing_single_template_part('sections/content', '', $params);
        qode_listing_single_template_part('sections/footer', '', $params);
        qode_listing_single_template_part('sections/content-bottom', '', $params);
	?>
</article>
