<?php
$params_address = qode_listing_get_address_params(get_the_ID());
extract($params_address);
$get_directions_link = '';
if ( $address_lat !== '' && $address_long !== '' ) {
	$get_directions_link = '//maps.google.com/maps?daddr=' . $address_lat . ',' . $address_long;
}?>

<div class="qode-ls-single-map-holder" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">

	<?php if($address_lat !== '' && $address_long !== ''){

	    echo qode_listing_get_listing_item_map($address_lat, $address_long); ?>

		<!-- render map overlay-->
		<div class="qode-ls-single-map-address-info" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<h6 class="qode-ls-address-title">
				<?php esc_html_e('Listing Location', 'qode-listing'); ?>
			</h6>
			<p class="qode-ls-address-info">
				<a href="<?php echo esc_url($get_directions_link) ?>" target="_blank">
					<?php echo esc_html( $address ); ?>
				</a>
			</p>
		</div>

	<?php }
		qode_listing_single_template_part('parts/contact-info', '',$params);
		qode_listing_single_template_part('parts/social-networks', '',$params);
	?>

</div>