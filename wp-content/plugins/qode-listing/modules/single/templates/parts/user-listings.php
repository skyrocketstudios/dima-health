<?php
$user_id = get_the_author_meta( 'ID' );
$params = array(
	'user_id' => $user_id,
	'post_number' => 4,
	'post_not_in' => array(
		get_the_ID()
	)
);
$user_listings = qode_listing_get_listing_query_results($params);

if($user_listings->have_posts()){ ?>

	<div class="qode-ls-user-listing-holder">
		<div class="qode-ls-user-listing-header">
			<h5 class="qode-ls-user-listing-title">
				<?php esc_html_e('More from this employer','qode-listing'); ?>
			</h5>
			<form class="qode-ls-user-listing-link" method="get" action="<?php echo esc_url(get_post_type_archive_link( 'job_listing' )); ?>">

				<input type="hidden" name="qode-ls-user-id" value="<?php echo esc_attr($user_id) ?>">
				<?php echo bridge_core_get_button_html(array(
					'text' => esc_html__('See All', 'qode-listing'),
					'html_type' => 'button',
                    'custom_class' => 'qode-listing-button',
					'size'  => 'small'
				)); ?>
			</form>
		</div>
		<?php while($user_listings->have_posts()){

			$user_listings->the_post();
			$image_url = get_the_post_thumbnail_url();
			$image_style = 'background-image: url('.esc_url($image_url).')';
			?>

			<div class="qode-ls-user-listing-item">

                <div class="qode-ls-user-listing-item-image">
					<a href="<?php echo get_the_permalink(); ?>" class="qode-ls-user-listing-item-image-inner" <?php echo bridge_qode_get_inline_style($image_style) ?>></a>
				</div>

				<div class="qode-ls-user-listing-item-text">
					<h6 class="qode-ls-user-listing-title">
						<a href="<?php echo get_the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h6>
					<div class="qode-ls-user-listing-date">
						<?php the_time(get_option('date_format')); ?>
					</div>
				</div>

			</div>
		<?php }
		wp_reset_postdata(); ?>
	</div>

<?php }