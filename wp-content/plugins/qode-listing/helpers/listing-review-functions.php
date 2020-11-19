<?php
use QodeListing\Lib\Core;


if ( ! function_exists( 'qode_listing_extend_rating_posts_types' ) ) {
	function qode_listing_extend_rating_posts_types($post_types) {
		$post_types[] = 'job_listing';

		return $post_types;
	}

	add_filter( 'bridge_core_filter_rating_post_types', 'qode_listing_extend_rating_posts_types' );
}

if(!function_exists('qode_listing_comment_additional_fields')) {

	function qode_listing_comment_additional_fields() {

		if (is_singular('job_listing')) {
			$html = '<div class="qode-rating-form-title-holder">'; //Form title begin
			$html .= '<div class="qode-rating-form-title">';
			$html .= '<h5>' . esc_html__('Write a Review','qode-listing') . '</h5>';
			$html .= '</div>';
			$html .= '<div class="qode-comment-form-rating">
						<label>' . esc_html__('Rate Here', 'qode-listing') . '<span class="required">*</span></label>
						<span class="qode-comment-rating-box">';
			for ($i = 1; $i <= 5; $i++) {
				$html .= '<span class="qode-star-rating" data-value="' . $i . '"></span>';
			}
			$html .= '<input type="hidden" name="qode_rating" id="qode-rating" value="3">';
			$html .= '</span></div>';
			$html .= '</div>'; //Form title end

			$html .= '<div class="qode-comment-input-title">';
			$html .= '<input id="title" name="qode_comment_title" class="qode-input-field" type="text" placeholder="' . esc_html__('Title of your Review', 'qode-listing') . '"/>';
			$html .= '</div>';

			print $html;
		}
	}

	add_action( 'comment_form_top', 'qode_listing_comment_additional_fields' );

}


if(!function_exists('qode_listing_get_current_post_comments')){

	function qode_listing_get_current_post_comments($post_id, $order_by = 'comment_date_gmt' , $order = 'desc'){

		$meta_key  = '';
		if($order_by === 'rating'){
			$order_by = 'meta_value';
			$meta_key  = 'qode_rating';
		}elseif($order_by === 'date'){
			$order_by = 'comment_date_gmt';
		};

		$comment_args = array(
			'post_id' => $post_id,
			'status' => 'approve',
			'orderby' => $order_by,
			'meta_key'  => $meta_key,
			'order' => $order
		);
		if ( is_user_logged_in() ) {
			$comment_args['include_unapproved'] = get_current_user_id();
		} else {
			$commenter = wp_get_current_commenter();
			if ( $commenter['comment_author_email'] ) {
				$comment_args['include_unapproved'] = $commenter['comment_author_email'];
			}
		}

		$comments  = get_comments($comment_args);
		return $comments;

	}
}

if ( ! function_exists( 'qode_listing_post_reviews_html' ) ) {

	function qode_listing_post_reviews_html($reviews = array(), $post_id) {

		$post = get_post($post_id);
		$html = '';

		if(count($reviews)){

			foreach ($reviews as $comment){

				$is_pingback_comment = $comment->comment_type == 'pingback';
				$is_author_comment  = $post->post_author == $comment->user_id;

				$comment_class = 'qode-comment clearfix';

				if($is_author_comment) {
					$comment_class .= ' qode-post-author-comment';
				}

				if($is_pingback_comment) {
					$comment_class .= ' qode-pingback-comment';
				}
				$review_rating = get_comment_meta( $comment->comment_ID, 'qode_rating', true );
				$review_rating_style  = 'width: '.esc_attr($review_rating*20).'%';
				$review_title = get_comment_meta( $comment->comment_ID, 'qode_comment_title', true );

				$comment_params = array(
					'comment'   => $comment,
					'is_pingback_comment' => $is_pingback_comment,
					'is_author_comment' => $is_author_comment,
					'comment_class' => $comment_class,
					'review_rating_style' => $review_rating_style,
					'review_title' => $review_title,
				);
				$html .= qode_listing_single_template_part('review/review', '', $comment_params);

			}
		}
		return $html;
	}
}

if(!function_exists('qode_listing_get_post_reviews_ajax')){

	function qode_listing_get_post_reviews_ajax(){

		if(isset($_POST)) {
			$html = '';

			foreach($_POST as $key => $value) {
				if($key !== '') {
					$addUnderscoreBeforeCapitalLetter  = preg_replace('/([A-Z])/', '_$1', $key);
					$setAllLettersToLowercase          = strtolower($addUnderscoreBeforeCapitalLetter);
					$params[$setAllLettersToLowercase] = $value;
				}
			}
			extract($params);
			if(isset($order) && $order !== '' && isset($order_by) && $order_by !== '' && isset($post_id) && $post_id !== ''){
				$post_comments = qode_listing_get_current_post_comments($post_id, $order_by, $order );
				ob_start();
				qode_listing_post_reviews_html($post_comments, $post_id);
				$html = ob_get_clean();
			}

			$return_obj = array(
				'html' => $html
			);
			echo json_encode($return_obj); exit;
		}

	}

	add_action('wp_ajax_nopriv_qode_listing_get_post_reviews_ajax', 'qode_listing_get_post_reviews_ajax');
	add_action( 'wp_ajax_qode_listing_get_post_reviews_ajax', 'qode_listing_get_post_reviews_ajax' );
}