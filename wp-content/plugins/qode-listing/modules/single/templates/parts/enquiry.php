<div class="qode-ls-enquiry-holder">
	<div class="qode-ls-enquiry-inner">
        <a class="qode-ls-enquiry-close">
            <?php echo bridge_qode_icon_collections()->renderIconHTML( 'icon_close', 'font_elegant' );?>
        </a>
		<form class="qode-ls-enquiry-form" method="POST">
            
            <label><?php esc_html_e( 'Full Name', 'qode-listing' );?></label>
			<input type="text" name="enquiry-name" id="enquiry-name" placeholder="<?php esc_html_e( 'Your Full Name', 'qode-listing' );?>" required pattern=".{6,}">
            <label><?php esc_html_e( 'E-mail Address', 'qode-listing' );?></label>
			<input type="email" name="enquiry-email" id="enquiry-email" placeholder="<?php esc_html_e( 'Your E-mail Address', 'qode-listing' );?>" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
            <label><?php esc_html_e( 'Your Message', 'qode-listing' );?></label>
			<textarea name="enquiry-message" id="enquiry-message" placeholder="<?php esc_html_e( 'Your Message', 'qode-listing' );?>" required></textarea>

            <?php echo bridge_core_get_button_html(array(
				'text' => esc_html__('Send Your Message', 'qode-listing'),
				'html_type' => 'button',
				'type' => 'solid',
				'custom_class' => 'qode-ls-single-enquiry-submit'
			)); ?>

			<input type="hidden" id="enquiry-item-id" value="<?php echo get_the_ID(); ?>">
			<?php wp_nonce_field('qode_validate_listing_item_enquiry', 'qode_nonce_listing_item_enquiry'); ?>
		</form>
		<div class="qode-listing-enquiry-response"></div>
	</div>
</div>