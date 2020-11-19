<?php
$product_id = false;
$title = '';
$price = '';
$disc_price = '';
if(isset($package->product_id)){
	$product_id = $package->product_id;
}
if($product_id){
	$title = get_the_title($product_id);
	$disc_price = get_post_meta($product_id, '_price', true);
	$price = get_post_meta($product_id, '_regular_price', true);
}
if($package && $product_id){ ?>
	<li class="qode-user-package">

        <?php if($title !== '') { ?>
			<div class="qode-user-package-title qode-ls-package-part">
				<h4 class="qode-ls-package-text">
                    <?php
                        esc_html_e('Title','qode-listing');
                    ?>
                </h4>
                <p class="qode-ls-package-value">
					<?php echo esc_attr($title); ?>
				</p>
			</div>
		<?php }

		if(isset($package->package_duration)){ ?>
			<div class="qode-user-package-duration qode-ls-package-part">
                <h4 class="qode-ls-package-text">
                    <?php
                    esc_html_e('Duration','qode-listing');
                    ?>
                </h4>
                <p class="qode-ls-package-value">
					<?php echo esc_attr($package->package_duration);?>
				</p>
			</div>
		<?php }

		if(isset($package->package_count)){ ?>
			<div class="qode-user-package-count qode-ls-package-part">

                <h4 class="qode-ls-package-text">
                    <?php
                    esc_html_e('Count','qode-listing');
                    ?>
                </h4>
                <p class="qode-ls-package-value">
					<?php echo esc_attr($package->package_count); ?>
				</p>

			</div>
		<?php }

		if(isset($package->package_limit)){ ?>
			<div class="qode-user-package-limit qode-ls-package-part">

                <h4 class="qode-ls-package-text">
                    <?php
                    esc_html_e('Limit','qode-listing');
                    ?>
                </h4>

                <p class="qode-ls-package-value">
					<?php echo esc_attr($package->package_limit);?>
				</p>

			</div>
		<?php }

		if($price !== ''){ ?>
			<div class="qode-user-package-price qode-ls-package-part">

                <h4 class="qode-ls-package-text">
                    <?php
                        esc_html_e('Regular Price','qode-listing');
                    ?>
                </h4>

                <p class="qode-ls-package-value">
					<?php echo esc_attr($price);?>
				</p>

			</div>
		<?php }

		if($disc_price !== '' && ($price !== $disc_price)){ ?>
			<div class="qode-user-package-price qode-ls-package-part">

                <h4 class="qode-ls-package-text">
                    <?php
                        esc_html_e('Discount Price','qode-listing');
                    ?>
                </h4>

                <p class="qode-ls-package-value">
					<?php echo esc_attr($disc_price); ?>
				</p>

			</div>
		<?php } ?>

	</li>
<?php }