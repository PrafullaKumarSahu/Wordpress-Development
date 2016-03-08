<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package _tk
 */

get_header(); ?>
<div class="page-seperator"></div>
<div class="container">
  <div class="row">
    <div class="qua_page_heading">
      <h1><?php the_title(); ?></h1>
      <div class="qua-separator"></div>
    </div>
  </div>
</div>
<div class="container">
    <div class="row qua_blog_wrapper">
	    	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>



	<?php endwhile; // end of the loop. ?>
    <?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>
