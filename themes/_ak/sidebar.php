<?php
/**
 * The sidebar containing the main widget area
 *
 * @package _tk
 */
?>

<?php // add the class "panel" below here to wrap the sidebar in Bootstrap style ;) ?>


<?php do_action( 'before_sidebar' ); ?>
<?php if (  is_active_sidebar( 'sidebar-1' ) ) { ?>
		<div class="col-md-4 qua-sidebar">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
<?php } ?>

		<!-- close .sidebar-padder -->