<?php
$html = $article_obj->getListingAverageRating();
$number = $article_obj->getListingAverageRatingNumber();
if($html !== ''){ ?>
	<div itemprop="ratingStars" class="qode-ls-header-info rating-stars  entry-rating-stars published updated">
		<?php
			comments_number( __('No Review','qode'), '1'.__('Review','qode'), '% '.__('Reviews','qode'));
			echo wp_kses_post($html);
		?>
	</div>
<?php }