<?php
/**
 * Subscription Product Add to Cart
 */
global $woocommerce, $product;

if ( ! $product->is_purchasable() ) return;

// Availability
$availability = $product->get_availability();

if ($availability['availability']) :
	echo apply_filters( 'woocommerce_stock_html', '<p class="stock '.$availability['class'].'">'.$availability['availability'].'</p>', $availability['availability'] );
endif;

if ( ! $product->is_in_stock() ) : ?>
	<link itemprop="availability" href="http://schema.org/OutOfStock">
<?php else : ?>

	<link itemprop="availability" href="http://schema.org/InStock">

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<?php
		if ( ! $product->is_sold_individually() )
			woocommerce_quantity_input( array(
				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
		) );
		?>

		<button type="submit" class="button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>