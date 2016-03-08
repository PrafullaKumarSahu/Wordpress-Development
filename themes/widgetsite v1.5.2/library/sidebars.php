<?php
class Widgetsite_Widget{

	function __construct(){
		add_action( 'init', array( $this, 'footer_section_one' ) );
		add_action( 'widgets_init', array( $this, 'add_footer_section_one' ), 10 );
		
		add_action( 'init', array( $this, 'footer_section_two' ) );
		add_action( 'widgets_init', array( $this, 'add_footer_section_two' ), 10 );
		
		add_action( 'init', array( $this, 'footer_section_three' ) );
		add_action( 'widgets_init', array($this, 'add_footer_section_three' ), 10 );
		
		add_action( 'init', array( $this, 'footer_section_four' ) );
		add_action( 'widgets_init', array( $this, 'add_footer_section_four' ), 10 );
		
	}
	
	//Register the footer section one
	function footer_section_one(){
		register_sidebar( array(
			'name' => __( 'Footer Section One', 'testscores' ),
			'id' => 'widgetsite-footer-section-one',
			'description' => __( 'Footer Section One', 'testscores' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s widgetsite-footer widgetsite-footer-section-one">',
			'after_widget' => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
	//Add footer section pne
	function add_footer_section_one(){
		dynamic_sidebar( 'widgetsite-footer-section-one');
	}
	
	//Register the footer section Two
	function footer_section_two(){
		register_sidebar( array(
			'name' => __( 'Footer Section Two', 'testscores' ),
			'id' => 'widgetsite-footer-section-two',
			'description' => __( 'Footer Section Two', 'testscores' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s widgetsite-footer widgetsite-footer-section-two">',
			'after_widget' => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	
	//Add footer section two 
	function add_footer_section_two(){
		dynamic_sidebar( 'widgetsite-footer-section-two' );
	}
	
	//Register the footer section Three
	function footer_section_three(){
		register_sidebar( array(
			'name' => __( 'Footer Section Three', 'testscores' ),
			'id' => 'widgetsite-footer-section-three',
			'description' => __( 'Footer Section Three', 'testscores' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s widgetsite-footer widgetsite-footer-section-three">',
			'after_widget' => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
	
	//Add footer section three
	function add_footer_section_three(){
		dynamic_sidebar( 'widgetsite-footer-section-three' );
	}
	
	//Register the footer section Four
	function footer_section_four(){
		register_sidebar( array(
			'name' => __( 'Footer Section Four', 'testscores' ),
			'id' => 'widgetsite-footer-section-four',
			'description' => __( 'Footer Section Four', 'testscores' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s widgetsite-footer widgetsite-footer-section-four">',
			'after_widget' => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
	
	//Add footer section four
	function add_footer_section_four(){
		dynamic_sidebar('widgetsite-footer-section-four');
	}
}
new Widgetsite_Widget();