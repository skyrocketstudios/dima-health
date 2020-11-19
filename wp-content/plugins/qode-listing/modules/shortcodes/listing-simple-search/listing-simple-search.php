<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
use QodeListing\Lib\Core;

/**
 * Class Button that represents button shortcode
 * @package Qode\Modules\Shortcodes\Button
 */
class ListingSimpleSearch implements ShortcodeInterface {

	private static $instance;
	private $base;
	private $basic_params;
	private $types;
    private $regions;

	public function __construct() {

		$this->base = 'qode_listing_simple_search';
		self::$instance = $this;

		add_action('vc_before_init', array($this, 'generateListingTypeArray'));
        add_action('vc_before_init', array($this, 'generateListingRegionArray'));
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


	public function getBase() {
		return $this->base;
	}

	public function generateListingTypeArray(){
		$types_array = qode_listing_get_listing_types(true);
		$this->types = $types_array['key_value'];
	}

	public function getListingTypes(){
		return $this->types;
	}
    
    public function generateListingRegionArray(){
		$region_array = qode_listing_get_listing_region(true);
		$this->regions = $region_array['key_value'];
	}

	public function getListingRegions(){
		return $this->regions;
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

	public function vcMap() {

		vc_map(array(
			'name'                      => esc_html__('Listing Simple Search', 'qode-listing'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-qode-listing-simple-search extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
                    'type'        => 'textfield',
					'param_name'  => 'listing_search_keyword_text',
					'heading'     => esc_html__('Enter keyword text', 'qode-listing')
				),
				array(
                    'type'        => 'textfield',
					'param_name'  => 'listing_search_button_text',
					'heading'     => esc_html__('Enter button text', 'qode-listing')
					)
                ),
		));

	}

	public function render($atts, $content = null) {
		$args = array(
			'listing_search_button_text' => esc_html('Find Listings', 'qode-listing'),
            'listing_search_keyword_text' => esc_html('Type in your keyword', 'qode-listing')
		);
		$params = shortcode_atts($args, $atts);
		$this->resetBasicParams();
		$this->setBasicParams($params);

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-simple-search');

	}
}