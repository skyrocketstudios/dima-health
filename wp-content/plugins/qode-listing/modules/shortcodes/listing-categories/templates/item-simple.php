<?php
	$this_object = qode_listing_categories_class_instance();
?>
<article class="qode-ls-item qode-ls-gallery-item <?php echo esc_attr($tax['classes']); ?>" >

    <div class="qode-ls-item-inner" >
	
        <div class="qode-ls-gallery-item-text">
	        <div class="qode-ls-gallery-item-text-inner">

	            <h4 class="qode-gallery-item-title">
	                <?php echo esc_attr($tax['name']) ?>
	            </h4>

	            <?php if($tax['desc'] !== ''){ ?>
	                <div class="qode-ls-gallery-item-desc">
	                    <?php print $tax['desc']; ?>
	                </div>
	            <?php } ?>

	            <div class="qode-ls-button-holder">
	                <?php
	                    echo bridge_core_get_button_html($button_params);
	                ?>
	            </div>

	        </div>
        </div>

    </div>

</article>