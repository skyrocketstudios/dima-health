<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
$this_object = qode_listing_slider_class_instance();
$query_results = $this_object->getQueryResults();
$holder_classes = $this_object->getBasicParamByKey('holder_classes');
$data_params = $this_object->getBasicParamByKey('data_params');
$html = '';
?>

<div class="qode-ls-slider-holder clearfix">

	<div class="qode-ls-slider-items-holder clearfix <?php echo esc_attr($holder_classes); ?>">
		<div class="qode-ls-slider-items-holder-inner qode-ls-slider-inner qode-owl-slider clearfix" <?php echo bridge_qode_get_inline_attrs($data_params); ?>>
			<?php
				if($query_results->have_posts()){
					while ( $query_results->have_posts() ) {
						$query_results->the_post();
						$article_obj = new Core\ListingArticle(get_the_ID());

						$params  = array(
							'type_html' => $article_obj->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
							'cat_html' => $article_obj->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
							'rating_html' => $article_obj->getListingAverageRating(),
                            'listing_author' => get_the_author(),
                            'price_html'  => $article_obj->getActualPriceHtml(),
							'article_obj' => $article_obj
						);

						$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-slider','',$params);
					}
				}
				else{
					$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-slider');
				}

				wp_reset_postdata();
				echo wp_kses_post($html);
			?>
		</div>
	</div>
</div>