<?php

class QodeListingWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'qode_listing_widget',
			esc_html__('Qode Listing Widget', 'qode-listing'),
			array( 'description' => esc_html__( 'Add listing element to widget areas', 'qode-listing'))
		);

		$this->setParams();        
	}

	/**
	 * Sets widget options
	 */
	protected function setParams() {
		$this->params =array(
		    array(
			'type'    => 'dropdown',
			'name'    => 'listing_type',
			'title'   => esc_html__('Type', 'qode-listing'),
			'options' => array()
		    ),
		    array(
			'type'    => 'dropdown',
			'name'    => 'listing_category',
			'title'   => esc_html__('Category', 'qode-listing'),
			'options' => array(
			)
		    ),
		    array(
			'type'    => 'textfield',
			'name'    => 'listing_list_number',
			'title'   => esc_html__('Number of Items', 'qode-listing')
		    )
            
		);
	}

	/**
	 * Generates widget's HTML
	 *
	 * @param array $args args from widget area
	 * @param array $instance widget's options
	 */
	public function widget($args, $instance) {
		$params = '';
        
		if (!is_array($instance)) { $instance = array(); }

		// Default values
		if (!isset($instance['listing_list_number'])) { 
		    $instance['listing_list_number'] = 3;
		}
		   
		    

		$instance['listing_list_columns'] = 'one';
		foreach ($instance as $key => $value) {
			if($value !== '') {				
				$params .= $key .'='. esc_attr($value). ' ';
			}
		}
		
		echo '<div class="widget qode-listing-widget">';
			echo do_shortcode("[qode_listing_list $params]"); // XSS OK
		echo '</div>';
	}
    
}