<?php
/*
* Adding a logo
*/
function widgetsite_logo_customizer( $wp_customize ) {
	$wp_customize->add_section( 'widgetsite_logo_section' , array(
		'title' => __( 'Logo', 'widgetsite' ),
		'priority' => 30,
		'description' => 'Upload a logo to replace the default site name and description in the header',
	) );
	$wp_customize->add_setting( 'themes_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themes_logo', array( //WP_Customize_Image_Control class to to render the custom image control on the Theme Customizer
		'label' => __( 'Logo', 'widgetsite' ),
		'section' => 'widgetsite_logo_section',
		'settings' => 'themes_logo',
	) ) );
}
add_action('customize_register', 'widgetsite_logo_customizer');