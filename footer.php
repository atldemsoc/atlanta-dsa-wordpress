<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?>

	</div><!-- #content -->

	<footer class="site-footer footer">
		<div class="container">
<!--			<div class="title has-text-light has-text-centered">Stay in Touch!</div>-->
			<div class="site-footer__social-items">
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container' => false,
                    'items_wrap' => '%3$s',
                    'walker' => new footer_menu_walker()
                )); ?>
			</div>
			<div class="site-footer__copyright">&copy; <?= date('Y'); ?> Atlanta DSA</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<?php get_template_part( 'template-parts/analytics' ); ?>


</body>
</html>
