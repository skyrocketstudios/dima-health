<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
use  QodeListing\Maps;
if(!function_exists('qode_listing_type_get_custom_fields')){

	function qode_listing_type_get_custom_fields(){
		$type_id = $post_id = '';
		$html = '';

		if(isset($_POST['selectedType'])){
			$type_id = $_POST['selectedType'];

			if(isset($_POST['currentPostId'])){
				$post_id = $_POST['currentPostId'];
			}
			if($type_id !== ''){
				ob_start();
				$object = new Front\ListingTypeFieldCreator($type_id, $post_id);
				$object->renderListingFormFields();
				$html .= ob_get_clean();
			}
		}

		$return_obj = array(
			'html' => $html
		);

		echo json_encode($return_obj);exit;
	}
	add_action('wp_ajax_nopriv_qode_listing_type_get_custom_fields', 'qode_listing_type_get_custom_fields');
	add_action( 'wp_ajax_qode_listing_type_get_custom_fields', 'qode_listing_type_get_custom_fields' );
}
if(!function_exists('qode_listing_get_listing_type_amenities_html')){

	function qode_listing_get_listing_type_amenities_html(){
		$type_id = '';
		$html = '';
		if(isset($_POST['typeId'])){
			$type_id = $_POST['typeId'];

			if($type_id !== ''){
				ob_start();
				$object = new Front\ListingTypeFieldCreator($type_id);
				$object->getArchiveSearchHtml();
				$html .= ob_get_clean();
			}

		}

		$return_obj = array(
			'html' => $html
		);

		echo json_encode($return_obj);exit;
	}
	add_action('wp_ajax_nopriv_qode_listing_get_listing_type_amenities_html', 'qode_listing_get_listing_type_amenities_html');
	add_action( 'wp_ajax_qode_listing_get_listing_type_amenities_html', 'qode_listing_get_listing_type_amenities_html' );
}


if(!function_exists('qode_listing_get_archive_search_response')){

	function qode_listing_get_archive_search_response(){

		$search_params = array();
		$multiple_map_vars = array();
		$html = '';
		$max_num_pages = '';
		$found_posts = '';

		$post_in_array = $post_not_in_array = $locationObject = array();

		if(isset($_POST)) {
			if(isset($_POST['searchParams'])){
				$search_params = $_POST['searchParams'];
			}
			extract($search_params);

			$next_page = '';
			//just if is load more button clicked, take nextPage from params
			if($enableLoadMore !== 'false'){
				$next_page = $search_params['nextPage'];
			}

			$cat_array = array();
			if($cat !== ''){
				$cat_array[] = $cat;
			}
			$meta_query_flag = false;
			if(count($amenities) || count($customFields)){
				$meta_query_flag = true;
			}

			if(count($locationObject)){
				if(isset($locationObject['lat']) && isset($locationObject['long']) && isset($locationDist)){
					$locationObject['dist'] = $locationDist;
				}
			}

			$query_params = array(
				'type' => $type,
				'category_array' => $cat_array,
				'keyword' => $keyword,
				'post_in' => $post_in_array,
				'post_not_in' => $post_not_in_array,
				'tag' => $tag,
				'location' => $location,
				'post_number' => $number,
				'meta_query_flag' => $meta_query_flag,
				'checkbox_meta_params' => $amenities,
				'default_meta_params' => $customFields,
				'next_page' => $next_page,
				'location_object' => $locationObject
			);

			$query_results = qode_listing_get_listing_query_results($query_params);

			$max_num_pages = $query_results->max_num_pages;
			$found_posts = $query_results->found_posts;

			if($query_results->have_posts()){
				while ( $query_results->have_posts() ) {
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
					ob_start();
					qode_listing_get_archive_module_template_part('single', '', $params);
					$html .= ob_get_clean();
				}
				wp_reset_postdata();
			}
			else{
				ob_start();
				qode_listing_get_archive_module_template_part('archive/templates/post-not-found');
				$html = ob_get_clean();
			}

			$map_var_obj = new Maps\MapGlobalVars('multiple', '', $query_results);
			$multiple_map_vars = $map_var_obj->getMultipleVars();

			$return_obj = array(
				'html' => $html,
				'maxNumPages' => $max_num_pages,
				'mapAddresses' => $multiple_map_vars,
				'foundPosts' => $found_posts
			);
			echo json_encode($return_obj); exit;
		}

	}
	add_action('wp_ajax_nopriv_qode_listing_get_archive_search_response', 'qode_listing_get_archive_search_response');
	add_action( 'wp_ajax_qode_listing_get_archive_search_response', 'qode_listing_get_archive_search_response' );
}

if(!function_exists('qode_listing_get_main_search_response')){

	function qode_listing_get_main_search_response(){

		$keyword  = $type = $salary = '';
		$params = array();

		if(isset($_POST)) {

			foreach ($_POST as $key => $value) {
				if($key !== '') {
					$addUnderscoreBeforeCapitalLetter = preg_replace('/([A-Z])/', '_$1', $key);
					$setAllLettersToLowercase = strtolower($addUnderscoreBeforeCapitalLetter);
					$params[$setAllLettersToLowercase] = $value;
				}
			}
			extract($params);
			$href_attr  = qode_listing_build_query_string($keyword, $type, $salary);

			$return_obj = array(
				'href' => $href_attr
			);

			echo json_encode($return_obj); exit;
		}

	}
	add_action('wp_ajax_nopriv_qode_listing_get_main_search_response', 'qode_listing_get_main_search_response');
	add_action( 'wp_ajax_qode_listing_get_main_search_response', 'qode_listing_get_main_search_response' );
}

if(!function_exists('qode_listing_send_listing_item_enquiry')){

	function qode_listing_send_listing_item_enquiry(){
		if ( isset($_POST['data']) ) {

			$error = false;
			$responseMessage = '';

			$email_data = $_POST['data'];
			$nonce = $email_data['nonce'];

			if ( wp_verify_nonce( $nonce, 'qode_validate_listing_item_enquiry' ) ) {

				//Validate
				if ( $email_data['name'] ) {
					$name = esc_html($email_data['name']);
				} else {
					$error = true;
					$responseMessage = esc_html__('Please insert valid name', 'qode-listing');
				}

				if ( $email_data['email'] ) {
					$email = esc_html($email_data['email']);
				} else {
					$error = true;
					$responseMessage = esc_html__('Please insert valid email', 'qode-listing');
				}

				if ( $email_data['message'] ) {
					$message = esc_html($email_data['message']);
				} else {
					$error = true;
					$responseMessage = esc_html__('Please insert valid phone', 'qode-listing');
				}

				//Send Mail and response
				if ( $error ) {

					wp_send_json_error( $responseMessage );

				} else {

					//Get post id from request
					$post_id = $email_data['itemId'];
					//Get email address
					$mail_to = get_post_meta( $post_id, '_listing_mail', true );

					$headers = array(
						'From: ' . $name . ' <' . $email . '>',
						'Reply-To: ' . $name . ' <' . $email . '>',
					);

					$additional_emails = array();

					$post = get_post($post_id);
					$additional_emails[] = get_the_author_meta( 'user_email', (int) $post->post_author );
					$headers[] = 'Bcc: ' . implode(',', $additional_emails);

					$messageTemplate = esc_html__('From', 'qode-listing'). ': ' . $name . "\r\n";
					$messageTemplate .= esc_html__('Message', 'qode-listing') . ': ' . $message . "\r\n\n";
					$messageTemplate .= esc_html__( 'Message sent via enquiry form on', 'qode-listing' ) . ' ' . get_bloginfo('name') . ' - ' . esc_url( home_url('/') );

					wp_mail(
						$mail_to, //Mail To
						esc_html__('New Enquiry form blog name', 'qode-listing'), //Subject
						$messageTemplate, //Message
						$headers //Additional Headers
					);

					$responseMessage = esc_html__('Enquiry sent successfully', 'qode-listing');
					wp_send_json_success( $responseMessage );
				}

			}



		} else {
			$message = esc_html__('Please review your enquiry and send again', 'qode-listing');
			wp_send_json_error( $message );
		}
	}
	add_action('wp_ajax_nopriv_qode_listing_send_listing_item_enquiry', 'qode_listing_send_listing_item_enquiry');
	add_action( 'wp_ajax_qode_listing_send_listing_item_enquiry', 'qode_listing_send_listing_item_enquiry' );
}