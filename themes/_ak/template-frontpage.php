<?php
/**
* Template Name: Home Page
*/
get_header();
get_template_part( 'index', 'static' );
if( get_option( '_ak_service_enable' ) == true ){
	get_template_part( 'index', 'service' );
}
get_footer();
?>