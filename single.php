<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _s
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
?>

<div class="site__main container">
	<div class="site__content">
		<div class="section">

				<?php get_template_part( 'template-parts/content', get_post_type() ); ?>

		</div>
	</div>
	<div class="site__sidebar">
		<?php get_sidebar(); ?>
	</div>
</div>

<?php endwhile;	?>

<?php
get_footer();
