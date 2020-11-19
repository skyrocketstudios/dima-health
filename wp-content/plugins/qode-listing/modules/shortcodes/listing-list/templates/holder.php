<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
$this_object = qode_listing_list_class_instance();
$query_results = $this_object->getQueryResults();
$holder_classes = $this_object->getBasicParamByKey('holder_classes');
$html = '';	?>

<div class="qode-ls-list-holder clearfix">

	<div class="qode-ls-list-items-holder clearfix <?php echo esc_attr($holder_classes); ?>">
		<div class="qode-ls-list-items-holder-inner qode-ls-list-inner clearfix">
			<?php
				if($query_results->have_posts()){
					while ( $query_results->have_posts() ) {
						$query_results->the_post();
						$article_obj = new Core\ListingArticle(get_the_ID());

						$params  = array(
							'type_html' => $article_obj->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
							'cat_html' => $article_obj->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
							'rating_html' => $article_obj->getListingAverageRating(),
							'address_html' => $article_obj->getAddressIconHtml(),
							'listing_author' => get_the_author(),
							'article_obj' => $article_obj,
                            'price_html'  => $article_obj->getActualPriceHtml()
						);

						$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-list','',$params);
					}
				}
				else{
					$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-list');
				}

				wp_reset_postdata();
				echo wp_kses_post($html);
			?>
		</div>
	</div>
</div>