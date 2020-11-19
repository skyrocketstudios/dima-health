<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
/**
 * Class ListingTestimonials
 * @package QodeListing\Lib\Shortcodes
 */
class ListingTestimonials implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private static $instance;
	private $basic_params;
	private $base;

	public function __construct() {
		$this->base = 'qode_listing_testimonials';

		add_action('vc_before_init', array($this, 'vcMap'));

		//Listing project id filter
		add_filter( 'vc_autocomplete_qode_listing_testimonials_category_callback', array( &$this, 'testimonialsCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

		//Listing project id render
		add_filter( 'vc_autocomplete_qode_listing_testimonials_category_render', array( &$this, 'testimonialsCategoryAutocompleteRender', ), 10, 1 ); // Render exact listing. Must return an array (label,value)
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
					'name'                      => esc_html__( 'Listing Testimonials', 'qode-listing' ),
					'base'                      => $this->getBase(),
					'category'                  => esc_html__( 'by QODE LISTING', 'qode-listing' ),
					'icon'                      => 'icon-wpb-qode-listing-testimonials extended-custom-icon-qode',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'			=> 'autocomplete',
							'heading'		=> esc_html__('Category', 'qode-listing'),
							'param_name'	=> 'category',
							'description'	=> esc_html__('Category Slug (leave empty for all)', 'qode-listing')
						),
						array(
							'type'			=> 'textfield',
							'heading'		=> esc_html__('Number', 'qode-listing'),
							'param_name'	=> 'number',
							'description'	=> esc_html__('Number of Testimonials', 'qode-listing')
						),
						array(
							'type'			=> 'dropdown',
							'heading'		=> esc_html__('Order By', 'qode-listing'),
							'param_name'	=> 'order_by',
							'value'			=> array(
								esc_html__('Date', 'qode-listing')		=> 'date',
								esc_html__('Title', 'qode-listing')		=> 'title',
								esc_html__('Random', 'qode-listing')	=> 'rand'
							)
						),
						array(
							'type'			=> 'dropdown',
							'heading'		=> esc_html__('Order Type', 'qode-listing'),
							'param_name'	=> 'order',
							'value'			=> array(
								esc_html__('Descending', 'qode-listing')	=> 'DESC',
								esc_html__('Ascending', 'qode-listing')		=> 'ASC'

							),
							'dependency' => array('element' => 'order_by', 'value' => array('title', 'date'))
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
			'category'	=> '',
			'number'	=> '-1',
			'order_by'	=> 'date',
			'order'		=> 'DESC'
		);

		$params = shortcode_atts($args, $atts);
		$this->resetBasicParams();
		$this->setBasicParams($params);
		extract($params);

		$query_results = new \WP_Query($this->getQueryArray());

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-testimonials', '', array('query_results' => $query_results));
	}


	public function getQueryArray(){

		$query_params = array(
			'post_type' => 'testimonials',
			'posts_per_page' => $this->getBasicParamByKey('number'),
			'order_by' => $this->getBasicParamByKey('order_by'),
			'order' => $this->getBasicParamByKey('order')
		);

		$testimonials_category = $this->getBasicParamByKey('category');
		if($testimonials_category !== ''){
			$query_params['testimonials_category'] = $testimonials_category;
		}

		return $query_params;

	}

	/**
	 * Filter testimonials categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function testimonialsCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS testimonials_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'testimonials_category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['testimonials_category_title'] ) > 0 ) ? esc_html__( 'Category', 'qode-listing' ) . ': ' . $value['testimonials_category_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;

	}

	/**
	 * Find testimonials category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function testimonialsCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio category
			$testimonials_category = get_term_by( 'slug', $query, 'testimonials_category' );
			if ( is_object( $testimonials_category ) ) {

				$testimonials_category_slug  = $testimonials_category->slug;
				$testimonials_category_title = $testimonials_category->name;

				$testimonials_category_title_display = '';
				if ( ! empty( $testimonials_category_title ) ) {
					$testimonials_category_title_display = esc_html__( 'Category', 'qode-listing' ) . ': ' . $testimonials_category_title;
				}

				$data          = array();
				$data['value'] = $testimonials_category_slug;
				$data['label'] = $testimonials_category_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}