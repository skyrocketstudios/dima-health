<?php
	$this_object = qode_listing_regions_class_instance();
?>
<article class="qode-ls-item qode-ls-gallery-item <?php echo esc_attr($tax['classes']); ?>" >

    <div class="qode-ls-item-inner">

        <?php if($tax['link']){ ?>
	        <a href="<?php echo esc_url($tax['link']); ?>" class="qode-ls-gallery-item-overlay"></a>
        <?php } ?>
		<?php if($tax['image_src']){ ?>
		    <div class="qode-ls-gallery-item-image">
				<img src="<?php echo $tax['image_src']; ?>" alt="" />
		    </div>
		<?php } ?>
        <div class="qode-ls-gallery-item-text-holder">
	        <div class="qode-ls-gallery-item-text">
		        <div class="qode-ls-gallery-item-text-inner">
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

    </div>

</article>