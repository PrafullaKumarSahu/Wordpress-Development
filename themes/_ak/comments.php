<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to _tk_comment() which is
 * located in the includes/template-tags.php file.
 *
 * @package _tk
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) : ?>
<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'quality' ); ?></p>
<?php return; endif; ?>

	<div id="comments" class="comments-area">
    
	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
	<div class="qua_comment_section">
		<div class="qua_comment_title">
			<h3><i class="fa fa-comments"></i>
			  <?php echo comments_number('No Comments', '1 Comment', '% Comments'); ?>
			</h3>
		</div>
	      <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  ?>		
		   <?php endif; ?>
          <?php wp_list_comments( array( 'callback' => '_ak_comment' ) ); ?>
    </div>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="assistive-text screen-reader-text"><?php _e( 'Comment navigation', '_tk' ); ?></h1>
		
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', '_tk' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', '_tk' ) ); ?></div>
		
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', '_tk' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', '_tk' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', '_tk' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', '_tk' ); ?></p>
	<?php endif; ?>
<div class="qua_comment_form_section">
    <?php
	    $fields = array(
		    'author' => '<div class="qua_form_group"><label>' .__('Name','quality').'<small>*</small></label><input class="qua_con_input_control" name="author" id="author" value="" type="text"/></div>',
			'email' => '<div class="qua_form_group"><label>'.__('Email','quality').'<small>*</small></label><input class="qua_con_input_control" name="email" id="email" value=""   type="email" ></div>',	
		);
	    function my_fields( $fields ){
			return $fields;
		}
		add_filter( 'comment_form_default_fields', 'my_fields' );
	?>
	<?php comment_form( $args = array(
	          'fields' => apply_filters( 'comment_form_default_fields', $fields ),
			  'id_form'           => 'commentform',  // that's the wordpress default value! delete it or edit it ;)
			  'id_submit'         => 'commentsubmit',
			  'title_reply'=> '<h2>'.__( 'Leave a Reply','_ak').'</h2>',  // that's the wordpress default value! delete it or edit it ;)
			  'title_reply_to'    => __( 'Leave a Reply to %s', '_tk' ),  // that's the wordpress default value! delete it or edit it ;)
			  'cancel_reply_link' => __( 'Cancel Reply', '_tk' ),  // that's the wordpress default value! delete it or edit it ;)
			  'label_submit'      => __( 'Post Comment', '_tk' ),  // that's the wordpress default value! delete it or edit it ;)
              'logged_in_as' => '<p class="logged-in-as">' . __( "Logged in as ",'quality' ).'<a href="'. admin_url( 'profile.php' ).'">'.$user_identity.'</a>'. '<a href="'. wp_logout_url( get_permalink() ).'" title="Log out of this account">'.__(" Log out?",'quality').'</a>' . '</p>',
			  'comment_field' =>  '<div class="qua_form_group"><label>'.__('Comment','quality').'</label>
               <textarea id="comments" rows="5" class="qua_con_textarea_control" name="comment"></textarea></div>',

			  'comment_notes_after' => '',
			  'comment_notes_before'=>'',

			  // So, that was the needed stuff to have bootstrap basic styles for the form elements and buttons

			  // Basically you can edit everything here!
			  // Checkout the docs for more: http://codex.wordpress.org/Function_Reference/comment_form
			  // Another note: some classes are added in the bootstrap-wp.js - ckeck from line 1

	));

	?>
</div>
</div><!-- #comments -->
