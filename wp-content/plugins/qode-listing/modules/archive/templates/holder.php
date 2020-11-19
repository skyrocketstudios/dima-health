<?php
	use QodeListing\Lib\Core;
?>
<div class="qode-ls-archive-holder clearfix">

    <div class="qode-ls-archive-map-holder">
        <?php echo qode_listing_get_listing_multiple_map(); ?>
    </div>

	<div class="qode-ls-archive-items-wrapper">

		<?php
			qode_listing_get_archive_module_template_part('filter');
		?>

		<div class="qode-ls-archive-items qode-ls-archive-normal-space qode-ls-archive-three-columns clearfix">
			<div class="qode-ls-archive-items-inner clearfix">
				<?php if($query_results->have_posts()) {

					while($query_results->have_posts()) {
						$query_results->the_post();
						$article = new Core\ListingArticle(get_the_ID());
						$params = array(
							'type_html' => $article->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
							'rating_html'  => $article->getListingAverageRating(),
							'cat_html'      => $article->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
							'address_html' => $article->getAddressIconHtml(),
							'listing_author' => get_the_author(),
							'price_html'   => $article->getPriceHtml()
						);
						qode_listing_get_archive_module_template_part('single', '', $params);
					}
					wp_reset_postdata();
				}
				else{
					qode_listing_get_archive_module_template_part('post-not-found');
				}?>
			</div>
		</div>
		<?php
			qode_listing_get_archive_module_template_part('load-more-template');
		?>
	</div>
</div>