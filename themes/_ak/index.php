<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
    <div class="row qua_blog_wrapper">
	
	<?php if ( have_posts() ) : ?>

		<?php /* Start the Loop */ ?>
		<div class="<?php if( is_active_sidebar( 'sidebar-1' ) ){ echo "col-md-8"; } else { echo "col-md-12"; } ?>">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php
				/* Include the Post-Format-specific template for the content.
				 * If you want to overload this in a child theme then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				//get_template_part( 'content', get_post_format() );
			?>
            <div class="qua_blog_section" id="post-<?php the_ID(); ?>">
			    <div class="qua_blog_post_img">
				    <?php $default_arg = array( 'class' => 'img-responsive' ); ?>
					<?php if( has_post_thumbnail() ): ?>
					    <a href="<?php the_permalink(); ?>">
						    <?php the_post_thumbnail( '_ak_blog_img','$default_arg' ); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="qua_post_date">
				    <span class="date">
					    <?php echo get_the_date( 'j' ); ?>
					</span>
					<h6><?php the_time( 'M' ); ?></h6>
				</div>
				<div class="qua_post_title_wrapper">
				    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					
					<div class="qua_post_detail">
					    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><i class="fa fa-user"></i><?php get_the_author(); ?></a>
						<a href="<?php the_permalink(); ?>"><i class="fa fa-comments"></i><?php comments_number( 'No Comments', 'one comment', '% comments' ) ?></a>
						<?php if( get_the_tag_list() != '' ){
							?>
							<div class="qua_tags">
							    <i class="fa fa-tags"></i><?php the_tags( '', ', ', '<br />' ); ?>
							</div>
							<?php
						} ?>
						<?php if ( get_the_category_list() != '' ){
							?>
							<div class="qua_post_cats">
							    <i class="fa fa-group"></i>&nbsp;&nbsp;<?php the_category( ' ', ' ' ); ?>
							</div>
							<?php
						} ?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="qua_blog_post_content">
				    <?php the_content( __( 'Read More', '_ak' ) ); ?>
					<?php wp_link_pages(); ?>
				</div>
			</div>
		<?php endwhile; ?>

		<?php _tk_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'index' ); ?>

	<?php endif; ?>
	 <div class="qua_blog_pagination">
        <div class="qua_blog_pagi">					
          <?php if ( get_previous_posts_link() ): ?>
          <?php previous_posts_link(); ?>
          <?php endif; ?>
          <?php if ( get_next_posts_link() ): ?>
          <?php next_posts_link(); ?>
          <?php endif; ?>
        </div>
        <?php if(wp_link_pages()) { wp_link_pages();  } ?>
      </div>
    </div>
<?php get_sidebar(); ?>
   </div>
</div>
<?php get_footer(); ?>