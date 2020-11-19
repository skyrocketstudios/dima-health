<?php  extract(bridge_qode_get_blog_single_params()); ?>
<?php get_header(); 
$categories = get_the_category();
$guest_author = "";


?>

<div class="single_post_breadcrumb" <?php print bridge_qode_get_module_part( $bridge_qode_page_title_breadcrumbs_animation_data ); ?>> <?php bridge_qode_custom_breadcrumbs(); ?></div>

<?php if (have_posts()) : while (have_posts()) : the_post();

$guest_author = get_post_meta($post->ID,"author_name");

?>
<div class="single_post_container">
	<div class="single_post_category">
			<?php if ( ! empty( $categories ) ) {
    				echo esc_html( $categories[0]->name );   
			}?>
	</div>
	<div class="single_post_info">
		<div class="single_post_title">
			<?php echo wp_trim_words($post->post_title,15,'...')?>
		</div>
		<a class="single_post_author_date" href="#">by <?php echo $guest_author[0] != "" ? $guest_author[0] : strtoupper(the_author_meta('first_name')); ?> on <?php the_time('F j, Y'); ?></a>
	</div>
	<div class="single_post_image">
		<img width="100%" src="<?php 
		if ( has_post_thumbnail()) 
			{
			echo get_the_post_thumbnail_url($post->ID);
			
			}
		else
			{
			echo "https://dima.ph/wp-content/uploads/2020/01/placeholder-600x400-1.png" ;	
			}?>" alt="">
	</div>
	<div class="single_post_main_content">
		<?php the_content()?>	

		<div class="custom_single_post_tags">
			<?php $post_tags = get_the_tags();
				 if ( $post_tags ) {?>
				<span>TAGS : </span>
				<ul class="single_blog_tag_list">	 
				 <?php
	 			foreach( $post_tags as $tag ) {?>
				<li><?php echo $tag->name;?></li>
			<?php }}?>
			</ul>
		</div>
	</div>
	<?php $tags = wp_get_post_tags($post->ID);
		if ($tags) {?>

	<div class="related_posts">RELATED POSTS</div>
	<?php
$first_tag = $tags[0]->term_id; 
$args=array(
'tag__in' => array($first_tag),
'post__not_in' => array($post->ID),
'posts_per_page'=>2,
'caller_get_posts'=>1
);
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {?>
<ul class="related_posts_list">

<?php
while ($my_query->have_posts()) : $my_query->the_post(); ?>


<li class="related_posts_list">
	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
		
		<div class="related_post_container">
			<div class="related_posts_list" style="background-image:url('<?php the_post_thumbnail_url()?>')">
			</div>
			<div class="related_posts_list_info">
					<div>
						<span class="related_post_list_tag">
							<?php echo $post_tags[0]->name;?>
						</span>
					</div>
					<div class="related_post_list_title">
						<?php the_title()?>
					</div>
				
			</div>
		</div>
		
	</a>
</li>

 
<?php
endwhile;
}?>
</ul>

<?php
wp_reset_query();
}
?>



<?php 
comments_template();
// var_dump(aoc_get_images($post->ID));
?>
</div>





<?php


endwhile; endif; ?>


<?php get_footer(); ?>	