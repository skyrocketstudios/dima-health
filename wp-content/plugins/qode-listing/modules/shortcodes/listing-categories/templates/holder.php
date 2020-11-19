<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
$this_object = qode_listing_categories_class_instance();
$query_results = $this_object->getQueryResults();
$html = '';
$button_params = array(
	'text' => esc_html__('Add Listing', 'qode-listing'),
	'size' => 'small'
);
?>

<div class="qode-ls-categories-holder qode-ls-category-gallery  qode-ls-gallery-normal-space qode-ls-gallery-three-columns clearfix">
	<div class="qode-ls-gallery-inner clearfix">
		<div class="qode-ls-gallery-sizer"></div>
		<?php
			if(is_array($query_results) && count($query_results)){
				foreach($query_results as $tax){

				    $tax_params = array(
						'tax' => $tax
					);
					$button_params['link'] = '';
					if($tax['custom_link'] !== ''){
						$button_params['link'] = $tax['custom_link'];
					}

					$tax_params['button_params'] = $button_params;

					$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-categories', $tax['gallery_type'] ,$tax_params);
				}
			}
			else{
				$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-categories');
			}
			wp_reset_postdata();
			print $html;
		?>
	</div>
</div>