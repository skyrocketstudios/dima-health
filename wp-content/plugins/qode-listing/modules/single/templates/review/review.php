<li itemprop="review" itemscope itemtype="http://schema.org/Review">
	<div class="<?php echo esc_attr($comment_class); ?>">

		<?php if(!$is_pingback_comment) {
		    ?>

            <div class="qode-comment-author">

                <div class="qode-comment-image" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <?php echo bridge_qode_kses_img(get_avatar($comment, 110)); ?>
                </div>

                <h6 class="qode-comment-author-name">
                    <a itemprop="author" class="qode-post-info-author-link" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">
                        <?php
                            echo wp_kses_post($comment->comment_author);
                        ?>
                    </a>
                </h6>

            </div>

		<?php } ?>

		<div class="qode-comment-text">

			<div class="qode-comment-info">
				<?php
				$review_rating = get_comment_meta( $comment->comment_ID, 'qode_rating', true );
				$review_title = get_comment_meta( $comment->comment_ID, 'qode_comment_title', true );
				?>

				<h5 class="qode-review-title">
					<span><?php echo esc_html( $review_title ); ?></span>
				</h5>

                <div class="qode-average-rating">
					<span>
						<?php echo qode_listing_singular_plural_words($review_rating, esc_html__('No reviews', 'qode-listing'), esc_html__('Star', 'qode-listing'), esc_html__('Stars', 'qode-listing')); ?>
					</span>
				</div>

                <div class="qode-review-rating">
					<span class="rating-inner" <?php echo bridge_qode_get_inline_style($review_rating_style) ?>></span>
				</div>
				<?php
				$commentMetaDate = $comment->comment_date_gmt;
				?>
				<span class="qode-comment-date" itemprop="datePublished" content="<?php echo esc_attr($commentMetaDate); ?>">
						<?php comment_date(get_option('date_format'), $comment->comment_ID); ?>
				</span>
			</div>

			<?php if(!$is_pingback_comment) { ?>

				<div class="qode-text-holder" id="comment-<?php echo $comment->comment_ID; ?>"  itemprop="reviewBody">
					<?php print $comment->comment_content; ?>
				</div>

			<?php } ?>
		</div>
	</div>
</li>