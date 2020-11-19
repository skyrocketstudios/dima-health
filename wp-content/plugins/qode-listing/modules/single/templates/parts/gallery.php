<?php
$galery_imgs = $article_obj->getPostMeta('_listing_gallery_images');
$data_params = array(
	'data-enable-auto-width' => 'yes',
    'pretty_photo'       => 'yes'
);

if(is_array($galery_imgs) && count($galery_imgs)){?>

	<div class="qode-ls-single-gallery-holder" <?php echo bridge_qode_get_inline_attrs($data_params); ?>>
		<?php foreach($galery_imgs as $img_url){
			if($img_url !== ''){ ?>
				<div class="qode-ls-single-gallery-item">
                    <a itemprop="image" class="qode-ls-single-lightbox" href="<?php echo esc_url($img_url)?>" data-rel="prettyPhoto[single_pretty_photo]">
					    <img src="<?php echo esc_url($img_url); ?>" alt="qode-ls-gallery-img"/>
                    </a>
				</div>
			<?php }
		} ?>
	</div>

<?php }