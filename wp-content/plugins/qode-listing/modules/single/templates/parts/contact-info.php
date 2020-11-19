<?php
$phone = $article_obj->getPostMeta('_listing_phone');
$web_site = $article_obj->getPostMeta('_company_website');
$mail = $article_obj->getPostMeta('_listing_mail');

if($phone !== '' || $web_site !== '' || $mail !== ''){ ?>

	<div class="qode-ls-contact-info-holder">
		<?php if($phone !== ''){ ?>

			<div class="qode-ls-contact-info phone">
				<div class="qode-ls-contact-info-inner left">
					<?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-device-mobile' , 'dripicons' );?>
				</div>
				<div class="qode-ls-contact-info-inner right">
					<span>
						<?php echo wp_kses_post($phone); ?>
					</span>
				</div>
			</div>

		<?php }

		if($web_site !== ''){ ?>

			<div class="qode-ls-contact-info website">
				<div class="qode-ls-contact-info-inner left">
					<?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-direction', 'dripicons' );?>
				</div>
				<div class="qode-ls-contact-info-inner right">
					<a href="<?php echo esc_url($web_site) ?>" target="_blank">
						<?php echo wp_kses_post( $web_site ); ?>
					</a>
				</div>
			</div>

		<?php }

		if($mail !== ''){ ?>

			<div class="qode-ls-contact-info email">
				<div class="qode-ls-contact-info-inner left">
					<?php echo bridge_qode_icon_collections()->renderIconHTML( 'dripicons-mail', 'dripicons' );?>
				</div>
				<div class="qode-ls-contact-info-inner right">
					<a href="mailto:<?php echo wp_kses_post( $mail ); ?>">
						<?php echo wp_kses_post( $mail ); ?>
					</a>
				</div>
			</div>

		<?php } ?>
	</div>

<?php }