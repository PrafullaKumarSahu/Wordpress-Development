<!-- AK Theme main slider -->
<div class="carousel">
    <?php if( get_theme_mod( '_ak_banner_image', AK_TEMPLATE_DIR_URI . '/images/slider/slide.jpg' ) ){
		echo '<img src="'. get_theme_mod( '_ak_banner_image', AK_TEMPLATE_DIR_URI . '/images/slider/slide.jpg'  ) .'" alt="_ak" class="img-responsive">';
	} ?>

	<div class="flex-slider-center">
	    <?php
		if( get_theme_mod( '_ak_banner_title', 'Theme Feature Goes Here!' ) ){
			echo "<h2>" . get_theme_mod( '_ak_banner_title', 'Theme Feature Goes Here!' ) . "</h2>";
		} ?>
		<?php if( get_theme_mod( '_ak_banner_sub_title', 'Wordpress Premium Theme!' ) ){
			echo "<div><span>" . get_theme_mod( '_ak_banner_sub_title', 'Wordpress Premium Theme!' ) . "</span></div>";
		} ?>
		<?php if( get_theme_mod( '_ak_banner_description','Wordpress Premium Theme!'  ) ){
			echo "<p>" . get_theme_mod( '_ak_banner_description', 'Wordpress Premium Theme!' ) . "</p>";
		} ?>
	</div>
</div>