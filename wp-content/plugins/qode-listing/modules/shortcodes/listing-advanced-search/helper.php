<?php
use QodeListing\Lib\Shortcodes;
use QodeListing\Maps;
use QodeListing\Lib\Core;
use QodeListing\Lib\Front;

if(!function_exists('qode_listing_adv_search_shortcode_helper')) {
	function qode_listing_adv_search_shortcode_helper($shortcodes_class_name) {

		$shortcodes = array(
			'QodeListing\Lib\Shortcodes\ListingAdvancedSearch'
		);

		$shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

		return $shortcodes_class_name;
	}

	add_filter('qode_listing_filter_add_vc_shortcode', 'qode_listing_adv_search_shortcode_helper');
}

if(!function_exists('qode_listing_adv_search_class_instance')){

	function qode_listing_adv_search_class_instance(){
		return Shortcodes\ListingAdvancedSearch::getInstance();
	}

}

if( !function_exists('qode_listing_set_ls_adv_search_icon_class_name_for_vc_shortcodes') ) {
	/**
	 * Function that set custom icon class name for button shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function qode_listing_set_ls_adv_search_icon_class_name_for_vc_shortcodes($shortcodes_icon_class_array) {
		$shortcodes_icon_class_array[] = '.icon-wpb-ls-adv-search';

		return $shortcodes_icon_class_array;
	}

	add_filter('qode_core_filter_add_vc_shortcodes_custom_icon_class', 'qode_listing_set_ls_adv_search_icon_class_name_for_vc_shortcodes');
}

if(!function_exists('qode_listing_advanced_search_response')){

	function qode_listing_advanced_search_response(){

		$params = array();
		$html = '';
		$max_num_pages = '';

		if(isset($_POST)) {
			foreach ($_POST as $key => $value) {
				if($key !== '') {
					$addUnderscoreBeforeCapitalLetter = preg_replace('/([A-Z])/', '_$1', $key);
					$setAllLettersToLowercase = strtolower($addUnderscoreBeforeCapitalLetter);
					$params[$setAllLettersToLowercase] = $value;
				}
			}
			
			//make a new instance of adv search shortcode
			$adv_search_obj = qode_listing_adv_search_class_instance();
			
			//set basic params for new shortcode instance
			$adv_search_obj->setBasicParams($params);
			
			extract($params);
			$next_page = '';
			
			if($enable_load_more){
				$next_page = $load_more_data['nextPage'];
			}
			$cat_array = array();
			if(is_array($cat_params) && count($cat_params)){
				foreach($cat_params as $cat_slug => $cat_value){
					if($cat_value === 'true'){
						$cat_array[] = $cat_slug;
					}
				}
			}
			$meta_query_flag = false;
			if(count($check_box_search_params) || count($default_search_params)){
				$meta_query_flag = true;
			}
			
			$keyword_param = '';
			if(isset($keyword)){
			    $keyword_param  = $keyword;
			}

			$query_array = array(
				'type' => $type_id,
				'post_number' => $post_per_page,
				'category_array' => $cat_array,
				'meta_query_flag' => $meta_query_flag,
				'checkbox_meta_params' => $check_box_search_params,
				'default_meta_params' => $default_search_params,
				'keyword' => $keyword,
				'next_page' => $next_page
			);

			$query_results = qode_listing_get_listing_query_results($query_array);
			//set query results for new shortcode instance
			$adv_search_obj->setQueryResults($query_results);
			
			$max_num_pages = $query_results->max_num_pages;			
			
			if($query_results->have_posts()){
				while ( $query_results->have_posts() ) {
					$query_results->the_post();
					
					$article_obj = new Core\ListingArticle(get_the_ID());
					
					$params  = array(
						'type_html'			=> $article_obj->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
						'cat_html'			=> $article_obj->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
						'rating_html'		=> $article_obj->getListingAverageRating(),
						'address_html'		=> $article_obj->getAddressIconHtml(),
						'listing_author'	=> get_the_author(),
						'price_html'		=> $article_obj->getActualPriceHtml(),
						'article_obj'		=> $article_obj

					);
					
					$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-advanced-search','',$params);
					
				}
				wp_reset_postdata();
			}
			else{
				ob_start();
				$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-advanced-search');
				$html .= ob_get_clean();
			}
			
			$return_obj = array(
				'html' => $html,
				'maxNumPages' => $max_num_pages				
			);
			
			if($enable_map){
			    $map_var_obj = new Maps\MapGlobalVars('multiple', '', $query_results);
			    $multiple_map_vars = $map_var_obj->getMultipleVars();
			    $return_obj['mapAddresses'] = $multiple_map_vars;	
			}   
			
			echo json_encode($return_obj); exit;
		}

	}
	add_action('wp_ajax_nopriv_qode_listing_advanced_search_response', 'qode_listing_advanced_search_response');
	add_action( 'wp_ajax_qode_listing_advanced_search_response', 'qode_listing_advanced_search_response' );
}