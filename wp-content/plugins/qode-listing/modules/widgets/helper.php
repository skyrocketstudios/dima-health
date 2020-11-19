<?php
if(!function_exists('qode_listing_register_widget')){
    
    function qode_listing_register_widget(){
        
        register_widget('QodeListingWidget');
        
    }
    
    add_action('widgets_init', 'qode_listing_register_widget');
    
}

if(!function_exists('qode_register_listing_single_widget_bottom_area')){


    function qode_register_listing_single_widget_bottom_area(){


        register_sidebar(
            array(
                'name'          => esc_html__('Listing Single Bottom Area', 'qode-core'),
                'id'            => 'qode-lst-single-widget-bottom-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s qode-lst-single-widget-bottom-area">',
                'after_widget'  => '</div>',
                'description'   => esc_html__('Widgets added here will appear on the bottom of listing single pages', 'qode-core')
            )
        );

    }

    add_action('widgets_init', 'qode_register_listing_single_widget_bottom_area');

}