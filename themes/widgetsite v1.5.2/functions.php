<?php
/**
 * widgetsite functions and definitions
 *
 * @package widgetsite
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750; /* pixels */

if ( ! function_exists( 'widgetsite_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function widgetsite_setup() {
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
	add_theme_support( 'custom-background', apply_filters( 'widgetsite_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on widgetsite_setup, use a find and replace
	 * to change 'widgetsite_setup' to the name of your theme in all the template files
	*/
	load_theme_textdomain( 'widgetsite', get_template_directory() . '/languages' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/
	register_nav_menus( array(
		'primary'  => __( 'Main menu', 'widgetsite_setup' ),
	) );

}
endif; //end widgetsite_setup
add_action( 'after_setup_theme', 'widgetsite_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function widgetsite_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'widgetsite_setup' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'widgetsite_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function widgetsite_scripts() {
	
	// Import the necessary load bx slider CSS additions
	wp_enqueue_style( 'jquery.bxslider.min', get_template_directory_uri() . '/includes/resources/bxslider/jquery.bxslider.min.css' );

	// Import the necessary widgetsite Bootstrap WP CSS additions
	wp_enqueue_style( 'widgetsite-bootstrap-wp', get_template_directory_uri() . '/includes/css/bootstrap-wp.css' );

	// load bootstrap css
	wp_enqueue_style( 'widgetsite-bootstrap', get_template_directory_uri() . '/includes/resources/bootstrap/css/bootstrap.min.css' );

	// load Font Awesome css
	wp_enqueue_style( 'widgetsite-font-awesome', get_template_directory_uri() . '/includes/css/font-awesome.min.css', false, '4.1.0' );

	// load widgetsite styles
	wp_enqueue_style( 'widgetsite-style', get_stylesheet_uri() );

	// load bootstrap js
	wp_enqueue_script('widgetsite-bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.min.js', array('jquery') );

	// load bootstrap wp js
	wp_enqueue_script( 'widgetsite-bootstrapwp', get_template_directory_uri() . '/includes/js/bootstrap-wp.js', array('jquery') );

	wp_enqueue_script( 'widgetsite-skip-link-focus-fix', get_template_directory_uri() . '/includes/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	// load bx-slider js
	wp_enqueue_script ( 'jquery.bxslider.min', get_template_directory_uri() . '/includes/resources/bxslider/jquery.bxslider.min.js' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'widgetsite-keyboard-image-navigation', get_template_directory_uri() . '/includes/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

}
add_action( 'wp_enqueue_scripts', 'widgetsite_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/includes/bootstrap-wp-navwalker.php';

/**
* Load section option panel
*/
require get_template_directory() . '/library/theme-options.php';

/**
* Add new sidebars
*/
require get_template_directory() . '/library/sidebars.php';



/**
* Add logo
*/
require get_template_directory() . '/library/logo.php';


/**
* Add Social Links
*/
require get_template_directory() . '/library/social-links.php';


/**
* Sidebar Customisation feature
*/
require get_template_directory() . '/library/sidebar-customization.php';

/**
* Add Mobile Menu
*/
require get_template_directory() . '/library/mobile-navigation.php';


/**
* Add slider
*/
require get_template_directory() . '/library/widgetsite-slider.php';


/**
* Set default size for thumbnails
*/
set_post_thumbnail_size( 360, 270, true ); 


/**
* Set custom image size for images
*/

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'cropped_medium', 750, 562, true );
	add_image_size( 'home-slider', 760, 319, true ); 
}
