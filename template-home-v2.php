<?php
/*
Template Name: Homepage V2 (Video Hero)
*/
require_once('inc/constants.php');
get_header();

?>
<div class="home has-flipped-odd-duos">

	<?php get_template_part( 'template-parts/header', 'hero-video' ); ?>

	<div class="section container post-content">
		<div class="content post-content__content">
			<?php
			the_content();
			?>
		</div>
	</div>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_PRIMARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_SECONDARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_TERTIARY,
	)); ?>

</div>

<?php get_footer(); ?>
