<?php
use  QodeListing\Maps;
if(!function_exists('qode_listing_generate_listing_map_vars')){

	/**
	 * Generates map variables based on sent attributes
	 * $attributes can contain:
	 *      $type - 'single' or 'multiple'. Single map variables is for google map on single listing pages, multiple is used for different lists and archive pages
	 *      $id - id of current listing. Note that id is only used for single listing pages
	 *      $query - $query is used just for multiple type. $query is Wp_Query object containing listing items which should be presented on map
	 *      $init_multiple_map - boolean value to enable initial setting of map items. For example, when listing items are loaded via ajax, this is set on false, and in that case this param is false
	 *
	 * @param $attributes
	 *
	 */

	function qode_listing_generate_listing_map_vars($attributes){

		$type = '';
		$id = '';
		$query = '';
		$init_multiple_map = false;

		extract($attributes);
		new Maps\MapGlobalVars($type, $id, $query, $init_multiple_map);

	}

}


if ( ! function_exists( 'qode_listing_set_global_map_variables' ) ) {
	/**
	 * Function for setting global map variables
	 */
	function qode_listing_set_global_map_variables() {

		if(qode_listing_theme_installed()) {
			$global_map_variables = array();

			$global_map_variables['mapStyle'] = json_decode(bridge_qode_options()->getOptionValue('listing_map_style'));
			$global_map_variables['scrollable'] = bridge_qode_options()->getOptionValue('listing_maps_scrollable') == 'yes' ? true : false;
			$global_map_variables['draggable'] = bridge_qode_options()->getOptionValue('listing_maps_draggable') == 'yes' ? true : false;
			$global_map_variables['streetViewControl'] = bridge_qode_options()->getOptionValue('listing_maps_street_view_control') == 'yes' ? true : false;
			$global_map_variables['zoomControl'] = bridge_qode_options()->getOptionValue('listing_maps_zoom_control') == 'yes' ? true : false;
			$global_map_variables['mapTypeControl'] = bridge_qode_options()->getOptionValue('listing_maps_type_control') == 'yes' ? true : false;

			$global_map_variables = apply_filters('qode_listing_filter_js_global_map_variables', $global_map_variables);

			wp_localize_script('bridge-default', 'qodeMapsVars', array(
				'global' => $global_map_variables
			));
		}
	}

	add_action('wp_enqueue_scripts', 'qode_listing_set_global_map_variables', 20);

}

if( ! function_exists( 'qode_listing_set_single_map_variables' ) ) {
	/**
	 * Function for setting single map variables
	 */
	function qode_listing_set_single_map_variables() {

		if ( is_singular('job_listing') ) {
			$map_array = array(
				'type' => 'single',
				'id' => get_the_ID()
			);
			qode_listing_generate_listing_map_vars($map_array);
		}

	}
	add_action('wp', 'qode_listing_set_single_map_variables', 1);
}


if ( ! function_exists( 'qode_listing_get_listing_item_map' ) ) {
	/**
	 * Function that renders map holder for single listing item
	 *
	 * @return string
	 */
	function qode_listing_get_listing_item_map($latitude, $longitude) {


		$html = '<div id="qode-ls-single-map-holder"></div>
				<meta itemprop="latitude" content="'. $latitude .'">
				<meta itemprop="longitude" content="'. $longitude .'">';

		do_action('qode_listing_after_listing_map');

		return $html;

	}

}

if ( ! function_exists( 'qode_listing_get_listing_multiple_map' ) ) {
	/**
	 * Function that renders map holder for multiple listing item
	 *
	 * @return string
	 */
	function qode_listing_get_listing_multiple_map() {

		$html = '<div id="qode-ls-multiple-map-holder"></div>';

		do_action('qode_listing_after_listing_map');

		return $html;

	}

}

if ( ! function_exists( 'qode_listing_marker_info_template' ) ) {
	/**
	 * Template with placeholders for marker info window
	 *
	 * uses underscore templates
	 *
	 */
	function qode_listing_marker_info_template() {

		$html = '<script type="text/template" class="qode-info-window-template">
				<div class="qode-info-window">
					<div class="qode-info-window-inner">
						<a href="<%= itemUrl %>"></a>
						<div class="qode-info-window-details">
							<h6>
								<%= title %>
							</h6>
							<p><%= address %></p>
						</div>
						<% if ( featuredImage ) { %>
							<div class="qode-info-window-image">
								<img src="<%= featuredImage[0] %>" alt="<%= title %>" width="<%= featuredImage[1] %>" height="<%= featuredImage[2] %>">
							</div>
						<% } %>
					</div>
				</div>
			</script>';

		print $html;

	}

	add_action('qode_listing_after_listing_map', 'qode_listing_marker_info_template');

}

if ( ! function_exists( 'qode_listing_marker_template' ) ) {
	/**
	 * Template with placeholders for marker
	 */
	function qode_listing_marker_template() {

		$html = '<script type="text/template" class="qode-marker-template">
				<div class="qode-map-marker">
					<div class="qode-map-marker-inner">
					<%= pin %>
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		                    width="56px" height="67.316px" viewBox="0 0 56 67.316" enable-background="new 0 0 56 67.316" xml:space="preserve">
						<path fill="#1CB5C1" d="M55.939,27.722c-0.054-7.367-2.957-14.287-8.176-19.494c-5.27-5.26-12.28-8.161-19.736-8.157
							c-7.456-0.004-14.47,2.895-19.743,8.157c-5.267,5.255-8.172,12.255-8.171,19.697C0.113,35.363,3.018,42.359,8.29,47.62
							l19.738,19.696l19.513-19.472l0.08-0.078c0.05-0.051,0.098-0.099,0.143-0.143c0.052-0.053,0.099-0.099,0.146-0.147l0.074-0.071
							L49,46.305C53.535,41.163,55.997,34.617,55.939,27.722z"/>
						</svg>
					</div>
				</div>
			</script>';

		print $html;

	}

	add_action('qode_listing_after_listing_map', 'qode_listing_marker_template');

}


if(!function_exists('qode_listing_get_address_params')){

	/**
	 * Function that set up address params
	 * param id - id of current post
	 *
	 * @return array
	 */

	function qode_listing_get_address_params($id){

		$address_array = array();
		$address_string = get_post_meta( $id, 'geolocation_formatted_address', true );
		$address_lat = get_post_meta( $id, 'geolocation_lat', true );
		$address_long = get_post_meta( $id, 'geolocation_long', true );

		$address_array['address'] = $address_string !== '' ? $address_string : '';
		$address_array['address_lat'] = $address_lat !== '' ? $address_lat : '';
		$address_array['address_long'] = $address_long !== '' ? $address_long : '';

		return $address_array;

	}

}

if(!function_exists('qode_listing_get_address_html')){
	/**
	 * Function return listing address html
	 * param id - id of current post
	 *
	 * @return string
	 */
	function qode_listing_get_address_html($id){

		$params_address = qode_listing_get_address_params($id);
		$city = get_post_meta($id, 'geolocation_city' , true);

		extract($params_address);
		$html = '';
		$get_directions_link = '';

		if ( $address_lat !== '' && $address_long !== '' ) {
			$get_directions_link = '//maps.google.com/maps?daddr=' . $address_lat . ',' . $address_long;
		}

		if($get_directions_link !== ''){
			$html .= '<div class="qode-ls-adr-pin">';
			$html .= '<a href="'.$get_directions_link.'" target="_blank">';
			$html .= bridge_qode_icon_collections()->getIconHTML('icon_pin', 'font_elegant');
			$html .= '</a>';
			$html .= '</div>';
		}

		if($city !== ''){
			$html .= '<div class="qode-ls-adr-city">';
			$html .= '<span>'.esc_html__('In ', 'qode-listing' ).'</span>';
			$html .= '<span class="qode-city">'.esc_html($city).'</span>';
			$html .= '</div>';
		}

		return $html;
	}
}

if(!function_exists('qode_listing_add_params_to_map_url')){

	function qode_listing_add_params_to_map_url($params) {
		$params['libraries'] = 'geometry,places';

		return $params;
	}

	add_filter('bridge_qode_filter_google_maps_get_params', 'qode_listing_add_params_to_map_url');
}