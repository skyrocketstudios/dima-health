<?php
	$this_object = qode_listing_categories_class_instance();
?>
<article class="qode-ls-item qode-ls-gallery-item <?php echo esc_attr($tax['classes']); ?>" >

    <div class="qode-ls-item-inner" <?php echo bridge_qode_get_inline_style($tax['image_style']) ?>>

        <?php if($tax['link']){ ?>
	        <a href="<?php echo esc_url($tax['link']); ?>" class="qode-ls-gallery-item-overlay"></a>
        <?php } ?>

        <div class="qode-ls-gallery-item-text">
	        <div class="qode-ls-gallery-item-text-inner">
				<?php if($tax['icon'] !== ''){ ?>
			        <div class="qode-ls-gallery-item-icon">
						<?php
							print $tax['icon'];
						?>
			        </div>
				<?php } ?>
	            <h3 class="qode-gallery-item-title">
	                <?php echo esc_attr($tax['name']) ?>
	            </h3>

	            <?php if($tax['desc'] !== ''){?>
	                <div class="qode-ls-gallery-item-desc">
	                    <?php echo wp_kses_post($tax['desc']); ?>
	                </div>
	            <?php } ?>

	        </div>
        </div>

    </div>

</article>