<?php
/**
* Banner Setting Panel
*/
function _ak_banner_customizer( $wp_customize ){
	
	//Banner Panel
	$wp_customize->add_panel( '_ak_banner_setting', array(
	    'priority' => 500,
		'capability' => 'edit_theme_options',
		'title' => __( 'Banner Image Settings', '_ak' ),
	) );
	
	//Banner Section
	$wp_customize->add_section( '_ak_banner_section_settings', array(
	    'title' => __( 'Banner Image Settings', '_ak' ),
		'description' => '',
		'panel' => '_ak_banner_setting',
	) );
	
	//Banner image Settings
	$wp_customize->add_setting( '_ak_banner_image', array(
	    'default' => AK_TEMPLATE_DIR_URI . '/images/slider/slide.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	
	//Banner Image Control
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '_ak_banner_image', array(
		'label' => 'Image Upload',
		'section' => '_ak_banner_section_settings',
		'settings' => '_ak_banner_image',
	) ) );
	
	
	//Banner Title Setting
	$wp_customize->add_setting( '_ak_banner_title', array( 
		    'default' => 'Theme Feature Goes Here!',
			'sanitize_callback' => 'sanitize_text_field',
	) );

	//Banner Title Control
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, '_ak_banner_title', array(
	    'type' => 'text',
		'label' => __( 'Banner Title', '_ak' ),
		'section' => '_ak_banner_section_settings',
		'settings' => '_ak_banner_title',
	) ) );
	
 	
	//Banner Sub Title Setting
	$wp_customize->add_setting( '_ak_banner_sub_title', array( 
		    'default' => 'Wordpress Premium Theme!',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	

	//Banner Title Control
	$wp_customize->add_control(  new WP_Customize_Control( $wp_customize,'_ak_banner_sub_title', array(
	    'type' => 'text',
		'label' => __( 'Banner Sub Title', '_ak' ),
		'section' => '_ak_banner_section_settings',
		'settings' => '_ak_banner_sub_title',
	) ) );
	
	//Banner Description Setting	
	$wp_customize->add_setting( '_ak_banner_description', array( 
		    'default' => 'Wordpress Premium Theme!',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
 	//Banner Description  Control
	$wp_customize->add_control(  new WP_Customize_Control( $wp_customize,'_ak_banner_description', array(
			'type' => 'text',
			'label' => __( 'Banner Sub Title', '_ak' ),
			'section' => '_ak_banner_section_settings',
			'settings' => '_ak_banner_description',
	) ) );
	
}
add_action( 'customize_register', '_ak_banner_customizer' );
?>