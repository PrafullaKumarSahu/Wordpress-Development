<?php
// adds the link to Theme Options page to the menu under appearance (theme page)
add_action( 'admin_menu', 'add_manage_sections' );

function add_manage_sections()
{
    add_theme_page( 'Theme Options', 'Theme Options', 'manage_options', 'options','do_manage_theme_options' );
}


// this function is called when the theme options page is opened 
function do_manage_theme_options()
{
?>
    <div class="wrap">
		<div class="front-page-slider">
			<form method="post" action="options.php">
				<?php wp_nonce_field('update-options') ?>				
				<h2>Slider Settings</h2>
				<div class="options">
					<label for="slider-post-one"><strong>Slider 1</strong>

						<?php $posts = get_posts(array('posts_per_page' => -1)); ?>
					
						<select  id="slider-post-one" name="slider_post_one" value="<?php echo get_option( 'slider_post_one' ); ?>" >
							<?php $selected = get_option( 'slider_post_one' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($posts as $post) : ?>
								<option value="<?php echo $post->ID; ?>" <?php echo selected($post->ID, $selected); ?>>
									<?php echo $post->post_title; ?>
								</option>
							<?php endforeach; ?>						
						</select>		
					</label>
				</div>
				<div class="clear"></div>
				<div class="options">
					<label for="slider-post-two"><strong>Slider 2</strong>
				
						<?php $posts = get_posts(array('posts_per_page' => -1)); ?>
					
						<select  id="slider-post-two" name="slider_post_two" value="<?php echo get_option( 'slider_post_two' ); ?>" >
						<?php $selected = get_option( 'slider_post_two' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($posts as $post) : ?>
								<option value="<?php echo $post->ID; ?>" <?php echo selected($post->ID, $selected); ?>>
									<?php echo $post->post_title; ?>
								</option>
							<?php endforeach; ?>						
						</select>		
					</label>
				</div>
				<div class="clear"></div>
				<div class="options">
					<label for="slider-post-three"><strong>Slider 3</strong>
				
						<?php $posts = get_posts(array('posts_per_page' => -1)); ?>
					
						<select  id="slider-post-three" name="slider_post_three" value="<?php echo get_option( 'slider_post_three' ); ?>" >
					
						<?php $selected = get_option( 'slider_post_three' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($posts as $post) : ?>
								<option value="<?php echo $post->ID; ?>" <?php echo selected($post->ID, $selected); ?>>
									<?php echo $post->post_title; ?>
								</option>
							<?php endforeach; ?>						
						</select>		
					</label>
				</div>
				<div class="clear"></div>
				<div class="options">
					<label for="slider-post-four"><strong>Slider 4</strong>
				
						<?php $posts = get_posts(array('posts_per_page' => -1)); ?>
					
						<select  id="slider-post-four" name="slider_post_four" value="<?php echo get_option( 'slider_post_four' ); ?>" >
					
						<?php $selected = get_option( 'slider_post_four' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($posts as $post) : ?>
								<option value="<?php echo $post->ID; ?>" <?php echo selected($post->ID, $selected); ?>>
									<?php echo $post->post_title; ?>
								</option>
							<?php endforeach; ?>						
						</select>		
					</label>
				</div>
				<div class="clear"></div>
				<div class="option">
					<label for="slider-side-post-category"><strong>Show Slider Controls</strong>
						<input type="checkbox" name="show_slider_control" value="1"<?php checked( 1 == get_option('show_slider_control') ); ?> />
					</label>
				</div>
				<div class="clear"></div>
				<div class="option">
					<label for="slider-side-post-category"><strong>Slider Auto Transition</strong>
						<input type="checkbox" name="slider_auto_transition" value="1"<?php checked( 1 == get_option('slider_auto_transition') ); ?> />
					</label>
				</div>
				<div class="clear"></div>
				<div class="option">
					<label for="slider-side-post-category"><strong>Slider Speed</strong>
						<input type="text" name="slider_speed"  value="<?php echo get_option('slider_speed'); ?>" />
					</label>
				</div>
				<div class="clear"></div>
				<div class="options">
					<label for="slider-side-post-category"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
						<select  id="slider-side-post-category" name="slider_side_post_category" value="<?php echo get_option( 'slider_side_post_category' ); ?>" >
						<?php $selected = get_option( 'slider_side_post_category' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
							<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
								<?php echo $category->cat_name; ?>
							</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
				<div class="clear"> </div>
				<p class="submit"><input type="submit" class="button button-primary" value="Submit" /></p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="slider_post_one, slider_post_two ,slider_post_three, slider_post_four, slider_side_post_category, show_slider_control, slider_auto_transition, slider_speed" />
			</form>
		</div>
		
		<div class="front-page-section">
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>				
			<h2>Theme Options</h2>
			<h3>Front Page Sections</h3>
			<div class="options">
			
				<h4>Section One</h4>
				<div class="categories">
					<label for="section-category-one"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
				
						<select  id="section-category-one" name="selected_category_one" value="<?php echo get_option( 'category_one' ); ?>" >
						<?php $selected = get_option( 'selected_category_one' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
									<?php echo $category->cat_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			
				<div class="tags">
					<?php $tags = get_tags(); ?>
					<label for="section-tag-one"><strong>Tag</strong>
						<select id="section-tag-one" name="selected_tag_one" value="<?php echo get_option( 'tag_one' ); ?>" >
						<?php $selected = get_option( 'selected_tag_one' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($tags as $tag) : ?>
								<option value="<?php echo $tag->name; ?>" <?php echo selected($tag->name, $selected); ?>>
									<?php echo $tag->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			
			<div class="clear"></div>
			
			<div class="options">
				<h4>Section Two</h4>
				<div class="categories">
					<label for="section-category-two"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
				
						<select  id="section-category-two" name="selected_category_two" value="<?php echo get_option( 'category_two' ); ?>" >
						<?php $selected = get_option( 'selected_category_two' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
									<?php echo $category->cat_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			
				<div class="tags">
					<?php $tags = get_tags(); ?>
					<label for="section-tag-two"><strong>Tag</strong>
						<select id="section-tag-two" name="selected_tag_two" value="<?php echo get_option( 'tag_two' ); ?>" >
						<?php $selected = get_option( 'selected_tag_two' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($tags as $tag) : ?>
								<option value="<?php echo $tag->name; ?>" <?php echo selected($tag->name, $selected); ?>>
									<?php echo $tag->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			
			<div class="clear"></div>
			
			<div class="options">
				<h4>Section Three</h4>
				<div class="categories">
					<label for="section-category-three"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
				
						<select  id="section-category-three" name="selected_category_three" value="<?php echo get_option( 'category_three' ); ?>" >
						<?php $selected = get_option( 'selected_category_three' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
									<?php echo $category->cat_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			
				<div class="tags">
					<?php $tags = get_tags(); ?>
					<label for="section-tag-three"><strong>Tag</strong>
						<select id="section-tag-three" name="selected_tag_three" value="<?php echo get_option( 'tag_three' ); ?>" >
						<?php $selected = get_option( 'selected_tag_three' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($tags as $tag) : ?>
								<option value="<?php echo $tag->name; ?>" <?php echo selected($tag->name, $selected); ?>>
									<?php echo $tag->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			
			<div class="clear"></div>
			
			<div class="options">
				<h4>Section Four</h4>
				<div class="categories">
					<label for="section-category-four"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
				
						<select  id="section-category-four" name="selected_category_four" value="<?php echo get_option( 'category_four' ); ?>" >
						<?php $selected = get_option( 'selected_category_four' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
									<?php echo $category->cat_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			
				<div class="tags">
					<?php $tags = get_tags(); ?>
					<label for="section-tag-four"><strong>Tag</strong>
						<select id="section-tag-four" name="selected_tag_four" value="<?php echo get_option( 'tag_four' ); ?>" >
						<?php $selected = get_option( 'selected_tag_four' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($tags as $tag) : ?>
								<option value="<?php echo $tag->name; ?>" <?php echo selected($tag->name, $selected); ?>>
									<?php echo $tag->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			
			<div class="clear"></div>
			
			<div class="options">
				<h4>Section Five</h4>
				<div class="categories">
					<label for="section-category-five"><strong>Category</strong>
						<?php $categories = get_categories(); ?>
				
						<select  id="section-category-five" name="selected_category_five" value="<?php echo get_option( 'category_five' ); ?>" >
						<?php $selected = get_option( 'selected_category_five' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category->cat_name; ?>" <?php echo selected($category->cat_name, $selected); ?>>
									<?php echo $category->cat_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			
				<div class="tags">
					<?php $tags = get_tags(); ?>
					<label for="section-tag-five"><strong>Tag</strong>
						<select id="section-tag-five" name="selected_tag_five" value="<?php echo get_option( 'tag_five' ); ?>" >
						<?php $selected = get_option( 'selected_tag_five' ); ?>
							<option value="<?php echo get_option(''); ?>" selected="selected">Not Selected</option>
							<?php foreach ($tags as $tag) : ?>
								<option value="<?php echo $tag->name; ?>" <?php echo selected($tag->name, $selected); ?>>
									<?php echo $tag->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			
			<div class="clear"> </div>
			<p class="submit"><input type="submit" class="button button-primary" value="Submit" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="selected_category_one, selected_tag_one, selected_category_two, selected_tag_two, selected_category_three, selected_tag_three, selected_category_four, selected_tag_four, selected_category_five, selected_tag_five " />
        </form>
		</div>
    </div>
	
<?php
}

// enqueues the CSS for theme options page 
add_action( 'admin_init', 'widgetsite_enqueue_and_register_scripts' );

function widgetsite_enqueue_and_register_scripts(){
	wp_enqueue_style( 'theme-options', get_template_directory_uri().'/library/theme-options.css' );
}
?>