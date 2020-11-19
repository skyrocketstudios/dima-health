<?php
use QodeListing\Lib\Front;
use QodeListing\Lib\Core;
$this_object = qode_listing_adv_search_class_instance();

$query_results = $this_object->getQueryResults();
$type_id = $this_object->getBasicParamByKey('type');
$data_params = $this_object->getBasicParamByKey('data_params');
$holder_classes = $this_object->getBasicParamByKey('holder_classes');
$content_in_grid = $this_object->getBasicParamByKey('content_in_grid') === 'yes' ? true : false;
$grid_class = '';
$search_title = $this_object->getBasicParamByKey('search_title');
$search_subtitle = $this_object->getBasicParamByKey('search_subtitle');
if($content_in_grid){
    $grid_class = 'grid_section';
    $grid_class_inner = 'section_inner';
}

$map_flag = $this_object->getBasicParamByKey('enable_map') === 'yes' ? true : false;
$keyword_flag = $this_object->getBasicParamByKey('keyword_search') === 'yes' ? true : false ;

$banner_html = $this_object->getBannerHtml();


$html = '';	?>

<div class="qode-ls-adv-search-holder clearfix <?php echo esc_attr($holder_classes);?>" <?php echo esc_attr($data_params); ?>>
	<?php

    if($map_flag){
	    echo qode_listing_get_shortcode_module_template_part('templates/map', 'listing-advanced-search');
	}

	if($type_id !== ''){ ?>
	    <div class="qode-ls-adv-search-content <?php echo esc_attr($grid_class);?>" >
	    <div class="qode-ls-adv-search-content-inner <?php echo esc_attr($grid_class_inner);?>" >

		<?php 
		   if($keyword_flag && !$map_flag){
		       echo qode_listing_get_shortcode_module_template_part('templates/keyword', 'listing-advanced-search');
		   }

		   if($search_title !== '' || $search_subtitle !== '') { ?>

                <div class="qode-ls-adv-title-holder">

                    <?php if($search_title !== '') {?>
                        <h2 class="qode-ls-adv-title" >
                            <?php echo wp_kses_post($search_title); ?>
                        </h2 >
                    <?php }

                    if($search_subtitle !== '') { ?>
                        <span  class="qode-ls-adv-subtitle">
                            <?php echo wp_kses_post($search_subtitle); ?>
                        </span>
                    <?php } ?>
                </div>

            <?php } ?>
    
		<div class="qode-ls-adv-search-items-holder qode-ls-adr-normal-space qode-ls-adr-three-columns clearfix">
			<div class="qode-ls-adv-search-items-holder-inner qode-ls-adr-inner clearfix">

				<?php
					if($query_results->have_posts()){
						while ( $query_results->have_posts() ) {
							$query_results->the_post();
							$article_obj = new Core\ListingArticle(get_the_ID());

							$params  = array(
								'type_html' => $article_obj->getTaxHtml('job_listing_type', 'qode-listing-type-wrapper'),
								'cat_html' => $article_obj->getTaxHtml('job_listing_category', 'qode-listing-cat-wrapper'),
								'rating_html' => $article_obj->getListingAverageRating(),
								'address_html' => $article_obj->getAddressIconHtml(),
								'listing_author' => get_the_author(),
								'price_html'  => $article_obj->getActualPriceHtml(),
								'article_obj' => $article_obj
							);

							$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-advanced-search','',$params);
						}
					}
					else{
						$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-advanced-search');
					}

					wp_reset_postdata();
					print $html; ?>
			</div>
			<?php
				echo qode_listing_get_shortcode_module_template_part('templates/load-more-template', 'listing-advanced-search');
			?>
		</div>
	    <div class="qode-ls-adv-search-fields-holder">

		   <?php
			$object = new Front\ListingTypeFieldCreator($type_id);
			$object->getAdvSearchHtml();
			?>
			<?php if($banner_html !== ''){

				echo wp_kses_post($banner_html);

			} ?>
	    </div>
	    </div>
	    </div>

	<?php }
	else{
		echo qode_listing_get_shortcode_module_template_part('templates/type-not-found', 'listing-advanced-search');
	}?>
</div>