<?php
/*
Template Name: Small image header with 3 blocks
*/
require_once('inc/constants.php');
get_header();

?>
<div class="bernie has-flipped-even-duos">
	<?php get_template_part( 'template-parts/header', 'duo' ); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_PRIMARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_SECONDARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_TERTIARY,
	)); ?>

	<div class="section container post-content">

		<?php
		while (have_posts()) :
			the_post();
		?>

			<div class="content  post-content__content">
				<?php	the_content(); ?>
			</div>

		<?php
		endwhile; // End of the loop.
		?>

	</div>

</div>

<?php get_footer(); ?>
