<?php
/**
* Service Setting Panel
*/
add_action( 'customize_register', '_ak_service_customizer' );
function _ak_service_customizer( $wp_customize ){
	
	//Service Section Panel	
	$wp_customize->add_panel( '_ak_service_panel', array(
	    'priority' => 600,
		'capability' => 'edit_theme_options',
		'title' => __( 'Service Settings', '_ak' ),
	) );
	
	$wp_customize->add_section( '_ak_service_section_head', array(
	    'title' => __( 'Service Heading', '_ak' ),
		'description' => '',
		'panel' => '_ak_service_panel',
		'priority' => 50,
	) );
	
	//Show and hide service section
	$wp_customize->add_setting( '_ak_service_enable', array( 
		    'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, '_service_enable', array(
	    'type' => 'checkbox',
		'label' => __( 'Enable Service Section On Home Page', '_ak' ),
		'section' => '_ak_service_section_head',
		'settings' => '_ak_service_enable',
	) ) );
	
	//Service title
	$wp_customize->add_setting( '_ak_service_title', array(
	    'default' => __( 'What We Do', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	
	
	$wp_customize->add_control( 'service_title', array(
	    'label' => __( 'Service Title', '_ak' ),
		'section' => '_ak_service_section_head',
		'settings' => '_ak_service_title',
		'type' => 'text',
	) );
	
	//Service Description	
	$wp_customize->add_setting( '_ak_service_description', array(
	    'default' => __( 'We provide best Wordpress Solution for your business.Thanks to our framework you will get more happy customers', '_ak' ),
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	
	$wp_customize->add_control( 'service_description', array(
	    'label' => __( 'Service Description', '_ak' ),
		'section' => '_ak_service_section_head',
		'settings' => '_ak_service_description',
		'type' => 'text',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	//Service Section One
	$wp_customize->add_section( '_ak_service_section_one', array(
	    'title' => __( 'Service Section One', '_ak' ),
		'panel' => '_ak_service_panel',
		'priority' => 100,
	) );
		
	$wp_customize->add_setting( '_ak_service_one_icon', array( 
		    'default' => 'fa fa-shield',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	
	$wp_customize->add_control(  new WP_Customize_Control( $wp_customize, 'service_one_icon', array(
	    'type' => 'text',
		'label' => __( 'Service Icon One', '_ak' ),
		'section' => '_ak_service_section_one',
		'settings' => '_ak_service_one_icon',
	) ) );
	
	$wp_customize->add_setting( '_ak_service_one_title', array(
	    'default' => __( 'Fully Responsive', '_Ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod'
	) );
	
	$wp_customize->add_control( 'service_one_title', array(
	    'label' => __( 'Service Title One', '_ak' ),
		'section' => '_ak_service_section_one',
		'settings' => '_ak_service_one_title',
		'type' => 'text',
	) );
	
	$wp_customize->add_setting( '_ak_service_one_text', array(
	    'default' => __( 'Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is ', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	
	$wp_customize->add_control( 'service_one_text', array(
	    'label' => __( 'Service Text One', '_ak' ),
		'section' => '_ak_service_section_one',
		'settings' => '_ak_service_one_text',
		'type' => 'text'
	) );
	
	
	//Second Service Section
	$wp_customize->add_section( '_ak_service_section_two', array(
	    'title' => __( 'Service Section Two', '_ak' ),
		'panel' => '_ak_service_panel',
		'priority' => 200,
	) );
	
	$wp_customize->add_setting( '_ak_service_two_icon', array(
	    'type' => 'theme_mod',
		'default' => 'fa fa-tablet',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'service_two_icon', array(
	    'label' => __( 'Service Icon Two', '_ak' ),
		'section' => '_ak_service_section_two',
		'settings' => '_ak_service_two_icon',
		'type' => 'text'
	) );
	
	$wp_customize->add_setting( '_ak_service_two_title', array(
	    'default' => __( 'SEO Friendly', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( 'service_two_title', array(
	    'label' => __( 'Title Two', '_ak' ),
		'section' => '_ak_service_section_two',
		'settings' => '_ak_service_two_title',
		'type' => 'text',
	) );
	$wp_customize->add_setting( '_ak_service_two_text', array(
	    'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consec tetur adipisicing elit dignissim dapid tumst.', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( 'service_two_text', array(
	    'label' => __( 'Service Text Two', '_ak' ),
		'section' => '_ak_service_section_two',
		'settings' => '_ak_service_two_text',
		'type' => 'text',
	) );
	
	//Third Service Section 
	$wp_customize->add_section( '_ak_service_section_three', array(
	    'title' => __( 'Service Section Three', '_ak' ),
		'panel' => '_ak_service_panel',
		'priority' => 300,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_setting( '_ak_service_three_icon', array(
	    'sanitize_callback' => 'sanitize_text_field',
		'default' => 'fa fa-edit',
		'capability' => 'edit_theme_options',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( 'service_three_icon', array(
	    'label' => __( 'Service Icon Three', '_ak' ),
		'section' => '_ak_service_section_three',
		'settings' => '_ak_service_three_icon',
		'type' => 'text',
	) );
	
	$wp_customize->add_setting( '_ak_service_three_title', array(
	    'default' => __( 'Easy Customization', '_Ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod'
	) );
	
	$wp_customize->add_control( 'service_three_title', array(
	    'label' => __( 'Service Title Three', '_ak' ),
		'section' => '_ak_service_section_three',
		'settings' => '_ak_service_three_title',
		'type' => 'text',
	) );
	
	$wp_customize->add_setting( '_ak_service_three_text', array(
	    'default' => __( 'fLorem Ipsum which looks reason able. The generated Lorem Ipsum is t', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	
	$wp_customize->add_control( 'service_three_text', array(
	    'label' => __( 'Service Text Three', '_ak' ),
		'section' => '_ak_service_section_three',
		'settings' => '_ak_service_three_text',
		'type' => 'text'
	) );
	
	
	//Fourth Service Section
	$wp_customize->add_section( '_ak_service_section_four', array(
	    'title' => __( 'Service Section Four', '_ak' ),
		'panel' => '_ak_service_panel',
		'priority' => 400,
	) );
	
	$wp_customize->add_setting( '_ak_service_four_icon', array(
	    'type' => 'theme_mod',
		'default' => 'fa fa-star-half-o',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'service_four_icon', array(
	    'label' => __( 'Service Icon Four', '_ak' ),
		'section' => '_ak_service_section_four',
		'settings' => '_ak_service_four_icon',
		'type' => 'text'
	) );
	
	$wp_customize->add_setting( '_ak_service_four_title', array(
	    'default' => __( 'Well Documentation', '_ak' ),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( 'service_four_title', array(
	    'label' => __( 'Title Four', '_ak' ),
		'section' => '_ak_service_section_four',
		'settings' => '_ak_service_four_title',
		'type' => 'text',
	) );
	$wp_customize->add_setting( '_ak_service_four_text', array(
	    'default' => __( 'Lorem Ipsum which looks reason able. The generated Lorem Ipsum is-o', '_ak' ),
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( 'service_four_text', array(
	    'label' => __( 'Service Text Four', '_ak' ),
		'section' => '_ak_service_section_four',
		'settings' => '_ak_service_four_text',
		'type' => 'text',
	) );
}