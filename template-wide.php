<?php
/*
Template Name: Wide
Template Post Type: page
*/
/**
 * Wide page: content spans the same width as the header and footer
 * (no sidebar), plus an optional extra side margin set per page.
 *
 * @package _s
 */

require_once('inc/constants.php');

// Optional per-page extra side margin, exposed to CSS as --wide-page-margin.
$wide_margin = function_exists('get_field') ? absint(get_field(PAGE_WIDE_MARGIN_KEY)) : 0;
$wide_margin = min($wide_margin, PAGE_WIDE_MARGIN_MAX);

get_header();
?>

	<div class="archive site__main container is-wide" style="--wide-page-margin: <?= $wide_margin ?>px">
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
