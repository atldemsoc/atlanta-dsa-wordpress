<?php if (
	get_field(HEADER_DUO_IMAGE_KEY)
	&& get_field(HEADER_DUO_TITLE_KEY)
	&& get_field(HEADER_DUO_BODY_KEY)
):
	$imageUrl = get_field(HEADER_DUO_IMAGE_KEY) ? get_field(HEADER_DUO_IMAGE_KEY)['url'] : '';
	$ctaUrl = get_field(HEADER_DUO_CTA_URL_KEY) ? get_field(HEADER_DUO_CTA_URL_KEY)['url'] : '';

	$type = get_field(HEADER_DUO_BULMA_TYPE);
	$heroClass = "is-{$type}";
	$buttonClass = $type === DUO_TYPE_PRIMARY ? 'is-dark' : 'is-primary';
	?>

	<div
		class="bernie__hero duo hero <?= $heroClass ?> is-halfheight"
	>
		<div class="hero-body container">
			<div class="duo__inner">
				<div class="duo__image">
					<figure class="image">
						<img src="<?= $imageUrl ?>"/>
					</figure>
				</div>
				<div class="duo__copy">

					<?php if (get_field(HEADER_DUO_SUPERTITLE_KEY)): ?>
						<div class="subtitle is-3"><?php the_field(HEADER_DUO_SUPERTITLE_KEY) ?></div>
					<?php endif; ?>

					<h1 class="title is-1"><?php the_field(HEADER_DUO_TITLE_KEY) ?></h1>

					<?php if (get_field(HEADER_DUO_SUBTITLE_KEY)): ?>
						<div class="subtitle is-3"><?php the_field(HEADER_DUO_SUBTITLE_KEY) ?></div>
					<?php endif; ?>

					<div class="content">
						<?php the_field(HEADER_DUO_BODY_KEY); ?>
					</div>

					<?php if (get_field(HEADER_DUO_CTA_LABEL_KEY) && get_field(HEADER_DUO_CTA_URL_KEY)): ?>
						<div class="buttons">
							<a
								href="<?= $ctaUrl ?>"
								class="button <?= $buttonClass ?> is-medium"
							>
								<?php the_field(HEADER_DUO_CTA_LABEL_KEY); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>

<?php endif; ?>
