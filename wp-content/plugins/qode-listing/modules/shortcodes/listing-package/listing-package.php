<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
use QodeListing\Lib\Core;

/**
 * Class ListingPackage that represents wc paid listing packages
 * @package QodeListing\Lib\Shortcodes
 */
class ListingPackage implements ShortcodeInterface {

	private static $instance;
	private $base;
	private $basic_params;
	private $packages;

	public function __construct() {

		$this->base = 'qode_listing_package';
		self::$instance = $this;

		add_action('vc_before_init', array($this, 'vcMap'));
		$this->setPackages();

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
	
	public function setPackages(){
	    
	    $this->packages = qode_listing_get_listing_packages();
	    
	}
	
	public function getPackages(){
		
	    return $this->packages;
	    
	}

	public function vcMap() {

		vc_map(array(
			'name'                      => esc_html__('Listing Packages', 'qode-listing'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-qode-listing-packages extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'listing_package_title',
					'heading'     => esc_html__('Package Button Text', 'qode-listing'),
					'value'       => '',
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'listing_package_link',
					'heading'     => esc_html__('Package Button Link', 'qode-listing'),
					'value'       => '',
					'admin_label' => true
				),
			)
		    ));

	}

	public function render($atts, $content = null) {

			$args = array(
				'listing_package_title' => '',
				'listing_package_link' => ''
			);
			$params = shortcode_atts($args, $atts);
			$this->resetBasicParams();
			$this->setBasicParams($params);

			return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-package');

	}
}