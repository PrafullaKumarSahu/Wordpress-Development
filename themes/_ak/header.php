<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _tk
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php
	    if( $current_options[ 'upload_image_favicon' ] != '' ){
			?>
			<link rel="shortcut icon" href="<?php echo esc_url( ); ?>">
			<?php
		}
	?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php do_action( 'before' ); ?>

<div id="masthead" class="site-header" role="banner">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<nav class="navbar navbar-default" role="navigation">
		    <div class="container-fluid">
			    
				<div class="navbar-header">
					<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only"><?php _e('Toggle navigation','_tk') ?> </span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="logo pull-left">
					    <?php /* if( function_exists( 'jetpack_the_site_logo' ) ) {
									jetpack_the_site_logo();
						    } */
							if( get_theme_mod( '_ak_site_logo' ) ){
								//var_dump( get_theme_mod( '_ak_site_logo' ) );
								
								echo '<a href="' . home_url() . '" class="site-logo-link" rel="home">
    <img width="' . get_theme_mod( '_ak_logo_width', 200 ) . '" height="'. get_theme_mod( '_ak_logo_height', 100 ) .'" src="'. get_theme_mod( '_ak_site_logo' ) .'" class="_ak-logo attachment-mytheme-logo" alt="Company logo" data-size="_ak_site_logo">
</a>';
								
							}
							elseif( get_theme_mod('_ak_logo_text') ){
							?>
							<div class="qua_title_head">
							    <h1 class="qua-logo site-title">
								    <!-- Your site title as branding in the menu -->
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					               <p class="lead"><?php bloginfo( 'description' ); ?></p>
							</div>
							<?php
						} else { ?>
						 <a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo AK_TEMPLATE_DIR_URI;  ?>/images/logo.png"></a>
						<?php }?>
					</div>	
				</div>
				<?php
				    wp_nav_menu( array(
						'menu'              => 'primary',
						'theme_location'    => 'primary',
						'depth'             => 2,
						'container'         => 'div',
						'container_class'   => 'collapse navbar-collapse',
						'container_id'      => 'bs-example-navbar-collapse-1',
						'menu_class'        => 'nav navbar-nav',
						'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
						'walker'            => new wp_bootstrap_navwalker())
				    );
				?>
		    </div>
		</nav>
	</div><!-- .container -->
</div><!-- #masthead -->