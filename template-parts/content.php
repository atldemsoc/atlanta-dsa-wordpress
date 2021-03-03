<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large', true);
$thumb_url = $thumb_url_array[0];
?>

<article class="post-content">
	<?php
	if ( is_singular() ) :
		the_title( '<h1 class="title">', '</h1>' );
	else :
		the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	endif; ?>

	<?php if ( 'post' === get_post_type() ) : ?>
		<div class="subtitle">
			<?php _s_posted_on(); ?>
			<?php _s_posted_by(); ?>
		</div>
	<?php endif; ?>

	<?php if ($thumb_id && $thumb_url): ?>
		<figure class="image is-16by9 post-content__figure">
			<img
				class="post-content__image"
				src="<?= $thumb_url ?>"
			/>
		</figure>
	<?php endif; ?>

	<div class="content post-content__content">
		<?php
		the_content();

		_s_entry_footer();
		?>
	</div>

		<footer class="entry-footer">
		</footer><!-- .entry-footer -->
</article>
