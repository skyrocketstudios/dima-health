<?php
	$list_params = array(

		'type' => 'advanced',
		'title' => get_the_title($id),
		'image' => get_post_thumbnail_id( $id ),
		'price' => $price,
		'additional_info' => $additional_info,
		'price_period' => $purchase_note,
		'show_button' => 'yes',
		'link'  => $link,
		'button_text'  => $button_text

	);
	echo bridge_core_get_pricing_table_item_html($list_params, do_shortcode($content));