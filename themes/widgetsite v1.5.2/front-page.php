<?php
/*
* Template Name: Front Page
*/

get_header();
?>
<?php
/*
* section one
*/
	$original_query = $wp_query;
	$wp_query = null;
	
	// getting category or tag selected for section one of front page
	if ( get_option( 'selected_category_one' ) ){
		$args = array( 'posts_per_page' => 6, 'category_name' => get_option('selected_category_one') );
		$section_one_heading = get_option( 'selected_category_one' );
	} elseif ( get_option( 'selected_tag_one' ) ) {
		$args = array('posts_per_page' => 6, 'tag' => get_option( 'selected_tag_one' ));
		$section_one_heading = get_option('selected_tag_one');		
	} else {
		$args = array();
	} 
	$wp_query = new WP_Query( $args );
	?>
	
	<!-- displaying posts of section one -->
    <?php if ( have_posts() ) : ?>
		<h2 class="section-title"> <?php echo $section_one_heading; ?> </h2>
		<ul class="widgetsite-section row thumbnails list-unstyled widgetsite-section-one">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-12 col-sm-6 col-md-4">
					<?php the_post();?>
					<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail('cropped_medium');
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
		    <?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();

/*
* section two
*/
	$original_query = $wp_query;
	$wp_query = null;

	// getting category or tag selected for section two of front page
	if ( get_option( 'selected_category_two' ) ){
		$args = array('posts_per_page' => 6, 'category_name' => get_option( 'selected_category_two' ));
		$section_two_heading = get_option( 'selected_category_two' );		
	} elseif ( get_option( 'selected_tag_two' ) ) {
		$args = array('posts_per_page' => 6, 'tag' => get_option('selected_tag_two'));
		$section_two_heading = get_option( 'selected_tag_two' );
	} else {
		$args = array();
	} 
	$wp_query = new WP_Query( $args );
	?>
	
	<!-- displaying posts of section two -->
    <?php if ( have_posts() ) : ?>
		<h2 class="section-title"> <?php echo $section_two_heading; ?> </h2>
		<ul class="widgetsite-section row thumbnails list-unstyled widgetsite-section-two">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-12 col-sm-6 col-md-4">
					<?php the_post();?>
					<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail('cropped_medium');
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
		    <?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();

/*
* section three
*/	
	$original_query = $wp_query;
	$wp_query = null;
	
	// getting category or tag selected for section three of front page
	if ( get_option( 'selected_category_three' ) ){
		$args = array( 'posts_per_page' => 4, 'category_name' => get_option( 'selected_category_three' ));
		$section_three_heading = get_option( 'selected_category_three' );
	} elseif (get_option( 'selected_tag_three' )) {
		$args = array( 'posts_per_page' => 4, 'tag' => get_option( 'selected_tag_three' ));
		$section_three_heading = get_option( 'selected_tag_three' );
	} else {
		$args = array();
	} 
	$wp_query = new WP_Query( $args );
	?>
	
	<!-- displaying posts of section three -->
    <?php if ( have_posts() ) : ?>
		<h2 class="section-title"> <?php echo $section_three_heading; ?> </h2>
		<ul class="widgetsite-section row thumbnails list-unstyled widgetsite-section-three">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-6 col-sm-6 col-md-3">
					<?php the_post();?>
			 		<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail();
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
		    <?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();

/*
* section four
*/
	$original_query = $wp_query;
	$wp_query = null;
	
	// getting category or tag selected for section four of front page
	if ( get_option( 'selected_category_four' ) ){
		$args = array('posts_per_page' => 6, 'category_name' => get_option( 'selected_category_four' ));
		$section_four_heading = get_option( 'selected_category_four' );		
	} elseif ( get_option( 'selected_tag_four' )) {
		$args = array( 'posts_per_page' => 6, 'tag' => get_option( 'selected_tag_four' ));
		$section_four_heading = get_option( 'selected_tag_four' );
	} else {
		$args = array();
	} 
	$wp_query = new WP_Query( $args );
	?>
	
	<!-- displaying posts of section four -->
    <?php if ( have_posts() ) : ?>
		<h2 class="section-title"> <?php echo $section_four_heading; ?> </h2>
		<ul class="widgetsite-section row thumbnails list-unstyled widgetsite-section-four">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-6 col-sm-4 col-md-2">
					<?php the_post();?>
			 		<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail();
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
		    <?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();

/*
* section five
*/
	$original_query = $wp_query;
	$wp_query = null;
	
	// getting category or tag selected for section five of front page
	if ( get_option( 'selected_category_five' ) ){
		$args = array( 'posts_per_page' => 6, 'category_name' => get_option( 'selected_category_five' ));
		$section_five_heading = get_option( 'selected_category_five' );		
	} elseif (get_option( 'selected_tag_five' ) ) {
		$args = array( 'posts_per_page' => 6, 'tag' => get_option( 'selected_tag_five' ));
		$section_five_heading = get_option( 'selected_tag_five' );
	} else {
		$args = array();
	} 
	$wp_query = new WP_Query( $args );
	?>

	<!-- displaying posts of section five -->
    <?php if ( have_posts() ) : ?>
		<h2 class="section-title"> <?php echo $section_five_heading; ?> </h2>
		<ul class="widgetsite-section row thumbnails list-unstyled">
			<?php while ( have_posts() ) : ?>
				<li class="col-xs-6 col-sm-4 col-md-2 widgetsite-section-five">
					<?php the_post();?>
					<div id="post-<?php the_ID() ?>" >
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php 
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail();
							} else {
								echo '<img  src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/thumbnail-default.jpg" />';
							}
							?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</li>
		    <?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();
?>
<?php get_sidebar(); ?>
<?php
	get_footer();