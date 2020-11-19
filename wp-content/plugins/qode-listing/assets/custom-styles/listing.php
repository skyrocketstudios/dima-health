<?php

if(!function_exists('qode_listing_first_color_styles')){
	function qode_listing_first_color_styles(){

		$first_color = bridge_qode_options()->getOptionValue('first_color');
		if(!empty($first_color)) {

			$background_color_selector = array(
				'.widget.qode-login-register-widget .qode-logged-in-user .icon_profile',
				'.widget.qode-login-register-widget .qode-login-holder .icon_profile',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder #job-manager-job-dashboard .job-manager-jobs thead tr',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder #job-manager-job-dashboard .job-manager-pagination ul li span.current',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder #job-manager-job-dashboard .job-manager-pagination ul li a:hover',
				'.page-template-user-dashboard .qode-user-package-holder .qode-user-package .qode-ls-package-text',
				'.woocommerce-account table.shop_table.my_account_job_packages thead th',
				'.qode-map-marker-holder .qode-map-marker:hover .qode-map-marker-inner',
				'.qode-map-marker-holder.active .qode-map-marker .qode-map-marker-inner',
				'.qode-ls-enquiry-inner .qode-ls-enquiry-close:hover',
				'.qode-ls-adv-search-holder .qode-ls-adv-search-keyword-holder .qode-ls-adv-search-submit-button .qode-ls-adv-search-keyword-button .qode-btn-icon',
				'.qode-ls-main-search-holder .qode-ls-main-search-holder-part .qode-ls-slider-wrapper .qode-price-slider-response',
				'.qode-ls-simple-search-holder .qode-ls-simple-search-holder-part.submit .qode-ls-simple-search-button-icon',
			);
			$background_color_styles = array();

			$color_selector = array(
				'.qode-listing-archive-filter-item .qode-listing-type-amenity-field input[type=checkbox]+label .qode-label-view:after',
				'.qode-ls-adv-search-holder .qode-ls-adv-search-field input[type=checkbox]+label .qode-label-view:after',
				'.qode-ls-checkbox-field input[type=checkbox]+label .qode-label-view:after',
				'.ui-autocomplete.ui-widget-content li:hover',
				'.qode-ls-archive-items-inner .qode-ls-item .qode-listing-cat-wrapper>a',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_facebook_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_instagram_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_pinterest_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_skype_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_soundcloud_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_twitter_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_vimeo_url .field:before',
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder .job-manager-form .qode-ls-field-holder .fieldset-listing_youtube_url .field:before',
				'.qode-map-marker-holder .qode-info-window-inner>a:hover~.qode-info-window-details h5',
				'.qode-ratings-holder .qode-ratings-stars-holder .qode-ratings-stars-inner>span.qode-active-rating-star',
				'.qode-ratings-holder .qode-ratings-stars-holder .qode-ratings-stars-inner>span.qode-hover-rating-star',
				'.qode-ls-single-comments .qode-comments-title-holder i',
				'.qode-ls-single-comments .qode-comments .qode-review-rating .rating-inner',
				'.qode-ls-single-comments .qode-comment-form .qode-rating-form-title-holder .qode-comment-form-rating label',
				'.qode-comment-rating-box .qode-star-rating.active',
				'.qode-listing-single-holder article .qode-ls-user-listing-holder .qode-ls-user-listing-header .qode-ls-user-listing-link',
				'.qode-listing-single-holder article .qode-ls-single-related-posts-holder .qode-ls-related-item .qode-listing-cat-wrapper>a',
				'.qode-listing-single-holder article .qode-ls-single-related-posts-holder .qode-related-post-holder .qode-owl-slider .owl-nav .owl-next:hover span',
				'.qode-listing-single-holder article .qode-ls-single-related-posts-holder .qode-related-post-holder .qode-owl-slider .owl-nav .owl-prev:hover span',
				'.qode-ls-enquiry-inner .qode-ls-enquiry-form label:after',
				'.qode-listing-single-holder article .qode-ls-single-gallery-holder .owl-nav .owl-next:hover .qode-next-icon',
				'.qode-listing-single-holder article .qode-ls-single-gallery-holder .owl-nav .owl-next:hover .qode-prev-icon',
				'.qode-listing-single-holder article .qode-ls-single-gallery-holder .owl-nav .owl-prev:hover .qode-next-icon',
				'.qode-listing-single-holder article .qode-ls-single-gallery-holder .owl-nav .owl-prev:hover .qode-prev-icon',
				'.qode-listing-single-holder article .qode-ls-single-header .qode-ls-header-info .qode-like.liked i',
				'.qode-ls-adv-search-holder .qode-ls-adv-search-items-holder .qode-ls-item .qode-listing-cat-wrapper>a',
				'.qode-ls-blog-list article .qode-ls-blog-list-category a',
				'.qode-ls-list-holder .qode-ls-list-items-holder .qode-ls-item .qode-listing-cat-wrapper>a',
				'.qode-ls-slider-holder .qode-ls-slider-items-holder .qode-ls-item .qode-listing-cat-wrapper>a',

			);
			$color_styles = array();

			$border_color_selector = array(
				'.page-template-user-dashboard .qode-membership-dashboard-content-holder #job-manager-job-dashboard .job-manager-jobs thead tr',
				'.woocommerce-account table.shop_table.my_account_job_packages thead tr',
				'.woocommerce-account table.shop_table.my_account_job_packages thead th',
				'.qode-map-marker-holder .qode-map-marker:hover .qode-map-marker-inner',
				'.qode-map-marker-holder.active .qode-map-marker .qode-map-marker-inner',
				'.qode-listing-single-holder article .qode-ls-single-related-posts-holder .qode-related-post-holder .qode-owl-slider .owl-dots .owl-dot.active span',
				'.qode-ls-slider-holder .qode-ls-slider-items-holder .qode-owl-slider .owl-dots .owl-dot.active span',

			);
			$border_color_styles = array();

			$border_top_color_selector = array(
				'.qode-map-marker-holder .qode-map-marker:hover>:before,.qode-map-marker-holder.active .qode-map-marker>:before',
				'.qode-ls-main-search-holder .qode-ls-main-search-holder-part .qode-ls-slider-wrapper .qode-price-slider-response:after'

			);
			$border_top_color_styles = array();

			$path_selector = array(
				'.qode-cluster-marker svg path,.qode-cluster-marker:hover svg path,.qode-map-marker-holder .qode-map-marker .qode-map-marker-inner svg path'

			);
			$path_styles = array();

			$background_color_styles['background-color'] = $first_color;
			$color_styles['color'] = $first_color;
			$border_color_styles['border-color'] = $first_color;
			$border_top_color_styles['border-top-color'] = $first_color;
			$path_styles['fill'] = $first_color;


			echo bridge_qode_dynamic_css($background_color_selector, $background_color_styles);
			echo bridge_qode_dynamic_css($color_selector, $color_styles);
			echo bridge_qode_dynamic_css($border_color_selector, $border_color_styles);
			echo bridge_qode_dynamic_css($border_top_color_selector, $border_top_color_styles);
			echo bridge_qode_dynamic_css($path_selector, $path_selector);
		}
	}

	add_action('bridge_qode_action_style_dynamic', 'qode_listing_first_color_styles');
}