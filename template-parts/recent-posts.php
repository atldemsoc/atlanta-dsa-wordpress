<div class="recent-posts">
	<div class="recent-posts__title title is-2 has-text-centered">Recent Posts</div>
	<div class="post-grid is-full">
		<?php
		$recent_posts = wp_get_recent_posts(array(
			'numberposts' => 6, // Number of recent posts thumbnails to display
			'post_status' => 'publish' // Show only the published posts
		));

		foreach ($recent_posts as $post) {
			get_template_part( 'template-parts/post-card');
		}

		wp_reset_query();
		?>
	</div>
	<div class="recent-posts__more buttons is-centered">
		<a
			href="/category/news"
			class="button is-primary is-large"
		>
			Show More
		</a>

	</div>
</div>
