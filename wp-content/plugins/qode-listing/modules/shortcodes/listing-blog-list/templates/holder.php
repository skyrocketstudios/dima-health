<div class="qode-ls-blog-list qode-ls-blog-list-big-space qode-ls-blog-list-three-columns">
	<div class="qode-ls-blog-list-inner clearfix">
			<?php
				if($query_results->have_posts()){
					while ( $query_results->have_posts() ) {
						$query_results->the_post();
						$params  = array(
							'text_length' => $params['text_length'],
							'title_tag' => $params['title_tag']
						);

						$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-blog-list','',$params);
					}
				}
				else{
					$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-blog-list');
				}

				wp_reset_postdata();
				echo wp_kses_post($html);
				?>
	</div>
</div>