<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;

/**
 * Class ListingList
 * @package QodeListing\Lib\Shortcodes
 */
class ListingRegions implements ShortcodeInterface {

	private static $instance;
	private $base;
	private $basic_params;
	private $types;

	public function __construct() {

		$this->base = 'qode_listing_regions';
		self::$instance = $this;

		add_action('vc_before_init', array($this, 'generateListingTypeArray'));
		add_action('vc_before_init', array($this, 'vcMap'));

		//Listing region filter
		add_filter( 'vc_autocomplete_qode_listing_regions_region_callback', array( &$this, 'listingRegionAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

		//Listing region render
		add_filter( 'vc_autocomplete_qode_listing_regions_region_render', array( &$this, 'listingRegionAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array
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
			'name'                      => esc_html__('Listing Regions', 'qode-listing'),
			'base'                      => $this->base,
			'category'                 	=> esc_html__('by QODE LISTING', 'qode-listing'),
			'icon'                      => 'icon-wpb-qode-listing-regions extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'region',
					'heading'     => esc_html__( 'Show Only Listed Regions', 'qode-core' ),
					'settings'    => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true
					),
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'listing_region_number',
					'heading'     => esc_html__('Number of items', 'qode-listing'),
					'value'       => '',
					'admin_label' => true
				)
			)
		));

	}

	public function render($atts, $content = null) {
		$args = array(
			'listing_region_number' => '',
			'region' => ''
		);
		$params = shortcode_atts($args, $atts);
		extract($params);
		$this->resetBasicParams();
		$this->setBasicParams($params);

		$query_params = array(
			'number'     => $listing_region_number
		);

		if (!empty($params['region'])) {

			$query_params['include'] = $this->getSelectedRegions();
			$query_params['include_params'] = $params['region'];
		}

		$terms = qode_listing_get_listing_regions($query_params);
		$this->setQueryResults($terms);

		return qode_listing_get_shortcode_module_template_part('templates/holder', 'listing-regions');

	}

	public function getSelectedRegions(){

		$selected_cats = explode(',',$this->getBasicParamByKey('region'));
		$selected_cats_array = array();

		if(is_array($selected_cats) && count($selected_cats)){
			foreach ($selected_cats as $cat_slug){
				$cat =  get_term_by( 'slug', $cat_slug, 'job_listing_region');
				if($cat){
					$selected_cats_array[] = $cat->term_id;
				}

			}
		}

		return $selected_cats_array;

	}

	public function getItemClasses($gallery_size, $gallery_type){

		$classes = array();


			return implode($classes , ' ');
	}


	/**
	 * Filter listing regions
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function listingRegionAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS listing_region_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'job_listing_region' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['listing_region_title'] ) > 0 ) ? esc_html__( 'Region', 'qode-listing' ) . ': ' . $value['listing_region_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}

	/**
	 * Find listing region by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function listingRegionAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio region
			$listing_region = get_term_by( 'slug', $query, 'job_listing_region' );
			if ( is_object( $listing_region ) ) {

				$listing_region_slug = $listing_region->slug;
				$listing_region_title = $listing_region->name;

				$listing_region_title_display = '';
				if ( ! empty( $listing_region_title ) ) {
					$listing_region_title_display = esc_html__( 'Region', 'qode-listing' ) . ': ' . $listing_region_title;
				}

				$data          = array();
				$data['value'] = $listing_region_slug;
				$data['label'] = $listing_region_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}

}