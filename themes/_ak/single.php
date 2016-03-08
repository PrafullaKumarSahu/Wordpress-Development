<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _tk
 */

get_header(); ?>
<div class="page-separator"></div>
<div class="container">
<div class="row">
<div class="qua_page_heading">
	<h1><?php the_title(); ?></h1>
    <div class="qua-separator"></div>
</div>
</div>
</div>
<div class="container">
<div class="row qua_blog_wrapper" >
	<?php while ( have_posts() ) : the_post(); ?>
	
		<?php get_template_part( 'content', 'single' ); ?>

	<?php endwhile; // end of the loop. ?>		

<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>