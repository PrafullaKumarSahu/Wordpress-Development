<?php
/**
* Widgetsite Slider
*/


// widgetsite_slider action hook to display the slider

add_action( 'widgetsite_slider', 'widgetsite_slider' );

function widgetsite_slider(){
	
	if( is_home() || is_front_page() ) {
		
		/**
		* Retrives the posts for the slides and slider control option from wp_options table using get_option()
		* and assigns stores in $widegetsite_slides array to use later
		*/
		
		$widegetsite_slides['slider_one'] = get_option('slider_post_one');
		$widegetsite_slides['slider_two'] = get_option('slider_post_two');
		$widegetsite_slides['slider_three'] = get_option('slider_post_three');
		$widegetsite_slides['slider_four'] = get_option('slider_post_four');
		$widegetsite_slides['show_slider_control'] = get_option('show_slider_control');
		$widegetsite_slides['slider_auto'] = get_option('slider_auto_transition');
		$widegetsite_slides['slider_speed'] = get_option('slider_speed');
		
		?>
		<?php  
		
		/**
		* Check if any slide is present set the slider controls, else slider will not appear
		*/
		
        if ( ( isset( $widegetsite_slides['slider_one'] ) && !empty( $widegetsite_slides['slider_one'] ) ) 
		|| ( isset( $widegetsite_slides['slider_two'] ) && !empty( $widegetsite_slides['slider_two'] ) ) 
		|| ( isset( $widegetsite_slides['slider_three'] ) && !empty( $widegetsite_slides['slider_three'] ) )
		|| ( isset( $widegetsite_slides['slider_four'] ) && !empty( $widegetsite_slides['slider_four'] ) )
		){
			// setting default values of these options if not set in the theme options
            $show_controls = $widegetsite_slides['show_slider_control'] ? 'true' : 'false' ;
            $slider_auto = $widegetsite_slides['slider_auto']  ? 'true' : 'false' ;
            $slider_speed = $widegetsite_slides['slider_speed'] ? $widegetsite_slides['slider_speed'] : 200; 
        ?>
			<script type="text/javascript">
				/**
				* set the control of slider
				*/
				jQuery(document).ready(function() {
                 
					jQuery('.home-bxslider').bxSlider( {
						speed:  <?php echo $slider_speed; ?>,
						auto: <?php echo $slider_auto; ?>,
						controls: <?php echo $show_controls; ?>,
						pager: false,
                        
					});
					
				})
			</script><?php
						
			/**
			* Add all the sliders to an array
			*/
			
			$sliders = array( $widegetsite_slides['slider_one'], $widegetsite_slides['slider_two'], $widegetsite_slides['slider_three'], $widegetsite_slides['slider_four'] );
			$remove = array( '' );	// to remove empty values from the array if options are not set
			$sliders = array_diff( $sliders, $remove ); ?>

			
			<div class="slider-section">
				<ul class="home-bxslider">	
				<?php
				foreach ( $sliders as $slider ) {
					/**
					* Make custom query to retrieve the post title, date of post and featured image
					*/
					$args = array (
						'p' => $slider
					);
					$slider_query = new WP_Query( $args );
					
					if ( $slider_query->have_posts() ):
					?>    
					<?php
						while ( $slider_query->have_posts() ): $slider_query->the_post();

							$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'home-slider' );
							$content = substr( get_the_content(), 0, 95 );
							$content = substr( $content, 0, strrpos( $content," " ) );
							
							if ( $image_url ):		// add the post of the slider only if the post has a featured image
							?>			
								<li>
									<a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url[0]; ?>" /></a>
									<div class="slider-desc">
										<div class="slide-date">
											<i class="fa fa-calendar"></i><?php echo get_the_date( 'F d, Y' ) ; ?>
										</div>	
										<div class="slider-details">
											<div class="slide-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>                                            
											<div class="slide-caption"><?php echo $content; ?></div>
										</div>
									</div>
								</li>
							<?php endif; ?>
							
					<?php endwhile; ?>    						    						
				<?php endif; ?>
				<?php 
				} 
				wp_reset_postdata(); ?>        
			</ul>
		</div>
		<?php
		}
	}
}
?>