<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package widgetsite
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body class="home blog custom-background">
	<?php do_action( 'before' ); ?>

<header id="masthead" class="site-header" role="banner">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div class="site-header-inner col-sm-12">

				<?php $header_image = get_header_image();
				if ( ! empty( $header_image ) ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
					</a>
				<?php } // end if ( ! empty( $header_image ) ) ?>
				<div class="site-branding col-xs-12 col-sm-6 col-md-6">				
					<?php if ( get_theme_mod( 'themes_logo' ) ) : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">	
							<div class='site-logo'>
								<img src='<?php echo esc_url( get_theme_mod( 'themes_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
							</div>
						</a>
					<?php else:  ?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">	
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<p class="lead"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div>
				<!-- Social Links and search -->
				<div class="social-search col-xs-12 col-sm-6 col-md-6">
					<!-- Social Links -->
					<div class="social-links">
						<div class="socials">
							<?php if ( get_theme_mod( 'widgetsite_linkedin' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'widgetsite_linkedin' ) ); ?>" class="linkedin" data-title="Linkedin" target="_blank"><span class="font-icon-social-linkedin"><i class="fa fa-lg fa-linkedin"></i></span></a>
							<?php endif;  ?>
							
							<?php if ( get_theme_mod( 'widgetsite_pinterest' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'widgetsite_pinterest' ) ); ?>" class="pinterest" data-title="Pinterest" target="_blank"><span class="font-icon-social-pinterest"><i class="fa fa-lg fa-pinterest"></i></span></a>                 
								
							<?php endif;  ?>
							<?php if ( get_theme_mod( 'widgetsite_twitter' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'widgetsite_twitter' ) ); ?>" class="twitter" data-title="Twitter" target="_blank"><span class="font-icon-social-twitter"><i class="fa fa-lg fa-twitter"></i></span></a>  
							<?php endif;  ?>
							
							<?php if ( get_theme_mod( 'widgetsite_facebook' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'widgetsite_facebook' ) ); ?>" class="facebook" data-title="Facebook" target="_blank"><span class="font-icon-social-facebook"><i class="fa fa-lg fa-facebook"></i></span></a>                        		    
							<?php endif;  ?>
						</div>
					</div> 
					<div class="clear"></div>
					<!-- Search -->
					<div class="widgetsite-search">
						<?php get_search_form(); ?>
					</div>
				</div>
				
			</div>
		</div>
	</div><!-- .container -->
</header><!-- #masthead -->

<nav class="site-navigation">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div class="site-navigation-inner">
				<div class="navbar navbar-default col-xs-12">
					<div class="navbar-header">
						<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only"><?php _e('Toggle navigation','widgetsite') ?> </span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">Menu</a>
					</div>
				<?php
					/**
					* Navigation Menu
					*/
					wp_nav_menu(
						array(
							'theme_location' 	=> 'primary',
							'depth'             => 2,
							'container'         => 'div',
							'container_class'   => 'collapse navbar-collapse',
							'menu_class' 		=> 'nav navbar-nav',
							'fallback_cb' 		=> 'wp_bootstrap_navwalker::fallback',
							'menu_id'			=> 'main-menu',
							'walker' 			=> new wp_bootstrap_navwalker(),
						)
					); ?>
					
				</div><!-- .navbar -->

			</div>
		</div>
	</div><!-- .container -->
</nav><!-- .site-navigation -->
<?php if ( is_home() || is_front_page() ) { // checks whether it's a home page or front page ?>
<div class="container">
	<div class="slider-wrap row">
		<div class="widgetsite-slider-section col-xs-12 col-md-8">
			<!-- Action hook to display slider -->
			<?php do_action( 'widgetsite_slider' ); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 beside-blocks">
		<?php
		/*
		* Slider side section
		*/	
		$original_query = $wp_query;
		$wp_query = null;
	
		// getting category selected for slider side section of front page
		if ( get_option( 'slider_side_post_category' ) ){
			$args = array( 'posts_per_page' => 4, 'category_name' => get_option('slider_side_post_category') );
			$section_one_heading = get_option( 'slider_side_post_category' );	
		} else {
		$args = array();
		} 
		$wp_query = new WP_Query( $args );
		?>
		<!-- displaying posts of  slider side section of front page -->
		<?php if ( have_posts() ) : ?>
			<ul class="widgetsite-section row thumbnails list-unstyled widgetsite-slider-side-section">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-6 col-sm-3 col-md-6">
					<?php the_post();?>
					<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail('cropped_medium');
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
			<?php endwhile; ?>
			</ul>
		<?php endif; ?>
		<?php
			$wp_query = null;
			$wp_query = $original_query;
			wp_reset_postdata();
		?>
		</div>
	</div>
</div>
<div clear></div>
<?php } ?>
<div class="main-content">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div id="content" class="main-content-inner col-md-8">