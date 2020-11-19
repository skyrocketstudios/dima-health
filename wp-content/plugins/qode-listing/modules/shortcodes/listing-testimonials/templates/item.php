<?php
	$this_object = qode_listing_list_class_instance();
?>
<article class="qode-ls-testimonial">
	<div class="qode-ls-testimonial-inner">
		<div class="qode-ls-testimonial-left-part">
			<span class="icon_quotations qode-ls-testimonial-quote"></span>
			<?php if(has_post_thumbnail()) { ?>
				<div class="qode-ls-testimonial-image">
					<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
				</div>
			<?php } ?>
			<?php if($author != '') : ?>
				<h6 class="qode-ls-testimonial-author">
					<?php echo esc_html($author); ?>
				</h6>
			<?php endif; ?>
			<?php if($website != '') : ?>
				<div class="qode-ls-testimonial-website">
					<?php echo esc_html($website); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="qode-ls-testimonial-right-part">
			<h5 class="qode-ls-testimonial-title">
				<?php the_title(); ?>
			</h5>
			<?php if($text != '') : ?>
				<div class="qode-ls-testimonial-text">
					<?php echo esc_html($text); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article>