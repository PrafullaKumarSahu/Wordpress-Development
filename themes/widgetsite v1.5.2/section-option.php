<?php
add_action( 'admin_menu', 'add_manage_sections' );

function add_manage_sections()
{
    add_theme_page( 'Sections', 'Sections', 'manage_options', 'sections','do_manage_sections' );
}


function do_manage_sections()
{
?>
    <div class="wrap">
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>				
			<h2>Manage Your Post Sections</h2>
			<div class="sections">
			
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
			
			<div class="sections">
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
			
			<div class="sections">
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
			
			<div class="sections">
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
			
			<div class="sections">
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
			<p><input type="submit" name="Submit" value="submit" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="selected_category_one, selected_tag_one, selected_category_two, selected_tag_two, selected_category_three, selected_tag_three, selected_category_four, selected_tag_four, selected_category_five, selected_tag_five " />
        </form>
    </div>
	
<?php
}

add_action( 'admin_init', 'test_enqueue_and_register_scripts' );

function test_enqueue_and_register_scripts(){
	wp_enqueue_style( 'section-style', get_template_directory_uri().'/section-style.css' );
}
?>