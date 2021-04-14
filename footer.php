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
				<a
					href="https://twitter.com/atldemsoc"
					target="_blank"
					rel="noopener"
					class="site-footer__social-item"
				>
					<div class="site-footer__social-item__icon">
						<span class="icon is-medium">
							<i class="fab fa-twitter fa-2x"></i>
						</span>
					</div>
					<div class="site-footer__social-item__label">ATL DSA Twitter</div>
				</a>
				<a
					href="https://www.instagram.com/atldemsoc/"
					target="_blank"
					rel="noopener"
					class="site-footer__social-item"
				>
					<div class="site-footer__social-item__icon">
						<span class="icon is-medium">
							<i class="fab fa-instagram fa-2x"></i>
						</span>
					</div>
					<div class="site-footer__social-item__label">ATL DSA Instagram</div>
				</a>
				<a
					href="https://www.facebook.com/atldemsoc/"
					target="_blank"
					rel="noopener"
					class="site-footer__social-item"
				>
					<div class="site-footer__social-item__icon">
						<span class="icon is-medium">
							<i class="fab fa-facebook fa-2x"></i>
						</span>
					</div>
					<div class="site-footer__social-item__label">ATL DSA Facebook</div>
				</a>
				<a
					href="http://www.dsausa.org/"
					target="_blank"
					rel="noopener"
					class="site-footer__social-item"
				>
					<div class="site-footer__social-item__icon">
						<span class="icon is-medium">
							<i class="fas fa-fist-raised fa-2x"></i>
						</span>
					</div>
					<div class="site-footer__social-item__label">National DSA</div>
				</a>
			</div>
			<div class="site-footer__copyright">&copy; <?= date('Y'); ?> Atlanta DSA</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<?php get_template_part( 'template-parts/analytics' ); ?>


</body>
</html>
