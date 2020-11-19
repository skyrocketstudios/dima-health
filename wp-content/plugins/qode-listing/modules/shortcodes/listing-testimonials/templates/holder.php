<div class="qode-ls-testimonials qode-ls-testimonials-normal-space qode-ls-testimonials-two-columns">
	<div class="qode-ls-testimonials-inner clearfix">
			<?php
				if($query_results->have_posts()){
					while ( $query_results->have_posts() ) {
						$query_results->the_post();
						$params  = array(
							'author'            => get_post_meta(get_the_ID(), "qode_testimonial-author", true),
							'website'           => get_post_meta(get_the_ID(), "qode_testimonial_website", true),
							'company_position'  => get_post_meta(get_the_ID(), "qode_testimonial-company_position", true),
							'text'              => get_post_meta(get_the_ID(), "qode_testimonial-text", true)
						);

						$html .= qode_listing_get_shortcode_module_template_part('templates/item', 'listing-testimonials','',$params);
					}
				}
				else{
					$html = qode_listing_get_shortcode_module_template_part('templates/post-not-found', 'listing-testimonials');
				}

				wp_reset_postdata();
				echo wp_kses_post($html);
				?>
	</div>
</div>