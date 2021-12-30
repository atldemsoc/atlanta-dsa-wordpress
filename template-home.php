<?php
/*
Template Name: Hompage
*/
require_once('inc/constants.php');
get_header();

?>
<div class="home has-flipped-odd-duos">

	<?php get_template_part( 'template-parts/header', 'hero-image' ); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_PRIMARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_SECONDARY,
	)); ?>

	<?php get_template_partial('duo', array(
		'duoSlot' => DUO_SLOT_TERTIARY,
	)); ?>

	<!-- <div class="container section">
		<?php get_template_part( 'template-parts/recent-posts' ); ?>
	</div> -->

</div>

<?php get_footer(); ?>
