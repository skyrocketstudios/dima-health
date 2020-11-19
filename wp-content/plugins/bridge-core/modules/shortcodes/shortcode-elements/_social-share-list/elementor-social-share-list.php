<?php

class BridgeCoreElementorSocialShareList extends \Elementor\Widget_Base{
    public function get_name() {
        return 'bridge_social_share_list';
    }

    public function get_title() {
        return esc_html__( 'Social Share List', 'bridge-core' );
    }

    public function get_icon() {
        return 'bridge-elementor-custom-icon bridge-elementor-social-share-list';
    }

    public function get_categories() {
        return [ 'qode' ];
    }

    protected function _register_controls() {

    }

    protected function render(){
        $params = $this->get_settings_for_display();

        echo bridge_core_get_shortcode_template_part('templates/social-share-list', '_social-share-list', '', $params);
    }

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new BridgeCoreElementorSocialShareList() );