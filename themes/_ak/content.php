<?php
/**
 * @package _tk
 */
?>


<?php // Styling Tip!

// Want to wrap for example the post content in blog listings with a thin outline in Bootstrap style?
// Just add the class "panel" to the article tag here that starts below.
// Simply replace post_class() with post_class('panel') and check your site!
// Remember to do this for all content templates you want to have this,
// for example content-single.php for the post single view. ?>

<div class="qua_blog_section" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="qua_post_date">
	    <span class="date"><?php echo get_the_date( 'j' ); ?></span>
		<h6><?php echo the_time( 'M' ); ?></h6>
	</div>
	
	<div class="qua_post_title_wrapper">
		<h2 class="page-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="clear"></div>
        <div class="qua_post_detail">
		    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><i class="fa fa-user"></i><?php echo get_the_author(); ?></a>
			<a href="<?php echo the_permalink(); ?>"><i class="fa fa-comments"></i><?php comments_number( 'No Comments', 'one comments', '% comments' ); ?></a>
			<?php if( get_the_tag_list() != '' ){
				?>
				<div class="qua_tags">
				    <i class="fa fa-tags"></i><a href="<?php the_permalink(); ?>"><?php the_tags( '', ', ', '<br />' ); ?></a>
				</div>
				<?php
			} ?>
		</div>
	</div>
</div>
<div class="qua_blog_section" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="clear"></div>
	<?php if ( is_search() || is_archive() ) : // Only display Excerpts for Search and Archive Pages ?>
	<div class="qua_blog-post_content">
	    
		<?php the_excerpt(); ?>
		<div class="qua_blog_post_img">
			<?php $default_arg = array( 'class' => "img-responsive" ); ?>
			<?php if( has_post_thumbnail() ): ?>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( '_ak_blog_img', $default_arg ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="qua_blog-post_content">
	    
		<?php the_content( __( 'Read More', '_tk' ) ); ?>
		<div class="qua_blog_post_img">
			<?php $default_arg = array( 'class' => "img-responsive" ); ?>
			<?php if( has_post_thumbnail() ): ?>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( '_ak_blog_img', $default_arg ); ?>
				</a>
			<?php endif; ?>
		</div>
		
		<?php
			wp_link_pages();
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

</div><!-- #post-## -->
