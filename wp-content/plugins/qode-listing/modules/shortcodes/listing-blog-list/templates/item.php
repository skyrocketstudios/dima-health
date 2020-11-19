<article class="qode-ls-blog-list-item">
	<div class="qode-ls-blog-list-item-inner">
		<?php if(has_post_thumbnail()) { ?>
			<div class="qode-ls-blog-list-image">
				<a href="<?php the_permalink() ?>" itemprop="url">
					<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
				</a>
			</div>
		<?php } ?>
		<div class="qode-ls-blog-list-text">
			<span class="qode-ls-blog-list-date entry-date published updated" itemprop="dateCreated" > <?php the_time(get_option('date_format')); ?></span>
			<span class="qode-ls-blog-list-category"><?php the_category(', '); ?></span>
			<<?php echo esc_attr($title_tag); ?> class="qode-ls-blog-list-title" itemprop="name" >
				<a href="<?php the_permalink() ?>" itemprop="url">
					<?php the_title(); ?>
				</a>
			</<?php echo esc_attr($title_tag); ?>>
			<?php if($text_length != '0') {
					$excerpt = ($text_length > 0) ? mb_substr(get_the_excerpt(), 0, intval($text_length)) : get_the_excerpt(); ?>
					<p itemprop="description" class="qode-ls-blog-list-excerpt"><?php echo wp_kses_post($excerpt); ?></p>
			<?php }	?>
			<a href="<?php the_permalink() ?>" itemprop="url" class="qode-ls-blog-list-read-more"><?php esc_html_e('Read More', 'qode-listing'); ?></a>
		</div>
	</div>
</article>