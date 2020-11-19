<?php
/*
  Plugin Name: Buttons to Edit Next/Previous Post
  Description: This plugin will add easy shortcut buttons to edit next and previous post in admin edit-post page. You can directly navigate to next and previous post.
  Author: Aftab Muni
  Version: 1.2
  Author URI: https://missgossiper.com/
 */

/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope tDEVICE_TYPE it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 */

define('AMM_EDIT_NEXT_PREV_POST_BUTTON_VERSION', '1.0');
define('AMM_EDIT_NEXT_PREV_POST_BUTTON_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMM_EDIT_NEXT_PREV_POST_BUTTON_DONATE_LINK', 'https://www.paypal.me/aftabmuni');

function amm_edit_next_prev_post_button_activate_plugin(){	
}
register_activation_hook(__FILE__, 'amm_edit_next_prev_post_button_activate_plugin');

function amm_edit_next_prev_post_button_deactivate_plugin(){
}
register_deactivation_hook(__FILE__, 'amm_edit_next_prev_post_button_deactivate_plugin');

add_filter('plugin_row_meta', 'amm_edit_next_prev_post_button_plugin_row_meta', 10, 2);
function amm_edit_next_prev_post_button_plugin_row_meta($meta, $file) {
	if ( strpos( $file, basename(__FILE__) ) !== false ) {
		$meta[] = '<a href="'.AMM_EDIT_NEXT_PREV_POST_BUTTON_DONATE_LINK.'" target="_blank">' . esc_html__('Donate', 'AMM_EDIT_NEXT_PREV_POST_BUTTON') . '</a>';
	}
	return $meta;
}

add_action('admin_print_footer_scripts','amm_edit_next_prev_post_button');
function amm_edit_next_prev_post_button(){
    $screen = get_current_screen();
	//echo "<pre>";
	//print_r($screen);exit;
    $supported_types = array('page', 'post');
    if( strpos($screen->parent_file, 'edit.php') !== FALSE && in_array($screen->id, $supported_types) && in_array($screen->post_type, $supported_types) && $screen->action != 'add'){
		//Prev-Next are arranged acc. to post table so switched next to prev and prev to next
		$next_post = get_previous_post();
		$previous_post = get_next_post();
		?>
			<!--<style>body{background-color:red !important}</style>-->
            <script>
				if(window.jQuery) {
					jQuery(document).ready(function($) {
						$(window).load(function() { 
							var is_next_post_available = '<?php echo ($next_post->ID) ? true : false ?>';
							var is_prev_post_available = '<?php echo ($previous_post->ID) ? true : false ?>';
							//alert(is_next_post_available);
							//alert(is_prev_post_available);
							<?php if($screen->is_block_editor){ ?>
								if(is_prev_post_available && is_next_post_available){
									$('.edit-post-header__settings').prepend('<a style="margin-right:10px;" href="<?php echo get_edit_post_link($previous_post->ID) ?>" class="prev-post components-button is-button is-primary is-large">&larr; Previous</a><a  style="margin-right:10px;" href="<?php echo get_edit_post_link($next_post->ID) ?>" class="next-post components-button is-button is-primary is-large">Next &rarr;</a>');
								}else if(is_prev_post_available && !is_next_post_available){
									$('.edit-post-header__settings').prepend('<a style="margin-right:10px;" href="<?php echo get_edit_post_link($previous_post->ID) ?>" class="prev-post components-button is-button is-primary is-large">&larr; Previous</a>');
								}else if(is_next_post_available && !is_prev_post_available){
									$('.edit-post-header__settings').prepend('<a style="margin-right:10px;" href="<?php echo get_edit_post_link($next_post->ID) ?>" class="next-post components-button is-button is-primary is-large">Next &rarr;</a>');
								}
							<?php } else { ?>
								if(is_prev_post_available && is_next_post_available){
									$('.wrap .page-title-action').after('<a style="color: white;background: #0085ba;border: #008EC2;" href="<?php echo get_edit_post_link($previous_post->ID) ?>" class="prev-post page-title-action">&larr; Previous</a><a style="color: white;background: #0085ba;border: #008EC2;" href="<?php echo get_edit_post_link($next_post->ID) ?>" class="next-post page-title-action">Next &rarr;</a>');
								}else if(is_prev_post_available && !is_next_post_available){
									$('.wrap .page-title-action').after('<a style="color: white;background: #0085ba;border: #008EC2;" href="<?php echo get_edit_post_link($previous_post->ID) ?>" class="prev-post page-title-action">&larr; Previous</a>');
								}else if(is_next_post_available && !is_prev_post_available){
									$('.wrap .page-title-action').after('<a style="color: white;background: #0085ba;border: #008EC2;" href="<?php echo get_edit_post_link($next_post->ID) ?>" class="next-post page-title-action">Next &rarr;</a>');
								}
							<?php } ?>							
						});
					});
				}
            </script>
        <?php
    }
}
?>