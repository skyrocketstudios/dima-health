<?php
namespace QodeListing\Lib\Shortcodes;

use QodeListing\Lib\Shortcodes\ShortcodeInterface;
/**
 * Class ListingProjectInfo
 * @package QodeListing\Lib\Shortcodes
 */
class ListingProjectInfo implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private static $instance;
	private $basic_params;
	private $base;

	public function __construct() {
		$this->base = 'qode_listing_project_info';

		add_action('vc_before_init', array($this, 'vcMap'));

		//Listing project id filter
		add_filter( 'vc_autocomplete_qode_listing_project_info_project_id_callback', array( &$this, 'listingIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

		//Listing project id render
		add_filter( 'vc_autocomplete_qode_listing_project_info_project_id_render', array( &$this, 'listingIdAutocompleteRender', ), 10, 1 ); // Render exact listing. Must return an array (label,value)
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
					'name'                      => esc_html__( 'Listing Project Info', 'qode-listing' ),
					'base'                      => $this->getBase(),
					'category'                  => esc_html__( 'by QODE LISTING', 'qode-listing' ),
					'icon'                      => 'icon-wpb-qode-listing-project-info extended-custom-icon-qode',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'       => 'autocomplete',
							'param_name' => 'project_id',
							'heading'    => esc_html__( 'Selected Project', 'qode-listing' ),
							'settings'   => array(
								'sortable'      => true,
								'unique_values' => true
							),
							'save_always' => true,
							'description' => esc_html__( 'If you left this field empty then project ID will be of the current page', 'qode-listing' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'project_info_type',
							'heading'     => esc_html__( 'Project Info Type', 'qode-listing' ),
							'value'       => array(
								esc_html__( 'Title', 'qode-listing' )    => 'title',
								esc_html__( 'Category', 'qode-listing' ) => 'category',
								esc_html__( 'Tag', 'qode-listing' )      => 'tag',
								esc_html__( 'Author', 'qode-listing' )   => 'author',
								esc_html__( 'Date', 'qode-listing' )     => 'date'
							),
							'save_always' => true,
							'admin_label' => true
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'project_info_title_type_tag',
							'heading'     => esc_html__( 'Project Info Title Type Tag', 'qode-listing' ),
							'value'       => array_flip(qode_listing_get_title_tag(true, array('p' => 'p'))),
							'description' => esc_html__( 'Set title tag for project title element', 'qode-listing' ),
							'dependency'  => array( 'element' => 'project_info_type', 'value' => array( 'title' ) )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'project_info_title',
							'heading'     => esc_html__( 'Project Info Title', 'qode-listing' ),
							'description' => esc_html__( 'Add project info title before project info element/s', 'qode-listing' )
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'project_info_title_tag',
							'heading'    => esc_html__( 'Project Info Title Tag', 'qode-listing' ),
							'value'      => array_flip(qode_listing_get_title_tag(true, array('p' => 'p'))),
							'save_always' => true,
							'dependency' => array( 'element' => 'project_info_title', 'not_empty' => true )
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
			'project_id'                  => '',
			'project_info_type'           => 'title',
			'project_info_title_type_tag' => 'h4',
			'project_info_title'          => '',
			'project_info_title_tag'      => 'h4'
		);

		$params = shortcode_atts($args, $atts);
		$this->resetBasicParams();
		$this->setBasicParams($params);
		extract($params);

		$project_id  = $params['project_id'] = !empty($params['project_id']) ? $params['project_id'] : get_the_ID();

		$html = '';

		$html .= '<div class="qode-listing-project-info">';

		if(!empty($project_info_title)) {
			$html .= '<'.esc_attr($project_info_title_tag).' class="qode-lpi-label">'.esc_html($project_info_title).'</'.esc_attr($project_info_title_tag).'>';
		}

		switch ($project_info_type) {
			case 'title':
				$html .= $this->getItemTitleHtml($params);
				break;
			case 'category':
				$html .= $this->getItemCategoryHtml($params);
				break;
			case 'tag':
				$html .= $this->getItemTagHtml($params);
				break;
			case 'author':
				$html .= $this->getItemAuthorHtml($params);
				break;
			case 'date':
				$html .= $this->getItemDateHtml($params);
				break;
			default:
				$html .= $this->getItemTitleHtml($params);
				break;
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Generates listing project title html based on id
	 *
	 * @param $params
	 *
	 * @return html
	 */
	public function getItemTitleHtml($params){
		$html = '';
		$project_id = $params['project_id'];
		$title = get_the_title($project_id);
		$project_info_title_type_tag = $params['project_info_title_type_tag'];

		if(!empty($title)) {
			$html = '<'.esc_attr($project_info_title_type_tag).' itemprop="name" class="qode-lpi-title entry-title">';
			$html .= '<a itemprop="url" href="'.esc_url(get_the_permalink($project_id)).'">'.esc_html($title).'</a>';
			$html .= '</'.esc_attr($project_info_title_type_tag).'>';
		}

		return $html;
	}

	/**
	 * Generates listing project categories html based on id
	 *
	 * @param $params
	 *
	 * @return html
	 */
	public function getItemCategoryHtml($params){

		$html = qode_listing_get_listing_categories_by_listing_id($params['project_id']);
		return $html;
	}

	/**
	 * Generates portfolio project tags html based on id
	 *
	 * @param $params
	 *
	 * @return html
	 */
	public function getItemTagHtml($params){
		$html = '';
		$project_id = $params['project_id'];
		$tags = wp_get_post_terms($project_id, 'job_listing_tag');

		if(!empty($tags)) {
			$html = '<div class="qode-lpi-tag">';
			foreach ($tags as $tag) {
				$html .= '<a itemprop="url" class="qode-lpi-tag-item" href="'.esc_url(get_term_link($tag->term_id)).'">'.esc_html($tag->name).'</a>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	/**
	 * Generates listing project authors html based on id
	 *
	 * @param $params
	 *
	 * @return html
	 */
	public function getItemAuthorHtml($params){
		$html = '';
		$project_id = $params['project_id'];
		$project_post = get_post($project_id);
		$autor_id = $project_post->post_author;
		$author = get_the_author_meta('display_name', $autor_id);

		if(!empty($author)) {
			$html = '<div class="qode-lpi-author">'.esc_html($author).'</div>';
		}

		return $html;
	}

	/**
	 * Generates listing project date html based on id
	 *
	 * @param $params
	 *
	 * @return html
	 */
	public function getItemDateHtml($params){
		$html = '';
		$project_id = $params['project_id'];
		$date = get_the_time(get_option('date_format'), $project_id);

		if(!empty($date)) {
			$html = '<div class="qode-lpi-date">'.esc_html($date).'</div>';
		}

		return $html;
	}

	/**
	 * Filter listings by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function listingIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$listing_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts}
					WHERE post_type = 'job_listing' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $listing_id > 0 ? $listing_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $value['id'];
				$data['label'] = esc_html__( 'Id', 'qode-listing' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'qode-listing' ) . ': ' . $value['title'] : '' );
				$results[] = $data;
			}
		}

		return $results;
	}

	/**
	 * Find listing by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function listingIdAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get listing
			$listing = get_post( (int) $query );
			if ( ! is_wp_error( $listing ) ) {

				$listing_id = $listing->ID;
				$listing_title = $listing->post_title;

				$listing_title_display = '';
				if ( ! empty( $listing_title ) ) {
					$listing_title_display = ' - ' . esc_html__( 'Title', 'qode-listing' ) . ': ' . $listing_title;
				}

				$listing_id_display = esc_html__( 'Id', 'qode-listing' ) . ': ' . $listing_id;

				$data          = array();
				$data['value'] = $listing_id;
				$data['label'] = $listing_id_display . $listing_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}