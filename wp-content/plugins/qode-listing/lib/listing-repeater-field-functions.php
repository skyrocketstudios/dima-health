<?php
use QodeListing\Lib;
if(!function_exists('qode_listing_get_custom_field_html')){
	/**
	 * Ajax call
	 * Function that calls render() method depending on the type of custom fields
	 */
	function qode_listing_get_custom_field_html(){
		if(isset($_POST['type'])){
			switch($_POST['type']){
				case 'text':
					$id = 'text-'.rand();
					$field = new Lib\CustomFieldText('','',$id);
					break;
				case 'textarea':
					$id = 'textarea-'.rand();
					$field = new Lib\CustomFieldTextArea('','',$id);
					break;
				case 'checkbox':
					$id = 'checkbox-'.rand();
					$field = new Lib\CustomFieldCheckBox('',$id);
					break;
				case 'select':
					$id = 'select-'.rand();
					$field = new Lib\CustomFieldSelect('' , '' , array(), array(), $id);
					break;
				default:
			}
		}
		ob_start();
		$field->render();
		$html = ob_get_clean();

		$return_object  =  array(
			'html' => $html
		);
		echo json_encode($return_object);exit;
	}
	add_action('wp_ajax_qode_listing_get_custom_field_html','qode_listing_get_custom_field_html');
}

if(!function_exists('qode_listing_get_option_field_html')){

	function qode_listing_get_option_field_html(){

		if(isset($_POST['parentId'])){
			$id = $_POST['parentId'];
		}else{
			$id = '';
		}
		$field = new Lib\CustomOptionField('', '', $id);
		ob_start();
		$field->render();
		$html = ob_get_clean();
		$return_array = array(
			'html' => $html
		);
		echo json_encode($return_array);exit;

	}
	add_action('wp_ajax_qode_listing_get_option_field_html','qode_listing_get_option_field_html');
}

if(!function_exists('qode_listing_get_amenity_field_html')){

	function qode_listing_get_amenity_field_html(){

		$field = new Lib\CustomAmenityCreator();
		ob_start();
		$field->render();
		$html = ob_get_clean();
		$return_array = array(
			'html' => $html
		);
		echo json_encode($return_array);exit;

	}
	add_action('wp_ajax_qode_listing_get_amenity_field_html','qode_listing_get_amenity_field_html');
}

if(!function_exists('qode_listing_add_repeater_option_button')){
	/**
	 * Generate html for repeater button
	 */
	function qode_listing_add_repeater_option_button(){

		$html = '';
		$html .= '<a class="qode-option-repeater-button" href="javascript:void(0)">';
		$html .= esc_html__('Add new', 'qode-listing');
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_add_repeater_option_trigger', 'qode_listing_add_repeater_option_button');
}

if(!function_exists('qode_listing_delete_repeater_option_button')){
	/**
	 * Generate html for repeater button
	 */
	function qode_listing_delete_repeater_option_button(){

		$html = '';
		$html .= '<a href="javascript:void(0)" class="qode-option-repeater-close-button">';
		$html .= esc_html__('Remove', 'qode-listing');
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_delete_repeater_option_trigger', 'qode_listing_delete_repeater_option_button');
}

if(!function_exists('qode_listing_taxonomy_delete_custom_row')){
	/**
	 * Generate html for row close button
	 */
	function qode_listing_taxonomy_delete_custom_row(){
		$html = '';
		$html .= '<a href="javascript:void(0)" class="qode-custom-row-close-button">';
		$html .= '<span>'.esc_html('x').'</span>';
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_delete_custom_row', 'qode_listing_taxonomy_delete_custom_row');
}

if(!function_exists('qode_listing_expand_custom_row_trigger')){
	function qode_listing_expand_custom_row_trigger(){
		$html = '';
		$html .= '<a href="javascript:void(0)" class="qode-custom-row-expand-button">';
		$html .= '<span class="qode-custom-row-opener qode-custom-row-open">'.esc_html('-').'</span>';
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_expand_custom_row', 'qode_listing_expand_custom_row_trigger');
}


if(!function_exists('qode_listing_taxonomy_add_amenity')){
	/**
	 * Generate html for amenity add button
	 */
	function qode_listing_taxonomy_add_amenity(){
		$html = '';
		$html .= '<a href="javascript:void(0)" class="qode-custom-amenity-add-button">';
		$html .= esc_html__('Add Amenity', 'qode-listing');
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_add_amenity_trigger', 'qode_listing_taxonomy_add_amenity');
}

if(!function_exists('qode_listing_taxonomy_delete_amenity')){
	/**
	 * Generate html for amenity close button
	 */
	function qode_listing_taxonomy_delete_amenity(){
		$html = '';
		$html .= '<a href="javascript:void(0)" class="qode-custom-amenity-close-button">';
		$html .= esc_html__('Delete Amenity', 'qode-listing');
		$html .= '</a>';
		echo $html;
	}
	add_action('qode_listing_action_delete_amenity_trigger', 'qode_listing_taxonomy_delete_amenity');
}