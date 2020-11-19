<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
$this_object = qode_listing_regions_class_instance();
$query_results = $this_object->getQueryResults();
$html = '';
?>

<div class="qode-ls-regions-holder qode-ls-region-gallery  qode-ls-gallery-normal-space qode-ls-gallery-four-columns clearfix">
	<div class="qode-ls-gallery-inner clearfix">
		<?php
			if(is_array($query_results) && count($query_results)){
				foreach($query_results as $tax){

				    $tax_params = array(
						'tax' => $tax
					);
					$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-regions', '' ,$tax_params);
				}
			}
			else{
				$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-regions');
			}
			wp_reset_postdata();
			print $html;
		?>
	</div>
</div>