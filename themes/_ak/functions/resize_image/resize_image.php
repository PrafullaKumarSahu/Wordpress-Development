<?php
 if ( function_exists( 'add_image_size' ) ) 
 { 
	/*** blog ***/	
	add_image_size('_ak_blog_img',730,280,true);
}
// code for home slider post types 
add_filter( 'intermediate_image_sizes', '_ak_image_presets');
function _ak_image_presets($sizes){
   $type = get_post_type($_REQUEST['post_id']);	
    foreach($sizes as $key => $value){				
		if($type=='post'  &&  $value != '_ak_blog_img'  )
		{        unset($sizes[$key]);      }		
    }
    return $sizes;	 
}
?>