<?php
/**
* Adding Panel for Header Setting
*/
function _ak_header_panel( $wp_customize ){
	$wp_customize->add_panel( 'header_settings', array(
	    'title' => __( 'Header Settings' ),
		'description' => $description,
		'priority' => 160,
	) );
	
	$wp_customize->add_section( 'header_settings_option', array(
	    'title' => __( 'Header Settings', '_ak' ),
		'panel' => 'header_settings',
		'description' => '',
		
		'priority' => 120,
	) );
	
	$wp_customize->add_setting( '_ak_custom_css' );
	$wp_customize->add_setting( '_ak_site_favicon' );
	$wp_customize->add_setting( '_ak_site_logo' );
	$wp_customize->add_setting( '_ak_logo_text' );
	$wp_customize->add_setting( '_ak_logo_width', array( 'default' => 200 ) );
	$wp_customize->add_setting( '_ak_logo_height', array( 'default' => 100 ) );
	
	$wp_customize->add_control( '_custom_css', array(
	    'settings' => '_ak_custom_css',
		'label' => 'Custom CSS Snipet',
		'section' => 'header_settings_option',
		'type' => 'textarea',
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '_site_favicon', array(
	    'settings' => '_ak_site_favicon',
		'label' => 'Upload Favicon',
		'section' => 'header_settings_option',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '_site_logo', array(
	    'settings' => '_ak_site_logo',
		'label' => 'Upload Logo',
		'section' => 'header_settings_option',
	) ) );
	
	$wp_customize->add_control(
    '_ak_text_title',
    array(
	    'settings' => '_ak_logo_text',
        'type' => 'checkbox',
        'label' => __('Show Logo text','_ak'),
        'section' => 'header_settings_option',
		'priority'   => 200,
    )
	);
	
	$wp_customize->add_control( '_ak_logo_width', array(
	    'label' => __( 'Logo Width', '_ak' ),
		'type' => 'text',
		'settings' => '_ak_logo_width',
		'section' => 'header_settings_option',
		'priority'   => 200,
		
	) );
	
	$wp_customize->add_control( '_ak_logo_height', array(
	    'label' => __( 'Logo Height', '_ak' ),
		'type' => 'text',
		'settings' => '_ak_logo_height',
		'section' => 'header_settings_option',
		'priority'   => 200,
		'default' => 80,
	) );
}
add_action('customize_register', '_ak_header_panel');
function _ak_custom_css_output() {
	echo '<style type="text/css" id="custom-theme-css">' .
	get_theme_mod( '_ak_custom_css', '' ) . '</style>';
	echo '<style type="text/css" id="custom-plugin-css">' .
	get_option( '_ak_custom_css', '' ) . '</style>';
}
add_action( 'wp_head', '_ak_custom_css_output');