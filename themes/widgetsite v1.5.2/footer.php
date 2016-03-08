<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package widgetsite
 */
?>
			</div><!-- close .*-inner (main-content or sidebar, depending if sidebar is used) -->
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .main-content -->	

<footer id="colophon" class="site-footer" role="contentinfo">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div class="sidebar col-xs-12 col-sm-6 col-md-3">
				<div class="sidebar-padder">
					<!-- displays footer sidebar 1 -->
					<?php do_action( 'before_sidebar' ); ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('widgetsite-footer-section-one') ) : ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="sidebar col-xs-12 col-sm-6 col-md-3">
				<div class="sidebar-padder">
					<!-- displays footer sidebar 2 -->
					<?php do_action( 'before_sidebar' ); ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('widgetsite-footer-section-two') ) : ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="sidebar col-xs-12 col-sm-6 col-md-3">
				<div class="sidebar-padder">
					<!-- displays footer sidebar 3 -->
					<?php do_action( 'before_sidebar' ); ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('widgetsite-footer-section-three') ) : ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="sidebar col-xs-12 col-sm-6 col-md-3">
				<div class="sidebar-padder">
					<!-- displays footer sidebar 4 -->
					<?php do_action( 'before_sidebar' ); ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('widgetsite-footer-section-four') ) : ?>
					<?php endif; ?>
				</div>
			</div>		
		</div>

		<div class="row">
			<div class="site-footer-inner col-sm-12">

				<div class="site-info">
					<?php do_action( 'widgetsite_credits' ); ?>
					<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'widgetsite' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'widgetsite' ), 'WordPress' ); ?></a>
					<span class="sep"> | </span>
                    <a class="credits" href="http://indibits.com/" target="_blank" title="Themes and Plugins developed by Indibits" alt="Themes and Plugins developed by Indibits"><?php _e('Themes and Plugins developed by Indibits.','widgetsite') ?> </a>
				</div><!-- close .site-info -->

			</div>
		</div>
	</div><!-- close .container -->
</footer><!-- close #colophon -->
<?php wp_footer(); ?>

</body>
</html>
