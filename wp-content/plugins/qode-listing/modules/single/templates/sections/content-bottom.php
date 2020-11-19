<?php
$option = bridge_qode_get_meta_field_intersect('listing_content_bottom');
$flag = $option === 'yes' ? true : false;
if($flag && is_active_sidebar('qode-lst-single-widget-bottom-area')){

    dynamic_sidebar('qode-lst-single-widget-bottom-area');

}