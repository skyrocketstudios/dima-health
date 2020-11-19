<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
/**
 * Class ListingTestimonials
 * @package QodeListing\Lib\Shortcodes
 */
class ListingBlogList implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private static $instance;
	private $basic_params;
	private $base;

	public function __construct() {
		$this->base = 'qode_listing_blog_list';

		add_action('vc_before_init', array($this, 'vcMap'));

		//Category filter
		add_filter( 'vc_autocomplete_qode_listing_blog_list_category_callback', array( &$this, 'blogCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

		//Category render
		add_filter( 'vc_autocomplete_listing_blog_list_category_render', array( &$this, 'blogCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array
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
					'name'                      => esc_html__( 'Listing Blog List', 'qode-listing' ),
					'base'                      => $this->getBase(),
					'category'                  => esc_html__( 'by QODE LISTING', 'qode-listing' ),
					'icon'                      => 'icon-wpb-qode-listing-blog-list extended-custom-icon-qode',
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
							'description'	=> esc_html__('Number of posts', 'qode-listing')
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
						),
						array(
							'type'			=> 'textfield',
							'heading'		=> esc_html__('Text Legth', 'qode-listing'),
							'param_name'	=> 'text_length'
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'title_tag',
							'heading'    => esc_html__( 'Title Tag', 'qode-listing' ),
							'value'      => array_flip( qode_listing_get_title_tag( true ) )
						),

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
			'category'		=> '',
			'number'		=> '-1',
			'order_by'		=> 'date',
			'order'			=> 'DESC',
			'text_length'	=> '150',
			'title_tag'     => 'h5'
		);

		$params = shortcode_atts($args, $atts);
		$this->resetBasicParams();
		$this->setBasicParams($params);
		extract($params);

		$query_results = new \WP_Query($this->getQueryArray());

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-blog-list', '', array('query_results' => $query_results, 'params' => $params));
	}


	public function getQueryArray(){

		$query_params = array(
			'posts_per_page' => $this->getBasicParamByKey('number'),
			'order_by' => $this->getBasicParamByKey('order_by'),
			'order' => $this->getBasicParamByKey('order')
		);

		$category = $this->getBasicParamByKey('category');
		if($category !== ''){
			$query_params['category_name'] = $category;
		}

		return $query_params;

	}

	/**
	 * Filter blog categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function blogCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['category_title'] ) > 0 ) ? esc_html__( 'Category', 'qode-listing' ) . ': ' . $value['category_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}

	/**
	 * Find blog category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function blogCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio category
			$category = get_term_by( 'slug', $query, 'category' );
			if ( is_object( $category ) ) {

				$category_slug = $category->slug;
				$category_title = $category->name;

				$category_title_display = '';
				if ( ! empty( $category_title ) ) {
					$category_title_display = esc_html__( 'Category', 'qode-listing' ) . ': ' . $category_title;
				}

				$data          = array();
				$data['value'] = $category_slug;
				$data['label'] = $category_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}