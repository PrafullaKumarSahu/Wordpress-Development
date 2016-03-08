<?php
/*Replace Add to Cart button with members only text on single product page */
function _wc_make_only_memebr_products() {
	
	if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){
		
		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
	}
	$product = new WC_Product( get_the_ID() );
	$level_id = get_post_meta( $product->id, '_membership_product_level', true );
	$level = pmpro_getLevel($level_id);
	$name = $level->name;
	if( pmpro_hasMembershipLevel() ){
		//var_dump($current_user);
		echo 'Your Current Membership Level: ' . $current_user->membership_level->name . '<br />';
	} elseif( $level_id ) {
		
		echo '<br /> If You Are a ' . $name . ' Member, Please ';
		echo '<a href="' . wp_login_url( get_permalink() ) . '" title="Login">Login</a>';
		echo ' To Buy This Product. or <br />';
	}
	if( $current_user->membership_level->name != $name && $level_id ){
			
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		
		echo 'Buy <a target="_blank" href="' . pmpro_url("checkout", "?level=" . $level->id, "https") . '">'. $name . ' membership</a> to buy this product.';
		
	}
}
add_action( 'woocommerce_single_product_summary', '_wc_make_only_memebr_products', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

//Remove add to cart button on shop page for membership products if memebr is not logged in with required memebrship level
/*STEP 1 - REMOVE ADD TO CART BUTTON ON PRODUCT ARCHIVE (SHOP) */

function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');



/*STEP 2 -ADD NEW BUTTON THAT LINKS TO PRODUCT PAGE FOR EACH PRODUCT */

add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
function replace_add_to_cart() {
	if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){
		
		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
	}
    global $product;
	$product = new WC_Product( get_the_ID() );
	$level_id = get_post_meta( $product->id, '_membership_product_level', true );
	$level = pmpro_getLevel($level_id);
	$name = $level->name;
	if( $level_id ){
			if( $current_user->membership_level->name != $name || !is_user_logged_in() ){
				
				echo '<form action="' . esc_url( $product->get_permalink( $product->id ) ) . '" method="get">
						<button type="submit" class="single_add_to_cart_button button alt">' . __('View More', 'woocommerce') . '</button>
				</form>';
			} else {
				echo '<a class="button add_to_cart_button product_type_simple" rel="nofollow" data-product_id="' . get_the_ID() . '" data-product_sku="" data-quantity="1"  href="' . esc_url( get_site_url() . '/cart/?add-to-cart=' . get_the_ID() ) . '">Add To Cart </a>';
			}
	} else {
		echo '<a class="button add_to_cart_button product_type_simple" rel="nofollow" data-product_id="' . get_the_ID() . '" data-product_sku="" data-quantity="1"  href="' . esc_url( get_site_url() . '/cart/?add-to-cart=' . get_the_ID() ) . '">Add To Cart </a>';
	}
}


/* Begin Add Membership meta box to WooCommerce product add/edit */
/* function my_page_meta_wrapper(){
	//duplicate this row for each CPT
	add_meta_box('pmpro_page_meta', 'Require Membership', 'pmpro_page_meta', 'product', 'side');
}
function pmpro_cpt_init(){
	if (is_admin()){
		add_action('admin_menu', 'my_page_meta_wrapper');
	}
}
add_action("init", "pmpro_cpt_init", 20); */
/* End Add Membership meta box to WooCommerce product add/edit */

/* Begin Custom PMPro Levels shortcode */
/* function my_pmpro_levels($atts = NULL)
{
    ob_start();

    include(PMPRO_DIR . "/preheaders/levels.php");

    if(file_exists(get_stylesheet_directory() . "/paid-memberships-pro/pages/levels.php"))
        include(get_stylesheet_directory() . "/paid-memberships-pro/pages/levels.php");
    else
        include(PMPRO_DIR . "/pages/levels.php");

    $temp_content = ob_get_contents();
    ob_end_clean();

    return apply_filters("pmpro_pages_shortcode_levels", $temp_content);
}
add_shortcode('my_pmpro_levels', 'my_pmpro_levels'); */
/* End Custom PMPro Levels shortcode */

//restrict user from adding membership product to cart
add_filter( 'woocommerce_add_to_cart_validation', 'validate_active_subscription', 10, 3 );

function validate_active_subscription( $valid, $product_id, $quantity ) {
	global $woocommerce;
  
	if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){

		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
	}
	$product_id = (int) ( apply_filters( 'woocommerce_add_to_cart_product_id', $_GET['add-to-cart'] ) ? apply_filters( 'woocommerce_add_to_cart_product_id', $_GET['add-to-cart'] ) : apply_filters( 'woocommerce_add_to_cart_product_id', $_POST['add-to-cart'] ) ) ;
	$level_id = get_post_meta( $product_id, '_membership_product_level', true );
	$level = pmpro_getLevel($level_id);
	$name = $level->name;

	if( $level_id ){
		if( $current_user->membership_level->name != $name || !is_user_logged_in() ){
			$valid = false;
		}else{
			$valid = true;
		}
	} else {
		$valid = true;
	}
	return $valid;
} 




