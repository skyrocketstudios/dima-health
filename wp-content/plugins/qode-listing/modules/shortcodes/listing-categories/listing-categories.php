<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;

/**
 * Class ListingList
 * @package QodeListing\Lib\Shortcodes
 */
class ListingCategories implements ShortcodeInterface {

	private static $instance;
	private $base;
	private $basic_params;
	private $types;

	public function __construct() {

		$this->base = 'qode_listing_cats';
		self::$instance = $this;

		add_action('vc_before_init', array($this, 'generateListingTypeArray'));
		add_action('vc_before_init', array($this, 'vcMap'));

		//Listing category filter
		add_filter( 'vc_autocomplete_qode_listing_cats_category_callback', array( &$this, 'listingCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

		//Listing category render
		add_filter( 'vc_autocomplete_qode_listing_cats_category_render', array( &$this, 'listingCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array
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

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Listing Categories', 'qode-listing'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-qode-listing-categories extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'param_name'  => 'listing_type',
					'heading'     => esc_html__('Listing Type', 'qode-listing'),
					'value'       => $this->types,
					'admin_label' => true					
				),
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'category',
					'heading'     => esc_html__( 'Show Only Listed Categories', 'qode-core' ),
					'settings'    => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true
					),
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'listing_cat_number',
					'heading'     => esc_html__('Number of items', 'qode-listing'),
					'value'       => '',
					'admin_label' => true
				)
			)
		));

	}

	public function render($atts, $content = null) {
		$args = array(
			'listing_cat_number' => '',
			'listing_type' => '',
			'category' => ''
		);
		$params = shortcode_atts($args, $atts);
		extract($params);
		$this->resetBasicParams();
		$this->setBasicParams($params);

		$query_params = array(
			'number'     => $listing_cat_number
		);

		if($listing_type !== ''){
			$query_params['meta_key']  = 'listing_type';
			$query_params['meta_value']  = $listing_type;
		}else{
			if (!empty($params['category'])) {

				$query_params['include'] = $this->getSelectedCategories();
				$query_params['include_params'] = $params['category'];
			}
		}

		$terms = qode_listing_get_listing_categories($query_params);
		$this->setQueryResults($terms);

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-categories');

	}

	public function getSelectedCategories(){

		$selected_cats = explode(',',$this->getBasicParamByKey('category'));
		$selected_cats_array = array();

		if(is_array($selected_cats) && count($selected_cats)){
			foreach ($selected_cats as $cat_slug){
				$cat =  get_term_by( 'slug', $cat_slug, 'job_listing_category');
				if($cat){
					$selected_cats_array[] = $cat->term_id;
				}

			}
		}

		return $selected_cats_array;

	}

	public function getItemClasses($gallery_size, $gallery_type){

		$classes = array();

		if($gallery_size && $gallery_size !== ''){
			$classes[] = 'qode-ls-gallery-'.$gallery_size;
		}else{
			$classes[] = 'qode-ls-gallery-square-small';
		}

		if($gallery_type && $gallery_type !== ''){
			$classes[] = 'qode-ls-gallery-'.$gallery_type.'-type';
		}
		else{
			$classes[] = 'qode-ls-gallery-standard-type';
		}

		return implode($classes , ' ');
	}


	public function getImageUrl($term_id){
		$image_url_style = '';
		$image_id = get_term_meta($term_id, 'featured_image', true);
		if($image_id && $image_id !== ''){
			$image_url = wp_get_attachment_image_url( $image_id, 'full');
			$image_url_style = 'background-image: url('.esc_url($image_url).')';
		}
		return $image_url_style;
	}

	/**
	 * Filter listing categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function listingCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS listing_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'job_listing_category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['listing_category_title'] ) > 0 ) ? esc_html__( 'Category', 'qode-core' ) . ': ' . $value['listing_category_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}

	/**
	 * Find listing category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function listingCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio category
			$listing_category = get_term_by( 'slug', $query, 'job_listing_category' );
			if ( is_object( $listing_category ) ) {

				$listing_category_slug = $listing_category->slug;
				$listing_category_title = $listing_category->name;

				$listing_category_title_display = '';
				if ( ! empty( $listing_category_title ) ) {
					$listing_category_title_display = esc_html__( 'Category', 'qode-core' ) . ': ' . $listing_category_title;
				}

				$data          = array();
				$data['value'] = $listing_category_slug;
				$data['label'] = $listing_category_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}

}