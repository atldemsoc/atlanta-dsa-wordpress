<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

require_once('inc/constants.php');
get_header();
?>

	<div class="archive site__main container">
		<div class="site__content">
			<div class="section">

				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', 'page');

				endwhile; // End of the loop.
				?>

			</div>
		</div>
		<div class="site__sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>

<?php
get_footer();
