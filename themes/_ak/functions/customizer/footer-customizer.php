<?php
/**
* Footer Customization Panel
*/
function _ak_footer_panel( $wp_customize ){
		$wp_customize->add_panel( 'footer_settings', array(
	    'title' => __( 'Footer Settings', '_ak' ),
		'description' => $description,
		'priority' => 90,
	) );
	
	$wp_customize->add_section( 'footer_options_sections', array(
	    'title' => __( 'Footer Options', '_ak' ),
		'panel' => 'footer_settings',
		'description' => '',
		'priority' => 90,
	) );
	
	$wp_customize->add_setting( '_ak_copyright_settings', array( 'default' => '<p>@ Copyright 2015 Developed By <a href="http://www.indibits.com/" target="_blank">Indibits</a></p><p></p>' ) );
	
	$wp_customize->add_control( '_ak_copyright_control', array(
	    'label' => __( 'Footer Copyright Settings', '_ak' ),
		'settings' => '_ak_copyright_settings',
		'section' => 'footer_options_sections',
		'type' => 'textarea',
	) );
}
add_action('customize_register', '_ak_footer_panel', 90);
