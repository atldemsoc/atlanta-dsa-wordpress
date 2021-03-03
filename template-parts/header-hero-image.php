<?php if (
	get_field(HEADER_HERO_IMAGE_ALIGNMENT_KEY)
	&& get_field(HEADER_HERO_IMAGE_TITLE_KEY)
	&& get_field(HEADER_HERO_IMAGE_BODY_KEY)
):
	$imageUrl = get_field(HEADER_HERO_IMAGE_IMAGE_KEY) ? get_field(HEADER_HERO_IMAGE_IMAGE_KEY)['url'] : '';
	$ctaUrl = get_field(HEADER_HERO_IMAGE_CTA_URL_KEY) ? get_field(HEADER_HERO_IMAGE_CTA_URL_KEY)['url'] : '';

	?>

	<section
		class="hero--image hero is-medium is-halfheight is-primary <?php the_field(HEADER_HERO_IMAGE_ALIGNMENT_KEY) ?>"
		<?php if (get_field(HEADER_HERO_IMAGE_IMAGE_KEY)): ?>
			style="background-image:url('<?= $imageUrl ?>"
		<?php endif; ?>
	>
		<div class="hero-body">
			<div class="container hero--image__inner">
				<div class="card">
					<div class="card-content">
						<?php if (get_field(HEADER_HERO_IMAGE_SUPERTITLE_KEY)): ?>
							<div class="subtitle"><?php the_field(HEADER_HERO_IMAGE_SUPERTITLE_KEY) ?></div>
						<?php endif; ?>
						<h1 class="title"><?php the_field(HEADER_HERO_IMAGE_TITLE_KEY) ?></h1>
						<?php if (get_field(HEADER_HERO_IMAGE_SUBTITLE_KEY)): ?>
							<div class="subtitle"><?php the_field(HEADER_HERO_IMAGE_SUBTITLE_KEY) ?></div>
						<?php endif; ?>
						<div class="content">
							<p><?php the_field(HEADER_HERO_IMAGE_BODY_KEY); ?></p>
						</div>
						<?php if (get_field(HEADER_HERO_IMAGE_CTA_LABEL_KEY) && get_field(HEADER_HERO_IMAGE_CTA_URL_KEY)): ?>
							<div class="buttons">
								<a
									href="<?= $ctaUrl ?>"
									class="button is-dark is-medium is-fullwidth"
								>
									<?php the_field(HEADER_HERO_IMAGE_CTA_LABEL_KEY); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
