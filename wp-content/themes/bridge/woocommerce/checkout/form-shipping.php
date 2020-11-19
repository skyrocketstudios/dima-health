<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
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
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

// global $order;


$product_id = 0;
?>

<div class="col2-set" id="customer_details">
	<div class="col-1">
		
		<div class="woocommerce-shipping-fields">
			<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

				<h3 id="ship-to-different-address">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
						<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox hidden" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Shipping Details', 'woocommerce' ); ?></span>
					</label>
				</h3>

				<div class="shipping_address">

					<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

					<div class="woocommerce-shipping-fields__field-wrapper">
						<?php
						$fields = $checkout->get_checkout_fields( 'shipping' );

						foreach ( $fields as $key => $field ) {
							if($key == "shipping_state"){;
								$field['onchange']=__("shipping_state()");
								woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
								
							}else{
								woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							}
						}	
						?>
					</div>

					<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_checkout_billing' ); ?>
			
		</div>
	</div>

	<div class="col-2">
		
		<div class="woocommerce-additional-fields">
			<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

			<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

				<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

					<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

				<?php endif; ?>

				<div class="woocommerce-additional-fields__field-wrapper">
					<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
		</div>

	</div>
</div>
<!-- <input type="text" name="shipping_id" id="shipping_id"> -->
<?php 
	foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];
        if(!empty($product)){
			if($product->id == 15943){
				$product_id = $product->id;
			}
			
        }
    }

?>
<script>
		var product_id = 0; 
	
	jQuery('#shipping_state').change(function(){
				jQuery.noConflict();
				event.stopPropagation();
		 product_id = parseInt("<?php echo $product_id; ?>");
		//  jQuery('#cod').hide();
		var shipping_id = jQuery('#shipping_state option:selected').val();
		if(product_id == 15943){
			// console.log("1",product_id);
			jQuery('#cod').hide();
			// console.log('hello');
			jQuery("#payment_method_dragonpay").attr('checked', true);
			jQuery(".payment_method_dragonpay").show();
		}else{
				jQuery('#cod').show();
				jQuery("#payment_method_cod").attr('checked', true);
			// console.log("2",product_id);
			// if(shipping_id == '00' || shipping_id == 'RIZ'){	
			// 	jQuery('#cod').show();
			// 	jQuery("#payment_method_cod").attr('checked', true);
			// }else{ 
			// 	jQuery('#cod').hide();
			// 	jQuery("#payment_method_dragonpay").attr('checked', true);
			// 	jQuery(".payment_method_dragonpay").show();
			// }	
		}
		
	});


	

</script>


