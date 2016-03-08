<?php 
/**
* Template name: Full Width
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
    <div class="col-md-12">
        <?php 	while(have_posts()):the_post();
        global $more;
        $more = 0; ?>
        <div class="qua_blog_section" id="post-<?php the_ID(); ?>" >
		    <div class="qua_blog_post_img">
				<?php $default_arg = array( 'class' => 'img-responsive' ); ?>
				<?php if( has_post_thumbnail() ): ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'full-width-size', $default_arg  ); ?>
					</a>
				<?php endif; ?>
			</div>
            <?php the_content(); ?>
        </div>
        <?php endwhile ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>