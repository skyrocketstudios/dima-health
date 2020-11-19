<?php
use QodeListing\Lib\RelatedPost;
$tax_array = qode_listing_related_taxonomy_settings();

$related_post_object = new RelatedPost\QodeRelatedPosts(get_the_ID(), $tax_array);
$related_post_query = $related_post_object->getRelatedPosts();
$params = array(
	'query' => $related_post_query
);

echo qode_listing_single_template_part('related-posts/holder', '', $params);