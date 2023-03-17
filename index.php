<?php
/**
 * The template for displaying listy stuff
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

get_header();
?>

	<div class="archive site__main container">
		<div class="site__content">
			<div class="section">
				<?php the_archive_title( '<h1 class="title is-1">', '</h1>' ); ?>

				<?php if ( have_posts() ) : ?>

					<div class="">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/post-card');

						endwhile;
						?>
					</div>

					<?php

					bulma_pagination();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
		</div>
		<div class="site__sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>

<?php
get_footer();
