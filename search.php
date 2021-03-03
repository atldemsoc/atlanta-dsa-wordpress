<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package _s
 */

get_header();
?>

	<div class="archive site__main container">
		<div class="site__content">
			<div class="section">

				<?php if ( have_posts() ) : ?>
					<h1 class="title">
						<?php
						/* translators: %s: search query. */
						printf(esc_html__('Search Results for: %s', '_s'), '<span>' . get_search_query() . '</span>');
						?>
					</h1>

					<div class="post-grid">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/post-card' );

						endwhile;

						?>
					</div>

				<?php
					//the_posts_navigation();

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
