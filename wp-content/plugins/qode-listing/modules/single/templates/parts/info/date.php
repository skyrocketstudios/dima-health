<?php
	$month = get_the_time('m');
	$year = get_the_time('Y');
?>
<div itemprop="dateCreated" class="qode-ls-header-info date  entry-date published updated">
	<a itemprop="url" href="<?php echo get_month_link($year, $month); ?>">
		<?php the_time(get_option('date_format')); ?>
	</a>
</div>