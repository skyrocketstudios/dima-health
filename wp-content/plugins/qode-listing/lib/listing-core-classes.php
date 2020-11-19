<?php
namespace QodeListing\Lib\Core;
class ListingQuery{

	private $query_results;
	private $query_array;
	private $query_meta_array;
	private $post_number;
	private $listing_type_id;
	private $category_array;
	private $meta_query_flag;
	private $checkbox_meta_params;
	private $default_meta_params;
	private $next_page;
	private $user_id;
	private $post_status;
	private $keyword;
	private $post__in;
	private $post__not_in;
	private $tag;
	private $location;
	private $tax_query;
	private $location_object;

	public function __construct($listing_type_id = '', $post_number = '-1', $category_array = array(), $meta_query_flag = false, $checkbox_meta_params = array(), $default_meta_params = array(), $next_page = '', $user_id = '', $post_status = 'publish', $keyword = '', $post__in = array(), $post__not_in = array(), $tag = '', $location = '', $tax_query = array(), $location_object = array()) {

		$this->listing_type_id = $listing_type_id;
		$this->post_number = $post_number;
		$this->category_array = $category_array;
		$this->meta_query_flag = $meta_query_flag;
		$this->checkbox_meta_params = $checkbox_meta_params;
		$this->default_meta_params = $default_meta_params;
		$this->next_page = $next_page;
		$this->user_id = $user_id;
		$this->post_status = $post_status;
		$this->keyword = $keyword;
		$this->post__in = $post__in;
		$this->post__not_in = $post__not_in;
		$this->tag = $tag;
		$this->location = $location;
		$this->tax_query = $tax_query;
		$this->location_object = $location_object;

		$this->generateQueryArray();
		$this->setQueryResults();

	}

	private function generateQueryArray(){

		$this->query_array = array(
			'post_status'   => $this->post_status,
			'post_type'     => 'job_listing',
			'posts_per_page'=> (int)$this->post_number,
			'suppress_filters' => 0
		);

		if($this->user_id !== ''){
			$this->query_array['author'] = $this->user_id;
		}
		if($this->keyword !== ''){
			$this->query_array['s'] = $this->keyword;
		}

		if($this->listing_type_id !== '' && $this->listing_type_id !== 'all'){
			$this->query_array['tax_query'][] = array(
				'taxonomy' => 'job_listing_type',
				'field' => 'term_id',
				'terms' => (int)$this->listing_type_id
			);
		}

		if(count($this->category_array)){
			$this->query_array['tax_query'][] = array(
				'taxonomy' => 'job_listing_category',
				'field' => 'slug',
				'terms' => $this->category_array
			);
		}

		if($this->tag !== ''){
			$this->query_array['tax_query'][] = array(
				'taxonomy' => 'job_listing_tag',
				'field' => 'term_id',
				'terms' => (int)$this->tag
			);
		}

		if($this->location !== ''){
			$this->query_array['tax_query'][] = array(
				'taxonomy' => 'job_listing_region',
				'field' => 'term_id',
				'terms' => (int)$this->location
			);
		}

		if(count($this->tax_query)){
			$this->query_array['tax_query'][] = array(
				'taxonomy' => $this->tax_query['tax_id'],
				'field' => 'slug',
				'terms' => $this->tax_query['tax_slug_array']
			);
		}

		if(count($this->post__in)){
			$this->query_array['post__in'] = $this->post__in;
		}
		if(count($this->post__not_in)){
			$this->query_array['post__not_in'] = $this->post__not_in;
		}

		if($this->meta_query_flag){

			$meta_query_fields = array(
				'relation' => 'AND'
			);

			if(count($this->checkbox_meta_params)){
				foreach ($this->checkbox_meta_params as $param_key => $param_value){
					if($param_value === 'true'){
						$meta_query_fields[] = array(
							'key' => $param_key,
							'value' => '1' //amenities has value 1 or 0
						);
					}
				}
			}
			if(count($this->default_meta_params)){

				foreach ($this->default_meta_params as $param_key => $param_value){

					if($param_value !== ''){
						if($param_key === 'price_max'){
							$meta_query_fields[] = array(
								'key' => '_listing_disc_price',
								'value' => $param_value,
								'type'    => 'numeric',
								'compare' => '<='
							);
						}elseif($param_key === 'price_min'){
							array(
								'key' => '_listing_disc_price',
								'value' => $param_value,
								'type'    => 'numeric',
								'compare' => '=>'
							);
						}elseif($param_key === 'price_both_values'){
							array(
								'key' => '_listing_disc_price',
								'value' => array($param_key['min'], $param_key['max']),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							);
						}
						else{
							$meta_query_fields[] = array(
								'key' => $param_key,
								'value' => $param_value
							);
						}
					}
				}
			}

			$this->query_meta_array[] = $meta_query_fields;
			$this->query_array['meta_query'] = $this->query_meta_array;

		}

		//generate paged param
		if($this->next_page !== ''){
			$this->query_array['paged'] = (int)$this->next_page;
		} else {
			$this->query_array['paged'] = 1;
		}
	}

	private function setQueryResults(){

		if(isset($this->location_object['dist']) && isset($this->location_object['lat']) && isset($this->location_object['long'])){

			//we need to get all listings, not just posts from first pagination page
			$this->query_array['posts_per_page'] = '-1';

			$query = new \WP_Query($this->query_array);

			$lat = $this->location_object['lat'];
			$long = $this->location_object['long'];
			$dist = $this->location_object['dist'];
			$posts = $query->get_posts();
			$post_in = $post_not_in = $posts_to_check = array();

			if($posts && count($posts)){
				foreach($posts as $post){
					$posts_to_check[$post->ID] = $post->post_title;
				}
			}

			//get hide and show arrays
			$geo_location_answer  = qode_listing_check_distance($lat, $long, $dist, $posts_to_check);


			if(isset($geo_location_answer['hide_items'])){

				if(count($geo_location_answer['hide_items'])){
					$this->query_array['post__not_in'] = $geo_location_answer['hide_items'];
				}

			}

			if(isset($geo_location_answer['show_items'])){

				if(count($geo_location_answer['show_items'])){
					$this->query_array['post_in'] = $geo_location_answer['show_items'];
				}

			}

			//set post_per_page like it should be
			$this->query_array['posts_per_page'] = $this->post_number;

			//finally get real query results
			$this->query_results = new \WP_Query($this->query_array);

		}else{
			$this->query_results = new \WP_Query($this->query_array);
		}
	}
	public function getQueryResults(){
		return $this->query_results;
	}

	public function getQueryResultsArray(){
		$listing_array = array();

		if($this->query_results->have_posts()){
			while ( $this->query_results->have_posts() ) {
				$this->query_results->the_post();
				$listing_array[get_the_ID()] = get_the_title();
			}
			wp_reset_postdata();
		}

		return $listing_array;

	}


}
class ListingRating{

	private $post_id;
	private $rating_value;
	private $average_rate;
	private $old_value;

	public function __construct($post_id, $rating_value = false, $action = '', $old_value = '') {

		$this->post_id = $post_id;
		$this->rating_value = $rating_value;
		$this->update_flag = true;
		$this->old_value = $old_value;

		switch($action){
			case 'get_average_rating':
				$this->setAverageRating();
				break;
			case 'set_new_rating':
				$this->increaseRating();
				break;
			case 'edit_rating':
				$this->editRating();
				break;
		}

	}

	public function increaseRating(){
		$this->updateRateNumber();
		$this->updateRateCount();
	}

	public function editRating(){

		$new_value = (int)$this->getRateCount() - (int)$this->old_value + (int)$this->rating_value;
		$this->setRateCount($new_value);

	}


	public function setAverageRating(){

		/*$rating_value = $this->getRateCount();
		$number_of_rates = $this->getRateNumber();

		if ($rating_value == '' || $number_of_rates == '') {
			$this->average_rate = 0;
		}

		if ($number_of_rates > 0 && $rating_value > 0) {
			$this->average_rate = round($rating_value / ($number_of_rates), 2);
		}*/


		/*new function to calculate average rating since previous was not calculating properly when rating is deleted from admin*/


        $id = get_the_id();
        $comment_array = get_approved_comments( $id );
        $count         = ! empty( $comment_array ) ? count( $comment_array ) : 0;
        $sum = 0;
        $avg_rating = 0;

        $rating_criteria = bridge_core_rating_criteria('job_listing');
        $key = $rating_criteria[0]['key'];

        foreach ($comment_array as $comment){
            $rating = get_comment_meta( $comment->comment_ID, $key, true );
            $sum += intval( $rating );
        }

        if($count != 0){
            $avg_rating = round($sum/$count, 2);
        }

        $this->average_rate = $avg_rating;

	}

	public function getAverageRating(){
		return $this->average_rate;
	}

	private function updateRateNumber($action = 'plus'){

		$current_rates = $this->getRateNumber();

		if ($current_rates === '') {
			$current_rates  = 1;
		} else {
			$current_rates++;
		}

		$this->setRateNumber($current_rates);

	}

	private function getRateNumber(){
		return get_post_meta($this->post_id, 'qode_post_rating_number', true);
	}

	private function setRateNumber($count){
		update_post_meta($this->post_id, 'qode_post_rating_number', $count);
	}

	private function updateRateCount(){

		$rating_value = $this->getRateCount();


		if ($rating_value === '') {
			$rating_value = $this->rating_value;
		} else {
			$rating_value += $this->rating_value;
		}

		$this->setRateCount($rating_value);

	}

	private function getRateCount(){
		return get_post_meta($this->post_id, 'qode_post_rating_value', true);
	}

	private function setRateCount($value){
		update_post_meta($this->post_id, 'qode_post_rating_value', $value);
	}

	public function getRatingHtml(){

	    $avg_rating = $this->getAverageRating();

        //20* average rating in order to get actual percentages(average rating go from 0 to 5).

        $width = 20 * $avg_rating;

		//$width = 20*$this->getAverageRating();

		$style = 'width: '.$width.'%';

		if($width == '' && $width == 0){
			return;
		}
		?>
			<div class="qode-listing-rating-holder">

				<meta itemprop="ratingValue" content="<?php echo esc_attr($this->getAverageRating()); ?>">

				<?php /*<div class="qode-average-rating">
					<span>
						<?php echo esc_attr($this->getAverageRating());?>
					</span>
				</div> */ ?>

				<div class="qode-listing-rating-stars-holder">
					<span class="qode-rating-stars" <?php echo bridge_qode_get_inline_style($style) ?>></span>
				</div>

			</div>
		<?php
	}

}
class ListingViews{

	private $post_id;
	private $user_address;
	private $cookie_name;

	public function __construct($post_id, $user_address = '' ) {

		$this->post_id = $post_id;
		$this->user_address = $user_address;
		$this->cookie_name = 'qode_listing_single_id_'.$this->post_id;

	}

	public function setCountValue(){

		if ($this->isSetCookie()) {
			return;
		} else {
			$this->updateTotalCount();
		}

	}

	public function isSetCookie(){

		$flag = false;

		if(isset($_COOKIE[$this->cookie_name])){
		    $flag = true;
        }

		return $flag;

    }

	public function setCookie(){
		if(!$this->isSetCookie()){
			setcookie($this->cookie_name, $this->cookie_name, time()*20, '/');
		}
	}

	private function updateTotalCount(){

		$current_count = $this->getViewCount();

		if ($current_count == '') {
			$current_count = 1;
		}else{
			$current_count++;
		}
		$this->setViewCount($current_count);

	}

	public function getViewCount(){
		return get_post_meta($this->post_id, 'qode_post_view_count', true);
	}

	private function setViewCount($value){
		update_post_meta($this->post_id, 'qode_post_view_count', $value);
	}

}
class ListingArticle{

	private static $instance;
	private $post_id;
	private $post_type;

	public function __construct($post_id) {
		$this->post_id = $post_id;
		$this->setPostType();
	}

	/**
	 * Returns current instance of class
	 * @return ListingArticle
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			return new self;
		}

		return self::$instance;
	}

	/**
	 * Make sleep magic method private, so nobody can serialize instance.
	 */

	private function __clone() {}

	/**
	 * Make sleep magic method private, so nobody can serialize instance.
	 */
	private function __sleep() {}

	/**
	 * Make wakeup magic method private, so nobody can unserialize instance.
	 */
	private function __wakeup() {}

	private function setPostType(){
		$this->post_type = get_post_type($this->post_id);
	}

	private function getPostType(){
		return $this->post_type;
	}

	public function getTaxArray($taxonomy){

		$tax_array = array();
		$taxes = wp_get_object_terms($this->post_id, $taxonomy);

		if(is_array($taxes) && count($taxes)){
			foreach($taxes as $tax){

				$tax_array[]  = array(
					'id' => $tax->term_id,
					'name' => $tax->name,
					'link' => get_term_link($tax->term_id, $taxonomy),
					'icon_html' => qode_listing_get_listing_category_icon_html($tax->term_id)
				);

			}
		}
		return $tax_array;
	}


	public function getPostMeta($post_meta){

		$meta_field_value = get_post_meta($this->post_id, $post_meta, true);
		return $meta_field_value;

	}

	public function getTaxHtml($taxonomy, $custom_css_class = ''){

		$html = '';
		$taxes = $this->getTaxArray($taxonomy);

		if(count($taxes)){
			$html .= '<div class="qode-tax-wrapper '.esc_attr($custom_css_class).'">';
			foreach($taxes as $tax){

				$html .= '<a href="'.esc_url($tax['link']).'">';

				$html .= '<span class="qode-tax-name">'.esc_attr($tax['name']).'</span>';
				$html .= '</a>';
			}
			$html .= '</div>';
		}
		return $html;
	}

	public function getListingAverageRating(){

		ob_start();
		$rating_obj = new ListingRating($this->post_id, false, 'get_average_rating' );
		$rating_obj->getRatingHtml();
		$html = ob_get_clean();

		return $html;
	}
	public function getListingAverageRatingNumber(){


		$rating_obj = new ListingRating($this->post_id, false, 'get_average_rating' );

		return round($rating_obj->getAverageRating());
	}
	public function getAddressIconHtml(){

		$params_address = qode_listing_get_address_params($this->post_id);
		$city  = $this->getPostMeta('geolocation_city');

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

	public function getPriceHtml(){
		$price_html = '';

		$price = $this->getPostMeta('_listing_price');
		$disc_price = $this->getPostMeta('_listing_disc_price');

		if(($price && $price !== '') || ($disc_price && $disc_price !== '')){

			$price_html .= '<div class="qode-ls-price-holder">';
			if($price && $price !== '' && $disc_price && $disc_price < $price){
				$price_html .= '<span class="qode-ls-disc-price-amount">';
				if(bridge_qode_options()->getOptionValue('listings_woocommerce_currency') == 'yes'){
					if(bridge_qode_is_woocommerce_installed()){
						$price_html .= get_woocommerce_currency_symbol().esc_attr($disc_price);
					}
					else{
						$price_html .= esc_attr('$').esc_attr($disc_price);
					}
				}
				else{
					$price_html .= esc_attr('$').esc_attr($disc_price);
				}
				$price_html .= '</span>';
                $price_html .= '<span class="qode-ls-price-amount qode-ls-price-with-discount">';
                if(bridge_qode_options()->getOptionValue('listings_woocommerce_currency') == 'yes'){
                    if(bridge_qode_is_woocommerce_installed()){
                        $price_html .= get_woocommerce_currency_symbol().esc_attr($price);
                    }
                    else{
                        $price_html .= esc_attr('$').esc_attr($price);
                    }
                }
                else{
                    $price_html .= esc_attr('$').esc_attr($price);
                }
                $price_html .= '</span>';
			}
			else if($price && $price !== ''){
                $price_html .= '<span class="qode-ls-price-amount">';
                if(bridge_qode_options()->getOptionValue('listings_woocommerce_currency') == 'yes'){
                    if(bridge_qode_is_woocommerce_installed()){
                        $price_html .= get_woocommerce_currency_symbol().esc_attr($price);
                    }
                    else{
                        $price_html .= esc_attr('$').esc_attr($price);
                    }
                }
                else{
                    $price_html .= esc_attr('$').esc_attr($price);
                }
                $price_html .= '</span>';
            }

			$price_html .= '</div>';
		}

		return $price_html;
	}

	public function getActualPriceHtml(){
		$price_html = '';

		$disc_price = $this->getPostMeta('_listing_disc_price');

		if($disc_price && $disc_price !== ''){

			$price_html .= '<div class="qode-ls-actual-price-holder">';

			if($disc_price && $disc_price !== ''){
				$price_html .= '<span class="qode-ls-disc-price-amount">';
				if(bridge_qode_options()->getOptionValue('listings_woocommerce_currency') == 'yes'){
					if(bridge_qode_is_woocommerce_installed()){
						$price_html .= get_woocommerce_currency_symbol().esc_attr($disc_price);
					}
					else{
						$price_html .= esc_attr('$').esc_attr($disc_price);
					}
				}
				else{
					$price_html .= esc_attr('$').esc_attr($disc_price);
				}
				$price_html .= '</span>';
			}

			$price_html .= '</div>';
		}

		return $price_html;
	}

}

class ListingTitleGlobalVar{
    
    private $type;
    
    public function __construct($type = '') {
	
	$this->type  = $type;
	$this->generateGlobalVar();
	
    }
    
    public function generateGlobalVar(){
	
	$listing_posts = $params = array();
	
	if($this->type !== ''){
	    $params['type'] = $this->type;
	}	
	
	$query_results = qode_listing_get_listing_query_results($params);

	if($query_results->have_posts()){
		while($query_results->have_posts()){
			$query_results->the_post();
			$listing_posts[] = get_the_title();
		}
		wp_reset_postdata();
	}

	$listing_posts = apply_filters('qode_listing_filter_js_listing_variables', $listing_posts);

	wp_localize_script('bridge-default', 'qodeListingTitles', array(
		'titles' => $listing_posts
	));
    }

}