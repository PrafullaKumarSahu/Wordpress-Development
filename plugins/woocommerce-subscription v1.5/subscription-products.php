<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'product_type_selector', 'wpluv_add_product_type' );
function wpluv_add_product_type( $types ){
	$types[ 'subscription' ] = __( 'Subscription' );
	return $types;
}
add_action('woocommerce_product_options_sku', 'wpluv_start_buffer');
add_action('woocommerce_product_options_pricing', 'wpluv_end_buffer');

function wpluv_start_buffer(){
	ob_start();
}

function wpluv_end_buffer(){
	// Get value of buffering so far
	$getContent = ob_get_contents();

	// Stop buffering
	ob_end_clean();

	$getContent = str_replace('options_group pricing show_if_simple show_if_external', 'options_group pricing show_if_simple show_if_external show_if_subscription', $getContent);
	echo $getContent;
}
/**
 * @class 		WC_Product_Subscription
 */
class WC_Product_Subscription extends WC_Product {

	public function __construct( $product ) {
		
		$this->product_type = 'subscription';
		
		parent::__construct( $product );
		
		add_action( 'woocommerce_subscription_add_to_cart', __CLASS__ . '::subscription_add_to_cart', 30 );
		
	}
	public static function subscription_add_to_cart(){
		woocommerce_get_template( 'woocommerce/single-product/add-to-cart/subscription.php', array(), '', plugin_dir_path( __FILE__ ) . 'templates/' );
	}
}