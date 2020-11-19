<?php
namespace QodeListing\Lib\RelatedPost;

class QodeRelatedPosts{

	private $post_id;
	private $post_type;
	private $tax_array;

	public function __construct($post_id, $tax_array = array()) {
		$this->post_id = $post_id;
		$this->tax_array = $tax_array;
		$this->setPostType();
	}

	private function setPostType(){
		$this->post_type = get_post_type($this->post_id);
	}

	private function getPostType(){
		return $this->post_type;
	}

	public function getRelatedPosts(){

		$post_taxes = $this->getTaxesByPriority();
		$related_posts = false;

		if(count($post_taxes)){
			foreach($post_taxes as $tax_key => $tax_prior){

				if(taxonomy_exists($tax_key)){
					$current_post_tax_array = $this->getRelatedPostsByTax($tax_key);
					if(count($current_post_tax_array)){
						$related_posts = $this->getPosts($tax_key, $current_post_tax_array);
					}
					if($related_posts){
						return $related_posts;
					}
				}

			}
		}
	}

	private function getTaxesByPriority(){

		$tax_prior_array = array();

		if(count($this->tax_array)){

			foreach ($this->tax_array as $tax_obj){
				$tax_prior_array[$tax_obj['id']] = $tax_obj['priority'];
			}
			array_multisort($tax_prior_array, SORT_ASC, $this->tax_array);

		}
		return $tax_prior_array;

	}

	private function getRelatedPostsByTax($tax_key){
		//in this case, function wp_get_object_terms will always return array, because we check in a step before if taxonomy exists
		$taxes = wp_get_object_terms($this->post_id, $tax_key);
		$tax_array = array();

		if(count($taxes)){
			foreach($taxes as $tax) {
				$tax_array[] = $tax->slug;
			}
		}

		return $tax_array;
	}

	private function getPosts($tax_key, $post_taxes){

		if($this->post_type === 'job_listing'){
			$params = array(
				'tax_array' => array(
					'tax_id' => $tax_key,
					'tax_slug_array' => $post_taxes
				),
				'post_not_in' => array($this->post_id)
			);
			return qode_listing_get_listing_query_results($params);
		}
		else{
			$args = array(
				'post_not_in' => array($this->post_id),
				'order'         => 'DESC',
				'orderby'       => 'date',
				'tax_query'     => array(
					array(
						'taxonomy'  => $tax_key,
						'field'     => 'term_id',
						'terms'     => $post_taxes,
					),
				)
			);
			$related_posts = new \WP_Query($args);

			return $related_posts;

		}

	}
}