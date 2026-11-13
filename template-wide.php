<?php
/*
Template Name: Wide
Template Post Type: page
*/
/**
 * Full-width page: content uses most of the page width (with side margins) and has no sidebar.
 *
 * @package _s
 */

require_once('inc/constants.php');
get_header();
?>

	<div class="archive site__main container is-wide">
		<div class="site__content">
			<div class="section">

				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', 'page', array('layout' => 'wide'));

				endwhile; // End of the loop.
				?>

			</div>
		</div>
	</div>

<?php
get_footer();
