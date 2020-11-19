<?php
use QodeListing\Lib\Core;
if (have_posts()) :
	while (have_posts()) : the_post();?>
		<div class="full_width">
			<div class="full_width_inner clearfix">

				<div <?php bridge_qode_class_attribute(implode(' ',$holder_class)); ?>>
					<?php
					if(post_password_required()) {
						echo get_the_password_form();
					} else {
						//load proper listing template
						$article = new Core\ListingArticle(get_the_ID());
						$params  = array(
							'article_obj' => $article
						);

						qode_listing_single_template_part('single', $listing_template, $params);
					} ?>
				</div>

			</div>
		</div>
	<?php endwhile;
endif;