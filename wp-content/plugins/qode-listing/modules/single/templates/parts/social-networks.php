<?php
$social_networks_array = qode_listing_get_listing_social_network_array();
$networks_to_show = array();

foreach($social_networks_array as $network){

	$value = get_post_meta(get_the_ID(), '_listing_'.$network['id'].'_url', true);

	if($value && $value !== null && $value !== ''){
		$networks_to_show[$network['id']]['object'] = $network;
		$networks_to_show[$network['id']]['value'] = $value;
	}

}

if(count($networks_to_show)){ ?>

	<div class="qode-ls-single-social-network-holder clearfix">

		<h5 class="qode-ls-single-social-net-title">
			<?php esc_html_e('Connect with us', 'qode-listing'); ?>
		</h5>

		<?php foreach($networks_to_show as $network){ ?>

			<a class="qode-ls-social-icon <?php echo esc_attr($network['object']['id']); ?>" href="<?php echo esc_url($network['value']); ?>" target="_blank">
				<?php echo bridge_qode_icon_collections()->renderIconHTML( 'fa-'.$network['object']['icon'], 'font_awesome' );?>
			</a>

		<?php } ?>
	</div>

<?php }