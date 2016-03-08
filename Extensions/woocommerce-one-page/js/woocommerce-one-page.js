if ( typeof wc_add_to_cart_params === 'undefined' ){
	console.log("false");
}

jQuery( document ).ready( function( jQuery ) {	
	/*
	* Code to update cart using single product data
	*/	
	jQuery('#product_form .quantity').change(function(){
		var id =  jQuery( this ).next('.product-qty').attr('product_id');
		var quantity = Math.abs(jQuery(this).children('.qty').val());
		var variation_id = jQuery( '#product' + id ).attr('variation_id');
		jQuery(this).children('.qty').val(quantity);	
			
		var data = {
			action: 'woocommerce_one_page_add_to_cart_variable_rc',
			product_id: id,
			quantity: quantity,
			variation_id: variation_id,
		};
		jQuery.post(wc_add_to_cart_params.ajax_url, data, function(response){
			jQuery('#one_page_total').html(response);
		});
    });
	
	
	/*
	*Making changes according to origin and destination attribute
	*/
	jQuery('#origin, #destination').change(function(){
		var origin = jQuery('#origin').find('option:selected').val();
		var destination = jQuery('#destination').find('option:selected').val();
		var variation = {attribute_origin: origin, attribute_pa_destination: destination};
		
		Object.getOwnPropertyNames(locations_obj).forEach(function(val, idx, array) {
			var found = false, key = null;
			Object.keys(locations_obj[val]).forEach(function (k) {
				if (!found && locations_obj[val][k].attribute_origin === variation['attribute_origin'] && locations_obj[val][k].attribute_pa_destination === 	variation['attribute_pa_destination']) {	
					found = true;
					key = k;
					jQuery('#product' + val).html(price_obj[val][key]).attr("variation_id", key);				}
			});	
		});	
		/*
		*Code to update cart by sending all product data during change in origin and destination
		*/
		product_info = [];
		jQuery('.qty').each(function(){	
			if (jQuery( this ).parent('.quantity').next('.product-qty').attr('product_id') !== undefined ){
				if ( jQuery('#product' + jQuery( this ).parent('.quantity').next('.product-qty').attr('product_id')).attr('variation_id')  != undefined && jQuery(this).val() != 0){
					product_info.push({id: jQuery( this ).parent('.quantity').next('.product-qty').attr('product_id'), quantity: jQuery(this).val(), variation_id: jQuery('#product' + jQuery( this ).parent('.quantity').next('.product-qty').attr('product_id')).attr('variation_id') });
				}
			}
		});
		var data = {
			action: 'woocommerce_one_page_add_to_cart_variable_rc',
			product_info: product_info
		};
	 	jQuery.post(wc_add_to_cart_params.ajax_url, data, function(response){
			jQuery('#one_page_total').html(response);
		});
	});
});