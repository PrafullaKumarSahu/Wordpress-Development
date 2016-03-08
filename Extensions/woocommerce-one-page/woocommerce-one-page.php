<?php	
/*
Plugin Name: Woocommerce One Page
Plugin URI: http://woothemes.com/woocommerce/plugins/woocommerce-one-page
Description: One Page Plugin
Version: 1.0.0
Author: Indibits
Author URI: http://indibits.com
Text Domain: woocommerce-one-page-plugin
*/
?>
<?php
//woocommerce_one_page
add_action( 'wp_ajax_woocommerce_one_page_add_to_cart_variable_rc', 'woocommerce_one_page_add_to_cart_variable_rc_callback' );
add_action( 'wp_ajax_nopriv_woocommerce_one_page_add_to_cart_variable_rc', 'woocommerce_one_page_add_to_cart_variable_rc_callback' );

function woocommerce_one_page_add_to_cart_variable_rc_callback() {	
	global $woocommerce;
	if(isset($_POST['product_id'])){
	
		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity = empty( $_POST['quantity'] ) ? 0 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
		$quantity = abs($quantity);
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$variation_id = $_POST['variation_id'];
	
		global $woocommerce;
		foreach($woocommerce->cart->cart_contents as $key=>$cart_item){
			if($cart_item['product_id'] == $product_id){
				WC()->cart->remove_cart_item($key);
			} 
		}
		if($quantity == 0){
			echo wp_kses_data( WC()->cart->get_cart_total() );
			exit();
		} else {
			/**
           * Add a product to the cart.
           *
           * @param string $product_id contains the id of the product to add to the cart
           * @param integer $quantity contains the quantity of the item to add
           * @param int $variation_id
           * @param array $variation attribute values
           * @param array $cart_item_data extra cart item data we want to pass into the item
           * @return string $cart_item_key
           */
			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) ) { 
			//, $variation_id = '', $variation = array(), $cart_item_data = array()
				echo wp_kses_data( WC()->cart->get_cart_total() );
				exit();
			}
		}
	}
	if ($_POST['product_info']){
		//echo count($_POST['product_info']);
		//exit();
		$product_info = $_POST['product_info'];
		
		foreach ($product_info as $info){
		 	$info['id'] = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $info[id] ) );
			$info['quantity'] = empty( $info['quantity'] ) ? 0 : apply_filters( 'woocommerce_stock_amount', $info['quantity'] );
			$info['quantity'] = abs($info['quantity']);
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $info[id], $info['quantity'] );
			
			//array_push($info, )
			
			foreach($woocommerce->cart->cart_contents as $key=>$cart_item){
				if($cart_item['product_id'] == $info['id']){
					WC()->cart->remove_cart_item($key);
				} 
			}
				/**
				* Add a product to the cart.
				*
				* @param string $product_id contains the id of the product to add to the cart
				* @param integer $quantity contains the quantity of the item to add
				* @param int $variation_id
				* @param array $variation attribute values
				* @param array $cart_item_data extra cart item data we want to pass into the item
				* @return string $cart_item_key
				*/
			if ( $passed_validation ){
				WC()->cart->add_to_cart( $info['id'], $info['quantity'], $info['variation_id'] );
			//, $variation_id = '', $variation = array(), $cart_item_data = array()
				
			}
		}
			echo wp_kses_data( WC()->cart->get_cart_total() );
			exit();
	}
}
 ?>

<?php
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
	
	function woocommerce_one_page_init(){
		
		class Woocommerce_One_Page extends WC_Product{
			var $products;
			var $product;
			public function __construct(){
				//add_action( 'woocommerce_archive_description', array($this, 'indibits_one_page'),60);	
				
				add_shortcode('woocommerce_one_page', array($this, 'indibits_one_page'));
				add_action( 'init', array($this, 'wc_loop_add_to_cart_scripts'), 99 );
			}
		/* 	function one_page_shortcode(){
				add_action( 'woocommerce_sidebar', array($this, 'indibits_one_page'),60);	
				add_action( 'wp_enqueue_scripts', array($this, 'wc_loop_add_to_cart_scripts'), 99 );
			} */
		
			function indibits_one_page() {
				$arg = array('posts_per_page' => -1, 'numberposts' => -1, 'post_type' => 'product');
				$products = get_posts($arg);
				
				?>
				<div id="one-page-shop">
					<div id="product-form">
						<form action="" method="post" id="product_form">
							<table class="shop_table cart" cellspacing="0">
								<thead>
									<tr>
										<th class="product-name"><?php _e( "Product", "woocommerce" ); ?> </th>
										<th class="product-quantity"><?php _e( "Quantity", "woocommerce" ); ?></th>
										<th class="product-price"><?php _e( "Price", "woocommerce" ); ?></th>
									</tr>
								</thead>
	
								<tbody>
									<?php $product_obj = new WC_Product_Factory();  ?>
									<?php if ($products): ?>
									
									<?php foreach($products as $product): ?>
							
									<tr>
										<td>
											<div class="product-title product-tool-tip-container">
												<div class="title tooltip_toggle hover ">
													<?php echo $product->post_title; ?>
												</div>
												<div class="product-tool-tip">
													<h3><?php echo $product->post_title; ?></h3>
													<?php if ($product->post_excerpt): ?>
														<?php echo wp_trim_words($product->post_excerpt, 15, 'more'); ?>
													<?php else: ?>
														<?php echo wp_trim_words($product->post_content, 15, 'more'); ?>
													<?php endif; ?>
										
													<?php $product = $product_obj->get_product($product); ?>
													
													
													<?php if ($product->product_type == 'variable'): ?>
													<?php $available_product_variatons = new WC_Product_Variable($product); ?>
													
													<?php $available_variations = $available_product_variatons->get_available_variations(); ?>
													
														<?php $children	= $product->get_children( $args = '', $output = OBJECT ); ?>
														
														<?php
															foreach ($children as $key=>$value) {
																
																$product_variatons = new WC_Product_Variation($value);
																
															//	var_dump($product_variatons);
																if ( $product_variatons->exists() && $product_variatons->variation_is_visible() ) {
																	$variations[$product->id][$value] = $product_variatons->get_variation_attributes();
																
																}
																if (has_post_thumbnail($value)){
																	$product_attachment_ids[$value] = get_post_thumbnail_id( $value );
																}
													
																$weight = $product_variatons->get_weight();
																if ($weight){
																	$weight .= get_option( 'woocommerce_weight_unit' );
																	$product_weight[$value] = $weight;
																}
													
																$size = str_replace( ' ', '', $product_variatons->get_dimensions() );
																if ($size){	
																	$size .= get_option('woocommerce_size_unit');
																	$product_dimension[$value] = $size;
																}
													
																$price = $product_variatons->get_price();
																if ($price){
																	$price .= get_option( 'woocommerce_price_unit' );
																	$product_price[$product->id][$value] = woocommerce_price($price);
																}
																//echo $product_variatons->variation_id;//Variation ID
															}
														?>
													<?php
														/* echo "<br />" . woocommerce_price($product->min_variation_price) . " to " . woocommerce_price($product->max_variation_price); */
													?>

													<?php
													if($product_attachment_ids){
														foreach ($product_attachment_ids as $product_attachment_id){
															$thumb_url = wp_get_attachment_thumb_url($product_attachment_id);	
													?>
											
													<img src="<?php echo $thumb_url ?>"/>
												<?php	}
													} else {
												?>
													<?php if (has_post_thumbnail( $product->id )): ?>
														<?php $product_thumbnail_id = get_post_thumbnail_id( $product->id ); ?>
													<?php
														$url = wp_get_attachment_thumb_url( $product_thumbnail_id );
													?>
														<img src="<?php echo $url ?>"/>
													<?php endif;
													}
													?>
													<?php else: ?>
																					
														<!--<?php //echo woocommerce_price($product->get_price()); ?>-->
															<?php if (has_post_thumbnail( $product->id )): ?>
																<?php $product_thumbnail_id = get_post_thumbnail_id( $product->id ); ?>
																<?php
																	$url = wp_get_attachment_thumb_url( $product_thumbnail_id );
																?>	
																<img src="<?php echo $url ?>"/>
															<?php  else:?>
																<?php echo "NO THUMBNAIL FOUND!"; ?>
															<?php endif; ?>
														<?php endif; ?>
												</div>
											</div>
										</td>	
										<td>
										
										<?php
										//sprintf( '<input type="hidden" data-product_id="%s" data-product_sku="%s" data-quantity="0" class="product_quantity" />', esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ) );
									
											global $woocommerce;
							
											$quantity =	array_column($woocommerce->cart->cart_contents, 'quantity', 'product_id');
											if($quantity[$product->id]){
												woocommerce_quantity_input(array('input_value' => $quantity[$product->id] , 'min_value' => 0 ), $product, true); 
												echo '<input type="hidden" class="product-qty" product_id="' . htmlentities($product->id) . '" name="product-qty" />';
											} else {
												woocommerce_quantity_input(array('input_value' => 0 , 'min_value' => 0 ), $product, true); 
												echo '<input type="hidden" class="product-qty" product_id="' . htmlentities($product->id) . '" name="product-qty" />';
											}	
										?>
										</td>
									
											
										<td>
											<?php $price = $product->get_price_html(); ?>	
											
											<?php echo '<span class="variation" id="product' . $product->id . '">' . $price . '</span>'; ?>
											
										
											
										</td>
									</tr>
									<?php endforeach;
										/*	var_dump($product_price);
										 	var_dump($variations); */
									?>
									<tr>
										<td colspan=2>Total Quantity</td>										
										<td><div id="one_page_total"><?php echo wp_kses_data( WC()->cart->get_cart_total() ); ?></div></td>
									</tr>
										<?php endif; ?>
								</tbody>
							</table>
						</form>
						
						<script>
							price_obj = <?php echo json_encode($product_price);?>;
							locations_obj = <?php echo json_encode($variations);?>;
						</script>
					</div>
			
				
					<div id="location-section">
						<div id="origin-section">
							<h2>Origin</h2>
							<select class="origin" id="origin" name="attribute_origin">
								<option selected="selected" value="australia">Australia</option>
								<option value="newzealand">New Zealand</option>
							</select>
						</div>
						<div id="destination-section">
							<h2>Destination</h2>
							<select class="destination" id="destination" name="attribute_pa_destination">
								<option selected="selected" value="australia">Australia</option>
								<option value="newzealand">New Zealand</option>
							</select>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			<?php
			}

			//add_action( 'wp_enqueue_scripts', 'wc_loop_add_to_cart_scripts', 99 );
			
			function wc_loop_add_to_cart_scripts() {
				
					wp_enqueue_script(  'add-to-cart-variation', plugins_url() . '/woocommerce-one-page/js/woocommerce-one-page.js', array('jquery'), '', true );
					wp_enqueue_script( 'jquery-ui.min', plugins_url() . '/woocommerce-one-page/js/jquery-ui.min.js', array('jquery'), '', true );
	  
					wp_localize_script( 'add-to-cart-variation', 'AddToCartAjax', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
					wp_enqueue_style( 'woocommerce-one-page', plugins_url() . '/woocommerce-one-page/css/woocommerce-one-page.css');
					wp_enqueue_style( 'jquery-ui.min', plugins_url() . '/woocommerce-one-page/css/jquery-ui.min.css');
			}
		}
		$woocommerce_one_page = new Woocommerce_One_Page();
	}
	add_action('plugins_loaded', 'woocommerce_one_page_init', 0);
}
?>

