<?php
/*
Template Name: Centered
Template Post Type: page
*/
/**
 * Like the default page, but without the sidebar column, so the content sits in the middle of the page.
 *
 * @package _s
 */

require_once('inc/constants.php');
get_header();
?>

	<div class="archive site__main container is-centered">
		<div class="site__content">
			<div class="section">

				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', 'page', array('layout' => 'centered'));

				endwhile; // End of the loop.
				?>

			</div>
		</div>
	</div>

<?php
get_footer();
