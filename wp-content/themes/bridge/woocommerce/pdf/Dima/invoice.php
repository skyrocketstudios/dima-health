<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $this->has_header_logo() ) {
			$this->header_logo();
		} else {
			echo $this->get_title();
		}
		?>
		</td>
	</tr>
</table>

<h1 class="document-type-label">
<?php if( $this->has_header_logo() ) echo "Prescription"; ?>
</h1>


<table class="order-data-addresses">
	<tr>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
				<?php if ( isset($this->settings['display_number']) ) { ?>
				<tr class="invoice-number">
					<th><?php _e( '#', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($this->settings['display_date']) ) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th><?php _e( 'Patient:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->billing_address(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>

			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

<table class="order-details">
	<tbody>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
			<td class="product">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				
				<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
			</td>
			<td class="quantity"> x <?php echo $item['quantity']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>				
		<tr class="no-borders">
			<td class="no-borders">
				<div class="customer-notes">
					<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
					<?php if ( $this->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->shipping_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
				</div>				
			</td>
			<td>&nbsp;</td>
		</tr>
	</tfoot>
</table>


<?php 		
$args = array(
    'post_id' => $this->order_id,
    'orderby' => 'comment_ID',
    'order'   => 'DESC',
    'approve' => 'approve',
    'type'    => 'order_note',
);
remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
$notes = get_comments( $args );
add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

foreach ($notes as $ky => $note) {
	// var_dump($note->comment_content, $note->comment_author);

	if (strpos($note->comment_content, 'to Approved by the Doctor.') !== false) {
	   //echo 'Issued by:'. $note->comment_author;
		$arr_auth[] =  $note->comment_author;
	}
}

if($arr_auth !== NULL || !empty($arr_auth)) {
	$last_doctor_approved = end($arr_auth);
} else {
	$last_doctor_approved = "";
}

if($last_doctor_approved !== "") { ?>
	<table>
		<tr>
			<td><?php _e( 'Issued by: ', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3></td>
			<td><strong><?php echo $last_doctor_approved; ?></strong> </td>
		</tr>
	</table>

<?php }?>
	   

<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>


<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>

