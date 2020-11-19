<?php
use QodeListing\Lib\Core;

$views_obj = new Core\ListingViews(get_the_ID());
$view_count = $views_obj->getViewCount();

?>
<div class="qode-ls-item-view-holder">
	<div class="qode-ls-item-view-icon">
		<?php echo bridge_qode_icon_collections()->renderIconHTML('fa-eye', 'font_awesome'); ?>
	</div>
	<div class="qode-ls-item-view-text">
		<span class="qode-ls-view-count">
			<?php echo esc_attr($view_count)?>
		</span>
		<span class="qode-ls-view-text-inner">
			<?php esc_attr_e('Views', 'qode-listing');?>
		</span>
	</div>
</div>