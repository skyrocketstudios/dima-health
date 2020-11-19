<?php
namespace QodeListing\Maps;
class MapGlobalVars{

	private $id;
	private $type;
	private $query;
	private $init_multiple_map;
	private $multiple_vars;

	public function __construct($type, $id = '', $query = '' ,$init_multiple_map = false) {

		$this->type = $type;
		$this->id = $id;
		$this->query = $query;
		$this->init_multiple_map = $init_multiple_map;
		$this->multiple_vars['addresses'] = array();

		if($this->type === 'single'){
			add_action('wp_enqueue_scripts', array($this, 'generateSingleGlobalVar'), 20);
		}
		if($this->type === 'multiple'){
			$this->setMultipleVars();

			if($this->init_multiple_map){
				add_action('wp_footer', array($this, 'setMultipleGlobalVars'));
			}
		}
	}

	public function generateSingleGlobalVar(){

		$single_map_variables = array();

		if($this->id !== ''){
			$single_map_variables['currentListing'] = $this->generateListingMapParams($this->id);
		}

		$single_map_variables = apply_filters('qode_listing_filter_js_single_map_variables', $single_map_variables);

		wp_localize_script('bridge-default', 'qodeSingleMapVars', array(
			'single' => $single_map_variables
		));

	}

	public function setMultipleGlobalVars(){

		$multiple_map_variables = $this->getMultipleVars();

		wp_localize_script('bridge-default', 'qodeMultipleMapVars', array(
			'multiple' => $multiple_map_variables
		));

	}

	public function setMultipleVars(){

		if($this->query !== ''){
			if($this->query->have_posts()){
				while($this->query->have_posts()){
					$this->query->the_post();
					$this->multiple_vars['addresses'][] = $this->generateListingMapParams(get_the_ID());
				}
			}
		}

	}

	public function getMultipleVars(){
		return $this->multiple_vars;
	}

	private function generateListingMapParams($listing_item_id){

		$listing_map_params = array();

		//get listing image
		$image_id = get_post_thumbnail_id( $listing_item_id );
		$image = wp_get_attachment_image_src( $image_id );

		//Get item type
		$listing_types = wp_get_post_terms($listing_item_id, 'job_listing_type');

		$listing_type_id = false;
		if(is_array($listing_types) && count($listing_types)){
			$listing_type_id = $listing_types[0]->term_id;
		}

		//take marker pin
		$marker_pin_icon = $marker_pin_icon_pack = '';

		$categories = wp_get_post_terms($listing_item_id, 'job_listing_category');

		if(is_array($categories) && count($categories)){

			$marker_pin_icon_pack = get_term_meta( $categories[0]->term_id, 'icon_pack', true );
			//take category icon
			if($marker_pin_icon_pack !== ''){

				$param = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($marker_pin_icon_pack);
				$marker_pin_icon = get_term_meta( $categories[0]->term_id, $param, true );

			}

		}

		$marker_pin = '';
		if($marker_pin_icon !== '' && $marker_pin_icon_pack !== ''){
			$marker_pin = bridge_qode_icon_collections()->getIconHTML( $marker_pin_icon, $marker_pin_icon_pack );
		}

		//get address params
		$address_array = qode_listing_get_address_params($listing_item_id);

		//Get item location
		if($address_array['address'] === '' && $address_array['address_lat'] === '' && $address_array['address_long'] === ''){
			$listing_map_params['location'] = null;
		}else{
			$listing_map_params['location'] = array(
				'address' => $address_array['address'],
				'latitude' => $address_array['address_lat'],
				'longitude' => $address_array['address_long']
			);
		}


		$listing_map_params['title'] = get_the_title($listing_item_id);

        if (isset($listing_type_id) && $listing_type_id) {
            $listing_post = get_post($listing_type_id);
            $listing_item_type_name = null;
            if ($listing_post && $listing_post !== null) {
                $listing_item_type_name = $listing_type_id !== '' ? get_post($listing_type_id)->post_name : null;
            }
            $listing_map_params['listingType'] = $listing_item_type_name;
        }

		$listing_map_params['markerPin'] = $marker_pin;
		$listing_map_params['featuredImage'] = $image;
		$listing_map_params['itemUrl'] = get_the_permalink($listing_item_id);

		return $listing_map_params;

	}

}