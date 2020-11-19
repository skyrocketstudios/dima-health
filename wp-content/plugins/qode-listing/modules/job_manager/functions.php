<?php

if(!function_exists('qode_listing_override_job_manager_template_path')){

    function qode_listing_override_job_manager_template_path( $template, $template_name, $template_path ) {
        $overridden_templates = apply_filters('qode_listing_override_template_filter', array(
            'account-signin.php',
            'job-dashboard.php',
            'job-submit.php'
        ));
        if(in_array($template_name, $overridden_templates)) {
            $template_path = QODE_LISTING_ABS_PATH . '/modules/job_manager/templates';
            if ( file_exists( trailingslashit( $template_path ) . $template_name ) ) {
                $template = trailingslashit( $template_path ) . $template_name;
            }
        }

        return $template;
    }
    add_filter( 'job_manager_locate_template', 'qode_listing_override_job_manager_template_path', 10, 3);
}