<?php
if( 'page' == get_option( 'show_on_front' ) ){
	get_template_part( 'index' );
} else {
	get_header();
	get_template_part( 'index', 'static' );

	if( get_theme_mod( '_ak_service_enable', true ) == true ){
		get_template_part( 'index', 'service' );
	}
	get_footer();
}