<?php
use QodeListing\Lib\Core;
$this_object = qode_listing_package_class_instance();
$link = $this_object->getBasicParamByKey('listing_package_link');
$title = $this_object->getBasicParamByKey('listing_package_title');
if($title === ''){
    $title = esc_html__('View Package', 'qode-listing');
}

$packages = $this_object->getPackages();

if(is_array($packages) && count($packages)){ ?>
    <div class="qode_pricing_tables clearfix three_columns qode-ls-packages">
        <?php foreach ($packages as $package){

            $id = $package->ID;
            $article = new Core\ListingArticle($package->ID);

            $params = array(
                'price' => $article->getPostMeta('_price'),
                'featured' => $article->getPostMeta('_job_listing_featured') === 'yes' ? true : false,
                'purchase_note' => $article->getPostMeta('_purchase_note'),
                'content' => $package->post_content,
				'additional_info' => $package->post_excerpt,
                'package' => $package,
                'id'	   => $id,
				'button_text' => esc_attr($title),
				'link'  => $link
            );

            echo qode_listing_get_shortcode_module_template_part('templates/package', 'listing-package', '', $params);

        }?>
    </div>
<?php }