<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
/**
 * Class ListingBanner
 * @package QodeListing\Lib\Shortcodes
 */
class ListingBanner implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private static $instance;
	private $basic_params;
	private $base;

	public function __construct() {
		$this->base = 'qode_listing_banner';

		add_action('vc_before_init', array($this, 'vcMap'));


	}

	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Returns current instance of class
	 * @return ListingProjectInfo
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

	/**
	 * Maps shortcode to Visual Composer
	 *
	 * @see vc_map
	 */
	public function vcMap() {
		if(function_exists('vc_map')) {
			vc_map( array(
					'name'                      => esc_html__( 'Listing Banner', 'qode-listing' ),
					'base'                      => $this->getBase(),
					'category'                  => esc_html__( 'by QODE LISTING', 'qode-listing' ),
					'icon'                      => 'icon-wpb-qode-listing-banner extended-custom-icon-qode',
					'allowed_container_element' => 'vc_row',
					'params'                    => array_merge(
						array(
							array(
								'type'			=> 'attach_image',
								'heading'		=> esc_html__('Background Image', 'qode-listing'),
								'param_name'	=> 'background_image'
							)),
							bridge_qode_icon_collections()->getVCParamsArray(array(), '', true),
						array(
							array(
								'type'			=> 'textfield',
								'heading'		=> esc_html__('Number', 'qode-listing'),
								'param_name'	=> 'number'
							),
							array(
								'type'			=> 'textfield',
								'heading'		=> esc_html__('Title', 'qode-listing'),
								'param_name'	=> 'title'
							),
							array(
								'type'			=> 'textfield',
								'heading'		=> esc_html__('Link', 'qode-listing'),
								'param_name'	=> 'link'
							),
							array(
								'type'			=> 'dropdown',
								'heading'		=> esc_html__('Link Target', 'qode-listing'),
								'param_name'	=> 'link_target',
								'value'			=> array(
									esc_html__('Blank', 'qode-listing')	=> '_blank',
									esc_html__('Self', 'qode-listing')	=> '_self'

								),
								'dependency' => array('element' => 'link', 'not_empty' => true)
							),
							array(
								'type'			=> 'dropdown',
								'heading'		=> esc_html__('Skin', 'qode-listing'),
								'param_name'	=> 'skin',
								'value'			=> array(
									esc_html__('Dark', 'qode-listing')	=> 'dark',
									esc_html__('Light', 'qode-listing')	=> 'light'

								)
							)
						)

					)
				)
			);
		}
	}

	/**
	 * Renders shortcodes HTML
	 *
	 * @param $atts array of shortcode params
	 * @param $content string shortcode content
	 * @return string
	 */
	public function render($atts, $content = null) {
		$args = array(
			'background_image'	=> '',
			'number'			=> '',
			'title'				=> '',
			'link'				=> '',
			'link_target'		=> '_blank',
			'skin'				=> ''
		);

		$args	= array_merge($args, bridge_qode_icon_collections()->getShortcodeParams());
		$params = shortcode_atts($args, $atts);

		extract($params);
		$iconPackName   = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($params['icon_pack']);
		$params['icon'] = $params[$iconPackName];

		$params['holder_styles'] = $this->getImageStyle($params);
		$params['holder_icon_classes'] = $this->getIconHolderClasses($params);
		$params['holder_classes'] = $this->getHolderClasses($params);
		$params['icon_style'] = $this->getIconHolderStyle($params);

		return qode_listing_get_shortcode_module_template_part('templates/banner', 'listing-banner', '', $params);
	}

	private function getIconHolderClasses($params) {
		$classes = array('qode-icon-holder', 'qode-icon-circle', 'qode-ls-banner-icon');

		return implode(' ', $classes);
	}
	private function getHolderClasses($params) {
		$classes = array('qode-ls-banner');

		if($params['skin'] != '') {
			$classes[] = 'qode-ls-banner-' . $params['skin'];
		}

		return implode(' ', $classes);
	}
	private function getImageStyle($params) {
		$style = array();

		if($params['background_image']) {

			$image_url = wp_get_attachment_image_src($params['background_image'], 'full');

			$style[] = 'background-image:url(' .$image_url[0] . ')';
		}

		return $style;
	}
	private function getIconHolderStyle($params) {
		$style = array();

		return $style;
	}
}