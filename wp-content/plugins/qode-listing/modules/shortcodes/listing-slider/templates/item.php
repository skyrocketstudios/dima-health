<?php
	$this_object = qode_listing_slider_class_instance();
?>
<article class="qode-ls-item">

	<?php if(has_post_thumbnail()) { ?>

		<div class="qode-ls-item-image">

			<?php
			if($type_html !== ''){
				print wp_kses_post($type_html);
			}
			?>

			<a href="<?php echo get_the_permalink(); ?>">
				<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
			</a>
		</div>

	<?php } ?>

	<div class="qode-ls-item-inner">

		<div class="qode-ls-item-title">
			<h5 class="qode-listing-title">
				<a href="<?php echo get_the_permalink(); ?>">
					<?php echo get_the_title(); ?>
				</a>
			</h5>
		</div>
		<?php
		if($rating_html !== ''){ ?>
			<div class="qode-ls-item-content">
				<?php
					echo  wp_kses_post($rating_html);
                ?>
			</div>
		<?php }	?>

		<div class="qode-ls-item-footer">
			<?php
				if($price_html !== ''){
					echo wp_kses_post($price_html);
				}
			?>
			<div class="qode-ls-author-text" >
			    <span>
			        <?php esc_html_e('by', 'qode-listing'); ?>
			    </span>
			    <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>" >
			        <?php echo esc_attr($listing_author); ?>
			    </a>
			</div>
			<?php
				if(!empty($cat_html)){
					print wp_kses_post($cat_html);
				}
			?>
		</div>
	</div>

</article>