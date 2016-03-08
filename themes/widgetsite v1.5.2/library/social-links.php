<?php
/*
* Adding a section to manage social links
*/
function widgetsite_sociallink_customizer( $wp_customize ) {
	$wp_customize->add_section( 'widgetsite_social_section' , array(
		'title' => __( 'Social Links', 'widgetsite' ),
		'priority' => 30,
		'description' => 'Add social links to website',
	) );

	$wp_customize->add_setting( 'widgetsite_facebook' );
	$wp_customize->add_setting( 'widgetsite_twitter' );
	$wp_customize->add_setting( 'widgetsite_pinterest' );
	$wp_customize->add_setting( 'widgetsite_linkedin' );

	$wp_customize->add_control( 'facebook', array(
		'type' => 'url',
		'label' => __( 'Facebook', 'widgetsite' ),
		'section' => 'widgetsite_social_section',
		'settings' => 'widgetsite_facebook',
	) );
	$wp_customize->add_control( 'twitter', array(
		'type' => 'url',
		'label' => __( 'Twitter', 'widgetsite' ),
		'section' => 'widgetsite_social_section',
		'settings' => 'widgetsite_twitter',
	) );
	$wp_customize->add_control( 'pinterest', array(
		'type' => 'url',
		'label' => __( 'Pinterest', 'widgetsite' ),
		'section' => 'widgetsite_social_section',
		'settings' => 'widgetsite_pinterest',
	) );
	$wp_customize->add_control( 'linkedin', array(
		'type' => 'url',
		'label' => __( 'Linkedin', 'widgetsite' ),
		'section' => 'widgetsite_social_section',
		'settings' => 'widgetsite_linkedin',
	) );
}
add_action('customize_register', 'widgetsite_sociallink_customizer');