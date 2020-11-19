<?php
/**
 * Author: Ankit Tiwari
 * Author uri: http://artofcoding.in
 * Plugin uri: http://artofcoding.in/multiple-post-images
 * Plugin name: AOC Multiple Post Images
 * Description: This plugin allows you to upload multiple images for a post.
 * Version: 0.4
 */
function aoc_register_meta_boxes()
{

    add_meta_box('aoc-multi-image', 'Select images to upload', 'aoc_display_callback', get_post_types());
}

add_action('add_meta_boxes', 'aoc_register_meta_boxes');

function aoc_display_callback($post)
{
    $aoc_saved_images = get_post_meta($post->ID, 'aoc_multiple_images', true);
    ?>

    <div class="aoc-img-container">
        <?php wp_nonce_field('aoc_save_images', 'aoc_save_img_nonce') ?>
        <?php if ($aoc_saved_images): foreach ($aoc_saved_images as $image): ?>
                <div class="aoc-img-wrap"><img src="<?= esc_url(wp_get_attachment_url(intval($image))) ?>" style="max-width:150px;"/><input type="hidden" name="aoc_images[]" id="aoc_img_input_<?= intval($image) ?>" value="<?= intval($image) ?>"><button data-img-id="<?= intval($image) ?>" class="aoc-del-img" type="button">X</button></div>
                <?php
            endforeach;
        endif;
        ?>
    </div>
    <div style="clear:both"></div>
    <p><a href="javascript:void(0)" class="aoc_add_image_link">Add another image</a></p>
    <div style="clear:both"></div>
    <?php
}

function aoc_save_meta_box($post_id)
{
    if (!wp_verify_nonce($_POST['aoc_save_img_nonce'], 'aoc_save_images')) {
        return;
    }
    if (!$_POST['aoc_images']){
        return;
    }
    $img_array = array();
    foreach ($_POST['aoc_images'] as $image){
        $img_array[] = intval($image);
    }
    update_post_meta($post_id, 'aoc_multiple_images', $img_array);
}

add_action('save_post', 'aoc_save_meta_box');

add_action('admin_enqueue_scripts', 'aoc_admin_scripts');

function aoc_admin_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script('aoc_admin_script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery']);
    wp_enqueue_style('aoc_admin_css', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
}

function aoc_get_images($post_id)
{
    if (!$post_id) {
        return;
    }

    $gallery = get_post_meta($post_id, 'aoc_multiple_images', true);
    $images = [];
    if ($gallery) {
        foreach ($gallery as $image) {
            $images[] = wp_get_attachment_url($image);
        }
    }
    return $images;
}
