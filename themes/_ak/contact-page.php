<?php
/**
* Template Name: Contact Page
*/
?>
<?php 
  get_header(); ?>
<div class="container">
  <div class="row qua_blog_wrapper">
    <div class="col-md-12">
      <?php the_post(); ?>
	  <div class="qua_blog_section" >
        <div class="qua_blog_post_content row">
          <div id="jetpack-contact-form" class="widget widget_text col-xs-12 col-sm-12 col-md-6">
              <?php echo '<h2 class="widgettitle">' . get_the_title() . '</h2>'; ?>
              <?php the_content(); ?>
          </div>
          <div class="jetpack-map-address col-xs-12 col-sm-12 col-md-6">
	      <?php if ( is_active_sidebar( 'contact-page-sidebar' ) ) : ?>
		      <?php dynamic_sidebar( 'contact-page-sidebar' ); ?>
	      <?php endif; ?>
          </div>
       </div>
      </div>
	  <?php comments_template('',true); ?>
    </div>
    <?php /*get_sidebar();*/ ?>		
  </div>
</div>
<?php get_footer(); ?>