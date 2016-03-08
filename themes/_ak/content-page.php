<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _tk
 */
?>
<div class="<?php if ( is_active_sidebar( 'sidebar-1' ) ){
			echo "col-md-8";
		} else { echo "col-md-12"; }?>">
		<div class="qua_post_date">
		    <span class="date"><?php echo get_the_date('j'); ?></span>
            <h6><?php echo the_time('M'); ?></h6>
		</div>

		<div class="clear"></div>
		<div class="qua_blog_post_content">
			<div class="qua_blog_section">
				<div class="qua_blog_post_img">
					<?php $default_arg = array( 'class' => 'img-responsive' ); ?>
					<?php if( has_post_thumbnail() ): ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( '_ak_blog_img', $default_arg  ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		    <?php the_content(); ?>

		</div>
	    <?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', true );
		?>
</div>

