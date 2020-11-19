<div <?php bridge_qode_class_attribute($holder_classes); ?> <?php echo bridge_qode_get_inline_style($holder_styles); ?>>
	<div class="qode-ls-banner-inner">
		<span <?php bridge_qode_class_attribute($holder_icon_classes); ?>  <?php echo bridge_qode_get_inline_style($icon_style); ?>>
			<?php bridge_qode_icon_collections()->renderIconHTML($icon, $icon_pack, array('icon_attributes' => array('class' => 'qode-icon-element'))); ?>
		</span>
		<?php if($number != '') :?>
			<div class="qode-ls-banner-number">
				<?php echo esc_attr($number); ?>
			</div>
		<?php endif; ?>
		<?php if($title != '') :?>
			<h5 class="qode-ls-banner-title">
				<?php echo esc_attr($title); ?>
			</h5>
		<?php endif; ?>
	</div>
	<?php if($link != '') :?>
		<a href="<?php echo esc_attr($link); ?>" class="qode-ls-banner-link">
			<span><?php echo esc_html__('Show More', 'qode-listing'); ?></span><?php bridge_qode_icon_collections()->renderIconHTML('dripicons-arrow-thin-right', 'dripicons'); ?>
		</a>
	<?php endif; ?>

</div>