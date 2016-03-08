<?php
/*
* Adding option to add background color and border to sidebar
*/
function widgetsite_sidebar_customizer( $wp_customize ) {
	$wp_customize->add_section( 'widgetsite_sidebar_section' , array(
		'title' => __( 'Sidebar Customization', 'widgetsite' ),
		'priority' => 40,
		'description' => 'Add colors to sidebar',
	) );

	$wp_customize->add_setting( 'sidebar_background_color');
	$wp_customize->add_setting( 'sidebar_border_color');

	$wp_customize->add_control( 
		new WP_Customize_Color_Control( //WP_Customize_Color_Control class to render the custom color selector control on the Theme Customizer
			$wp_customize, 
			'background-color', 
			array(
				'label'      => __( 'Background Color', 'widgetsite' ),
				'section'    => 'widgetsite_sidebar_section',
				'settings'   => 'sidebar_background_color',
			) ) 
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'border-color', 
			array(
				'label'      => __( 'Border Color', 'widgetsite' ),
				'section'    => 'widgetsite_sidebar_section',
				'settings'   => 'sidebar_border_color',
			) ) 
	);
}
add_action( 'customize_register', 'widgetsite_sidebar_customizer' );

/**
* Customizing the sidebars using the background color and border color
*/
add_action( 'wp_head', 'sidebar_style_customization' );
function sidebar_style_customization(){
	$background_color = get_theme_mod( 'sidebar_background_color' );
	$border_color = get_theme_mod( 'sidebar_border_color' );
	?>
	<style type="text/css">
		#right-sidebar .widget{
			<?php if ( $border_color ): ?>
				border: 1px solid <?php echo  $border_color; ?>;
				padding: 10px;
			<?php endif; ?>
			<?php if ( $background_color ): ?>
				background-color: <?php echo $background_color; ?>;
				<?php if ( !$border_color ): ?>
					padding: 10px;
				<?php endif; ?>
			<?php endif; ?>
		}
	</style>
	<?php
}