<?php
/*
Class Name: bulma_pagination
Description: Custom pagination using Bulma components (tested with Bulma 0.6.2 on Wordpress 4.9.4)
Version: 0.2
Author: Domenico Majorana, modified by Ben Webb
*/


function bulma_pagination() {
	global $wp_query;
	$big = 999999999; //I trust StackOverflow.
	$maxPages = 5;
	$total_pages = $wp_query->max_num_pages; //you can set a custom int value to this var
	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $total_pages,
		'prev_next' => false,
		'type'  => 'array',
		'prev_next'   => true,
		'prev_text'    => __( 'Previous', 'text-domain' ),
		'next_text'    => __( 'Next', 'text-domain'),
	) );

	if ( is_array( $pages ) ) {
		echo '<nav class="pagination" role="navigation" aria-label="pagination">';

		//Get current page
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var( 'paged' );

		//Disable Previous button if the current page is the first one
		if ($paged == 1) {
			echo '<a class="pagination-previous" disabled>Prev</a>';
		} else {
			echo '<a class="pagination-previous" href="' . get_previous_posts_page_link() . '">Prev</a>';
		}

		//Disable Next button if the current page is the last one
		if ($paged<$total_pages) {
			echo '<a class="pagination-next" href="' . get_next_posts_page_link() . '">Next</a>
      <ul class="pagination-list">';
		} else {
			echo '<a class="pagination-next" disabled>Next</a>
      <ul class="pagination-list">';
		}

		for ($i=1; $i<=$total_pages; $i++) {
			if ($i == $paged) {
				echo '<li><a class="pagination-link is-current" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
			} else {
				echo '<li><a class="pagination-link" href="'. get_pagenum_link($i) . '">' . $i . '</a></li>';
			}
		}

		echo '</ul>';
		echo '</nav>';
	}
}

?>
