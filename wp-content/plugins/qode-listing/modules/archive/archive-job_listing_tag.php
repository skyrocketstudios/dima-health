<?php
get_header();
?>
	<div class="qode-full-width">
		<?php do_action('qode_listing_action_after_container_open'); ?>
		<div class="qode-full-width-inner">
			<?php
			if(qode_listing_is_wp_job_manager_tags_installed()){
				qode_listing_get_listing_archive_pages();
			}
			?>
		</div>
		<?php do_action('qode_listing_action_before_container_close'); ?>
	</div>
<?php get_footer();