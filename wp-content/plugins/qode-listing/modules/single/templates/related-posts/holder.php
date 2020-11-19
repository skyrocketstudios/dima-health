<?php
use QodeListing\Lib\Core;
if($query){
	if($query->have_posts()){
		$extra_class = '';
		
        $data_params = array(
            'data-number-of-items' => '4',
            'data-enable-navigation' => 'no',
            'data-enable-pagination' => 'yes',
            'data-enable-loop' => 'yes',
            'data-slider-animate-in' => 'fadeIn',
            'data-slider-animate-out' => 'fadeOut',
            'data-slider-speed-animation' => '600',
            'data-slider-speed' => '5000',
            'data-enable-autoplay' => 'yes',
            'data-slider-margin' => '36'
        );

        $slider_class = '';
        if($query->found_posts >= 4){
	        $slider_class = 'qode-owl-slider';
        }
        else{
	        $slider_class = 'qode-no-slider';
        }
		?>

		<div class="qode-ls-single-related-posts-holder">
            <?php
                echo bridge_qode_icon_collections()->getIconHTML( 'dripicons-message', 'dripicons' );
            ?>

			<h5 class="qode-ls-related-post-title">
				<?php esc_html_e('Related listings','qode-listing'); ?>
			</h5>

			<div class="qode-related-post-holder qode-ls-related-normal-space qode-ls-related-four-columns clearfix">
				<div class="qode-ls-related-inner <?php echo esc_attr($slider_class);?>" <?php echo bridge_qode_get_inline_attrs($data_params); ?>>
					<?php
						while($query->have_posts()){
							$query->the_post();

							$article_obj = new Core\ListingArticle(get_the_ID());

							$related_params  = array(
								'type_html' => $article_obj->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
								'cat_html' => $article_obj->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
								'rating_html' => $article_obj->getListingAverageRating(),
								'address_html' => $article_obj->getAddressIconHtml(),
								'listing_author' => get_the_author(),
								'price_html'   => $article_obj->getActualPriceHtml(),
								'article_obj' => $article_obj
							);

							qode_listing_single_template_part('related-posts/item', '', $related_params);
						}
						wp_reset_postdata();
					?>
				</div>
			</div>
		</div>

	<?php }
	else{
		qode_listing_single_template_part('related-posts/no-post-found');
	}
}
else{
	qode_listing_single_template_part('related-posts/no-post-found');
}