<?php
use QodeListing\Lib\Core;
$rating_obj = new Core\ListingRating(get_the_ID(), false, 'get_average_rating' ); ?>

<div class="qode-ratings-holder">
	<div class="qode-current-rate">

        <span>
			<?php esc_html_e('Current rate is: ') ?>
		</span>

		<span class="qode-rating-value">
			<?php echo esc_attr($rating_obj->getAverageRating()); ?>
		</span>

	</div>

	<div class="qode-title-holder qode-ratings-text-title">
		<h5 class="qode-title-line-head">
            <?php esc_html_e('Rate This Article:', 'qode-listing' ); ?>
        </h5>
	</div>

	<div class="qode-ratings-stars-holder">
		<div class="qode-ratings-stars-inner">
			<span id="qode-rating-1" ></span>
			<span id="qode-rating-2" ></span>
			<span id="qode-rating-3" ></span>
			<span id="qode-rating-4" ></span>
			<span id="qode-rating-5" ></span>
		</div>
	</div>

	<div class="qode-ratings-message-holder">
		<div class="qode-rating-message"></div>
	</div>

</div>