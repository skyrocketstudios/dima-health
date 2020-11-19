<div class="excess_div" ></div>
<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
global $post;
// $stock = get_post_meta( the_ID(), '_stock', true );
$stock = $product->get_stock_quantity();

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */


do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

?>

<?php if ( version_compare( WOOCOMMERCE_VERSION, '3.4' ) >= 0 ) { ?>

	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	
	<div class="page-description-mobile">
<?php 
	$shop_page = get_post( wc_get_page_id( 'shop' ) );
	if ( $shop_page ) {
		$description = wc_format_content( $shop_page->post_content );
		if ( $description ) {
			echo '<div class="page-description" style="margin-top:20px!important;">' . $description . '</div>'; // WPCS: XSS ok.
		}
	}
	?>
	</div>
	<?php
}

else { ?>
	<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php

} ?>
	
	<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		
		

	?>

	<div class="summary entry-summary">
		<div class="clearfix">
			<span class="custom_post_category" style="font-weight:700;">Availability :	<span class='custom_single_product_stocks' style="font-weight:400;" >

				<?php if ($stock > 0){echo "IN STOCK";} else{ echo "OUT OF STOCK";} 
				?>
				</span>
				</span>

			<?php
				/*
				 * woocommerce_single_product_summary hook.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				
				do_action( 'woocommerce_single_product_summary' );
				
			?>

		</div><!-- .clearfix -->

	</div><!-- .summary -->
	
	<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 * @hooked woocommerce_upsell_display - 15
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>

<script>


	var div = document.getElementById('product-<?php echo the_ID()?>');
	div.className += " custom_product_div";


	jQuery(document).ready(function(){ 
		if ( jQuery( "#questionnaireform" ).length ) { 
			console.log("questionnaireform exists!");
			
			jQuery("#alg_checkout_files_upload_form_1").show();
		}

		jQuery('#scrollToDescription').on('click', function(e) { 
			
			var el = jQuery( e.target.getAttribute('href') );
			var elOffset = el.offset().top;
			var elHeight = el.height();
			var windowHeight = jQuery(window).height();
			var offset;
				console.log(el);
			if (elHeight < windowHeight) {
				offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
			}
			else {
				offset = elOffset;
			}
			
			var speed = 500;
			jQuery('html, body').animate({scrollTop:offset}, speed);
		});
		
	});

	
	//JS to show upload file input
	var tr = document.getElementById('alg_checkout_files_upload_form_1').getElementsByTagName('tr');
	tr[1].style.display ='none';
	
	function showHideInput(){
		let status = tr[1].style.display;
		// status = status == 'none' ? '' : 'none';
		if(status=='none'){
			tr[1].style.display = '';
		}
		else{
			tr[1].style.display = 'none';
		}
	}

	
		

//Description label to Important info
</script>