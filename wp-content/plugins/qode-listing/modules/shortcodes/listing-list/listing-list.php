<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;

/**
 * Class ListingList
 * @package QodeListing\Lib\Shortcodes
 */
class ListingList implements ShortcodeInterface {

	private static $instance;
	private $base;
	private $basic_params;
	private $types;
	private $cats;

	public function __construct() {

		$this->base = 'qode_listing_list';
		self::$instance = $this;
		   
		add_action('vc_before_init', array($this, 'vcMap'));
		$this->generateListingCatsArray();
		$this->generateListingTypeArray();

	}


	/**
	 * Returns current instance of class
	 * @return ListingList
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
	
	public function generateListingTypeArray(){
		$this->types = qode_listing_get_listing_types_VC_Array();
	}
    
	public function getListingTypes(){
		return $this->types;
	}
    
	public function generateListingCatsArray(){
	    $this->cats = qode_listing_categories_VC_ARRAY(true);
	}

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Listing List', 'qode-listing'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-qode-listing-list extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'param_name'  => 'listing_list_skin',
					'heading'     => esc_html__('Skin', 'qode-listing'),
					'value'       => array(
						esc_html__('Default', 'qode-listing') => '',
						esc_html__('Light', 'qode-listing') => 'light',
						esc_html__('Dark', 'qode-listing') => 'dark'
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'listing_type',
					'heading'     => esc_html__('Listing Type', 'qode-listing'),
					'value'       => $this->types,
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'listing_category',
					'heading'     => esc_html__('Listing Category', 'qode-listing'),
					'value'       => $this->cats,
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'listing_list_number',
					'heading'     => esc_html__('Number of items', 'qode-listing'),
					'value'       => '',
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'listing_list_columns',
					'heading'     => esc_html__('Number of columns', 'qode-listing'),
					'value'       => array(
						esc_html__('Default', 'qode-listing') => '',
						esc_html__('One Column','qode-listing') => 'one',
						esc_html__('Two Columns','qode-listing') => 'two',
						esc_html__('Three Columns','qode-listing') => 'three',
						esc_html__('Four Columns','qode-listing') => 'four',
						esc_html__('Five Columns','qode-listing') => 'five',
						esc_html__('Six Columns','qode-listing') => 'six'
					),
					'admin_label' => true
				),
			)
		));

	}

	public function render($atts, $content = null) {
		$args = array(
			'listing_list_skin' => '',
			'listing_list_number' => '-1',
			'listing_list_columns'  => 'three',
			'listing_type'  => '',
			'listing_category'  => ''
		);
		$params = shortcode_atts($args, $atts);
		extract($params);

		$this->resetBasicParams();
		$this->setBasicParams($params);

		$this->setBasicParams(array(
			'holder_classes' => $this->getHolderClasses()
		));

		$query_results = qode_listing_get_listing_query_results($this->getQueryArray());

		$this->setQueryResults($query_results);

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-list');

	}

	public function getHolderClasses(){

		$classes = array(
			'qode-ls-list-normal-space'
		);

		$skin = $this->getBasicParamByKey('listing_list_skin');
		if($skin && $skin !== ''){
			$classes[] = 'qode-'.$skin.'-skin';
		}

		$column_number = $this->getBasicParamByKey('listing_list_columns');
		if($column_number && $column_number !== ''){
			$classes[] = 'qode-ls-list-'.$column_number.'-columns';
		}

		return implode($classes, ' ');
	}
	
	
	public function getQueryArray(){
	    
	    $query_params = array(
		'post_number' => $this->getBasicParamByKey('listing_list_number')
	    );
	    
	    $type = $this->getBasicParamByKey('listing_type');
	    $listing_category = $this->getBasicParamByKey('listing_category');

	    if($type !== ''){
		$query_params['type'] = $type;
	    }
	    if($listing_category !== ''){
		$query_params['category_array'] = array($listing_category);
	    }
	    
	    return $query_params;
	    
	}
}