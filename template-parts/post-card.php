<?php if($post):
	$id = is_array($post) ? $post['ID'] : $post->ID;
	$title = is_array($post) ? $post['post_title'] : $post->post_title;
	?>
	<a
		class="post-card card"
		href="<?php echo get_permalink($id) ?>"
	>
		<div class="card-image">
			<figure class="image is-4by3">
				<?php echo get_the_post_thumbnail($id, 'full'); ?>
			</figure>
		</div>
		<div class="card-content">
			<div class="title is-4"><?= $title ?></div>
			<div class="subtitle">
				<?= _s_posted_time_string(); ?>
			</div>
		</div>
	</a>
<?php endif; ?>
