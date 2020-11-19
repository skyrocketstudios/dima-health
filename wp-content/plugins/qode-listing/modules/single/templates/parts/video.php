<?php
$self_hosted_video = $article_obj->getPostMeta('_listing_self_hosted_video');
$video_url_soc_net = $article_obj->getPostMeta('_listing_video');
$video_text = $article_obj->getPostMeta('_listing_video_text');

if($self_hosted_video !== '' || $video_url_soc_net){ ?>

	<div class="qode-ls-content-part-holder clearfix">

		<div class="qode-ls-content-part left">
			<h6 class="qode-ls-content-part-title">
				<?php esc_html_e('Property video','qode-listing'); ?>
			</h6>
		</div>

		<div class="qode-ls-content-part right">
			<div class="qode-ls-content-video-part">
				<?php if($self_hosted_video !== ''){ ?>

					<div class="qode-self-hosted-video-holder">
						<div class="qode-video-wrap">
							<video class="qode-self-hosted-video" poster="<?php echo esc_url(get_post_meta(get_the_ID(), "video_format_image", true));  ?>" preload="auto">
								<source type="video/mp4" src="<?php echo esc_url($self_hosted_video);  ?>">
								<object width="320" height="240" type="application/x-shockwave-flash" data="<?php echo esc_url(get_template_directory_uri().'/assets/js/flashmediaelement.swf'); ?>">
									<param name="movie" value="<?php echo esc_url(get_template_directory_uri().'/assets/js/flashmediaelement.swf'); ?>" />
									<param name="flashvars" value="controls=true&file=<?php echo esc_url($self_hosted_video);  ?>" />
								</object>
							</video>
						</div>
					</div>

				<?php }
				if($video_url_soc_net !== ''){
					echo wp_oembed_get($video_url_soc_net);
				}?>

			</div>
			<?php if($video_text !== ''){ ?>
				<div class="qode-ls-content-video-text">
					<?php echo esc_html($video_text); ?>
				</div>
			<?php } ?>
		</div>

	</div>

<?php }