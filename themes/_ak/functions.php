<?php
/**
 * _tk functions and definitions
 *
 * @package _tk
 */
/**Includes reqired resources here**/
define('AK_TEMPLATE_DIR_URI', get_template_directory_uri());	
define('AK_TEMPLATE_DIR', get_template_directory());
define('AK_THEME_FUNCTIONS_PATH', AK_TEMPLATE_DIR.'/functions');	
define('QUALITY_THEME_OPTIONS_PATH', AK_TEMPLATE_DIR_URI.'/functions/theme_options');
 		
require_once( AK_THEME_FUNCTIONS_PATH . '/scripts/scripts.php');     //Theme Scripts And Styles	

require( AK_THEME_FUNCTIONS_PATH . '/resize_image/resize_image.php'); 
/**
 * Implement the Custom Header feature.
 */
require AK_TEMPLATE_DIR . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require AK_TEMPLATE_DIR . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require AK_TEMPLATE_DIR . '/includes/extras.php';

/**
 * Customizer additions.
 */
require AK_TEMPLATE_DIR . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require AK_TEMPLATE_DIR . '/includes/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require AK_TEMPLATE_DIR . '/includes/bootstrap-wp-navwalker.php';

//Customizer 
require( AK_THEME_FUNCTIONS_PATH . '/customizer/header-customizer.php');

require( AK_THEME_FUNCTIONS_PATH . '/customizer/footer-customizer.php');

require( AK_THEME_FUNCTIONS_PATH . '/customizer/banner-customizer.php');

require( AK_THEME_FUNCTIONS_PATH . '/customizer/service-customizer.php');

require( AK_THEME_FUNCTIONS_PATH . '/commentbox/comment-function.php');



//require_once( 'HelloAnalytics.php');     //Hello Analytic Test
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750; /* pixels */

if ( ! function_exists( '_tk_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function _tk_setup() {
	global $cap, $content_width;

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	/**
	 * Add default posts and comments RSS feed links to head
	*/
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	*/
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for Post Formats
	*/
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	*/
	add_theme_support( 'custom-background', apply_filters( '_tk_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	add_theme_support( 'site-logo', $args );
	
    /* add_theme_support( 'social-links', array(
        'facebook', 'twitter', 'linkedin', 'google_plus', 'tumblr',
    ) );
	 */
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _tk, use a find and replace
	 * to change '_tk' to the name of your theme in all the template files
	*/
	load_theme_textdomain( '_tk', AK_TEMPLATE_DIR . '/languages' );
	
	if ( ! isset( $content_width ) ) $content_width = 700;//In PX
	// Load text domain for translation-ready
	load_theme_textdomain( 'quality', QUALITY_THEME_FUNCTIONS_PATH . '/lang' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/
	register_nav_menus( array(
		'primary'  => __( 'Header bottom menu', '_tk' ),
	) );

}
endif; // _tk_setup
add_action( 'after_setup_theme', '_tk_setup' );

// Create a custom image size for Site Logo.
add_image_size( '_Ak-theme-logo', 200, 200 );
add_theme_support( 'site-logo' );
$args = array(
    'header-text' => array(
        'site-title',
        'site-description',
		'size' => '_Ak-theme-logo',
    ),
    'size' => 'medium',
);


/**
 * Register widgetized area and update sidebar with default widgets
 */
add_action( 'widgets_init', '_ak_widgets_init');
function _ak_widgets_init() {
/*sidebar*/
register_sidebar( array(
		'name' => __( 'Sidebar', '_ak' ),
		'id' => 'sidebar-1',
		'description' => __( 'The primary widget area', 'quality' ),
		'before_widget' => '<div class="qua_sidebar_widget" >',
		'after_widget' => '</div>',
		'before_title' => '<div class="qua_sidebar_widget_title"><h2>',
		'after_title' => '</h2><div class="qua-separator-small spacer"></div></div>',
	) );
}	

add_action( 'widgets_init', 'quality_child_widgets_init' );
function quality_child_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Contact Page Sidebar', 'quality-child' ),
        'id' => 'contact-page-sidebar',
        'description' => __( 'Widgets in this area will be shown on contact page.', 'quality-child' ),
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
    ) );
}

add_filter( 'jetpack_development_mode', '__return_true' );


//add_action('wp_footer', 'foo');
function foo(){
	//$abc = new GADWP_GAPI_Controller();
	//var_dump( $abc );
	/* $analytics = getService();
   $profile = getFirstProfileId($analytics);
   $results = getResults($analytics, $profile); */
  // printResults(printResults($results));
}