<?php if(has_post_thumbnail()) {
	$image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
	$image_style = 'background-image: url('.esc_url($image_url).')';
	?>
	<div class="qode-ls-item-image" <?php echo bridge_qode_get_inline_style($image_style); ?>>
	</div>
<?php }