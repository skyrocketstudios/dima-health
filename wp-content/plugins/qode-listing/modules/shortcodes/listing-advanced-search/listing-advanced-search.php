<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
use QodeListing\Lib\Core;

/**
 * Class ListingAdvancedSearch
 * @package QodeListing\Lib\Shortcodes
 */
class ListingAdvancedSearch implements ShortcodeInterface {

	private static $instance;
	private $basic_params;
	private $base;
	private $types;
	private $query;

	public function __construct() {

		$this->base = 'qode_listing_advanced_search';
		self::$instance = $this;

		add_action('vc_before_init', array($this, 'generateListingTypeArray'));
		add_action('vc_before_init', array($this, 'vcMap'));
		
	}


	/**
	 * Returns current instance of class
	 * @return ListingAdvancedSearch
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

	public function generateListingTypeArray(){
		$this->types = qode_listing_get_listing_types_VC_Array();
	}

	public function getBase() {
		return $this->base;
	}

	public function setBasicParams($params = array()){

		if(is_array($params) && count($params)){
			foreach($params as $param_key => $param_value){
				$this->basic_params[$param_key] = $param_value;
			}
		}

	}

	public function resetBasicParams(){
		if(is_array($this->basic_params) && count($this->basic_params)){
			foreach ($this->basic_params as $param_key => $param_value) {
				unset($this->basic_params[$param_key]);
			}
		}
	}

	public function getBasicParams(){
		return $this->basic_params;
	}

	public function getBasicParamByKey($key){
		return $this->basic_params[$key];
	}

	public function setQueryResults($query){
		$this->query = $query;
	}
	public function getQueryResults(){
		return $this->query;
	}

	public function vcMap() {

		vc_map(array(
			'name'                      => esc_html__('Listing Advanced Search', 'qode-listing'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-listing-advanced-search extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'heading'     => esc_html__('Listing Type', 'qode-listing'),
					'value'       => $this->types,
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'number',
					'heading'     => esc_html__('Number of items', 'qode-listing'),
					'admin_label' => true
				),
                array(
                    'type'        => 'textfield',
					'param_name'  => 'search_title',
                    'heading'     => esc_html__('Listing Title', 'qode-listing'),
                    'admin_label' => true
                ),
                array(
                    'type'        => 'textfield',
                    'param_name'  => 'search_subtitle',
                    'heading'     => esc_html__('Listing Subtitle', 'qode-listing'),
                    'admin_label' => true
                ),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'load_more',
					'heading'     => esc_html__('Enable load more', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'content_in_grid',
					'heading'     => esc_html__('Content in Grid', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_map',
					'heading'     => esc_html__('Enable Map', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'map_in_grid',
					'heading'     => esc_html__('Map in Grid', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true,
					'dependency'  => array(
					    'element' => 'enable_map',
					    'value'   => 'yes'	
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'keyword_search',
					'heading'     => esc_html__('Enable Keyword Search', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_banner',
					'heading'     => esc_html__('Sidebar Banner', 'qode-listing'),
					'value'       => array_flip(qode_listing_get_yes_no_select_array(true)),
					'admin_label' => true
				),
				array(
					'type' => 'attach_image',
					'param_name' => 'banner_image',
					'heading' =>esc_html__(  'Banner Image','qode-listing'),
					'admin_label' => true,
					'dependency' => array(
						'element' => 'enable_banner',
						'value'   => 'yes'
					)
				),
				array(
					'type' => 'textfield',
					'param_name' => 'banner_title',
					'heading' =>esc_html__('Banner title', 'qode-listing'),
					'admin_label' => true,
					'dependency' => array(
						'element' => 'enable_banner',
						'value'   => 'yes'
					)
				),
				array(
					'type' => 'textfield',
					'param_name' => 'banner_text',
					'heading' =>esc_html__('Banner text', 'qode-listing'),
					'admin_label' => true,
					'dependency' => array(
						'element' => 'enable_banner',
						'value'   => 'yes'
					)
				),
				array(
					'type' => 'textfield',
					'param_name' => 'banner_link',
					'heading' =>esc_html__('Banner link', 'qode-listing'),
					'admin_label' => true,
					'dependency' => array(
						'element' => 'enable_banner',
						'value'   => 'yes'
					)
				),
				array(
					'type' => 'textfield',
					'param_name' => 'banner_link_text',
					'heading' =>esc_html__('Banner link text', 'qode-listing'),
					'admin_label' => true,
					'dependency' => array(
						'element' => 'enable_banner',
						'value'   => 'yes'
					)
				),
			)
		));

	}

	public function render($atts, $content = null) {
		$args = array(
			'type'				=> '',
			'number'			=> '-1',
			'load_more'			=> '',
			'content_in_grid'	=> '',
			'enable_map'		=> '',
			'map_in_grid'		=> '',
			'keyword_search'	=> '',
			'enable_banner'		=> '',
			'banner_image'		=> '',
			'banner_title'		=> '',
			'banner_text'		=> '',
			'banner_link'		=> '',
			'banner_link_text'	=> '',
            'search_title'		=> '',
            'search_subtitle'	=> ''
		);
		
		$params = shortcode_atts($args, $atts);
		$this->resetBasicParams();
		$this->setBasicParams($params);
		extract($params);

		//get query results
		$query_results = null;

        $query_params = array(
            'post_number' => $number
        );

		if($type !== '') {
            $query_params['type'] = $type;
		} else{
            $query_params['post__in'] = array(0);
        }

        $query_results = qode_listing_get_listing_query_results($query_params);
		//set query results
		$this->setQueryResults($query_results);

		//init google map if is chosen in shortcode options
		$this->initGoogleMap();

		//add data param
		$this->setBasicParams(array(
			'data_params' => $this->getDattaParams(),
			'holder_classes' => $this->getHolderClasses(),
			'banner_html' => $this->getBannerHtml()
		));

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-advanced-search');
	}
	
	private function getHolderClasses(){
	    
	    $classes = array();
	    
	    $map_flag = $this->getBasicParamByKey('enable_map') === 'yes' ? true : false ;
	    
	    if($map_flag){
		$classes[] = 'qode-ls-adv-with-map';
	    }
	    
	    return implode($classes, ' ');
	    
	}

	private function getDattaParams(){

		$dataString  = '';
		$params = $this->getBasicParams();

		if(get_query_var('paged')) {
			$paged = get_query_var('paged');
		} elseif(get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		$params['max_num_pages'] = 0;
		$query_results = $this->getQueryResults();

		if($query_results && $query_results !== null){
			$params['max_num_pages'] = $query_results->max_num_pages;
		}

		if(isset($paged)) {
			$params['next_page'] = $paged+1;
		}

		foreach ($params as $key => $value) {
			if($value !== '') {
				$new_key = str_replace( '_', '-', $key );
				$dataString .= ' data-'.$new_key.'='.esc_attr($value);
			}
		}

		return $dataString;
	}

	public function getListingTypeHtml(){

		$html = '';
		$listing_types = qode_listing_get_listing_types_by_listing_id(get_the_ID());

		if(count($listing_types)){

			$html .= '<div class="qode-listing-type-wrapper">';
			foreach($listing_types as $type){
				$html .= '<a href="'.esc_url($type['link']).'">';
				$html .= '<span>'.esc_attr($type['name']).'</span>';
				$html .= '</a>';
			}

			$html .= '</div>';
		}

		return $html;

	}

	public function getListingCategoryHtml(){

		$html = qode_listing_get_listing_categories_by_listing_id(get_the_ID());
		return $html;

	}


	public function getListingAverageRating(){

		$html = '';
		$rating_obj = new Core\ListingRating(get_the_ID(), false, 'get_average_rating' );
		$html .= $rating_obj->getRatingHtml();

		return $html;
	}

	public function getAddressIconHtml(){

		$html = qode_listing_get_address_html(get_the_ID());
		return $html;

	}
	public function initGoogleMap(){
	    $enable_map = $this->getBasicParamByKey('enable_map');
	    if($enable_map === 'yes'){
		    //generate multiple map global vars from current query results
		    $map_array = array(
			    'type' => 'multiple',
			    'query' => $this->getQueryResults(),
			    'init_multiple_map' => true
		    );
		    qode_listing_generate_listing_map_vars($map_array);
		}
	}

	public function getBannerHtml(){
		$html = '';

		$banner_flag = $this->getBasicParamByKey('enable_banner') === 'yes' ? true : false;
		$banner_image_id = $this->getBasicParamByKey('banner_image');
		$banner_image = wp_get_attachment_image_src( $banner_image_id, 'full' );
		$banner_text = $this->getBasicParamByKey('banner_text');
		$banner_title = $this->getBasicParamByKey('banner_title');
		$banner_link = $this->getBasicParamByKey('banner_link');
		$banner_link_text = $this->getBasicParamByKey('banner_link_text');

		if($banner_flag){

			if($banner_image !== ''){

				$html .= '<div class="qode-ls-adv-search-banner-holder">';

				$html .= '<div class="qode-ls-banner-image">';
				$html .= '<img src="'.esc_url($banner_image[0]).'" alt="qode-ls-adv-banner-image" title="qode-ls-adv-banner-image" />';
				$html .= '</div>';

				if($banner_title !== ''){
					$html .= '<div class="qode-ls-banner-title">';
						$html .= '<h5>'.esc_attr($banner_title).'</h5>';
					$html .= '</div>';
				}
				if($banner_text !== ''){
					$html .= '<div class="qode-ls-banner-text">';
					$html .= '<span>'.esc_attr($banner_text).'</span>';
					$html .= '</div>';
				}

				if($banner_link_text !== '' && $banner_link !== ''){

					$button_params = array(
						'text'			=> $banner_link_text,
						'link'			=> $banner_link,
						'custom_class'	=> 'qode-listing-button qode-button-shadow',
						'type'			=> 'solid',
						'html_type'		=> 'href'
					);

					$html .= bridge_core_get_button_html($button_params);
				}

				$html .= '</div>';

			}

		}

		return $html;

	}

}