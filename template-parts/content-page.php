<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */
require_once get_template_directory() . '/inc/constants.php';

$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large', true);
$thumb_url = $thumb_url_array[0];
$has_thumb = $thumb_id && $thumb_url;

// Page templates pass a layout ('centered' or 'wide'); page.php passes none.
$layout = isset($args['layout']) ? $args['layout'] : '';
$layout_class = in_array($layout, array('centered', 'wide'), true) ? " is-{$layout}" : '';

// "Hide page title" keeps the <h1> for screen readers and search engines,
// but doesn't show it on the page.
$hide_title = function_exists('get_field') && get_field(PAGE_HIDE_TITLE_KEY);
$header_classes = array('content-page__header', 'container');
if (!$has_thumb) {
	$header_classes[] = 'without-image';
}
if ($hide_title) {
	$header_classes[] = 'screen-reader-text';
}
?>

<article id="post-<?php the_ID(); ?>" class="content-page<?= $layout_class ?>">
	<header class="<?= esc_attr(implode(' ', $header_classes)) ?>">
		<?php the_title( '<h1 class="title">', '</h1>' ); ?>
	</header><!-- .entry-header -->


	<?php if ($has_thumb): ?>
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

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', '_s' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', '_s' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
