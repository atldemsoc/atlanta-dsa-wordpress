<?php if (
	get_field(HEADER_HERO_IMAGE_ALIGNMENT_KEY)
	&& get_field(HEADER_HERO_IMAGE_TITLE_KEY)
	&& get_field(HEADER_HERO_IMAGE_BODY_KEY)
):
	$imageUrl = get_field(HEADER_HERO_IMAGE_IMAGE_KEY) ? get_field(HEADER_HERO_IMAGE_IMAGE_KEY)['url'] : '';
	$ctaUrl = get_field(HEADER_HERO_IMAGE_CTA_URL_KEY) ? get_field(HEADER_HERO_IMAGE_CTA_URL_KEY)['url'] : '';

	// Background video is opt-in per template (e.g. template-home-v2.php passes array('video' => true))
	$mp4Url = '';
	$webmUrl = '';
	if (!empty($args['video'])) {
		$mp4Url = get_field(HEADER_HERO_VIDEO_MP4_KEY) ? get_field(HEADER_HERO_VIDEO_MP4_KEY)['url'] : '';
		$webmUrl = get_field(HEADER_HERO_VIDEO_WEBM_KEY) ? get_field(HEADER_HERO_VIDEO_WEBM_KEY)['url'] : '';
	}
	$hasVideo = $mp4Url || $webmUrl;

	?>

	<section
		class="hero--image <?= $hasVideo ? 'hero--video' : '' ?> hero is-medium is-halfheight is-primary <?php the_field(HEADER_HERO_IMAGE_ALIGNMENT_KEY) ?>"
		<?php if (get_field(HEADER_HERO_IMAGE_IMAGE_KEY)): ?>
			style="background-image:url('<?= esc_url($imageUrl) ?>')"
		<?php endif; ?>
	>
		<?php if ($hasVideo): ?>
			<video
				class="hero--video__media"
				autoplay
				muted
				loop
				playsinline
				preload="auto"
				aria-hidden="true"
				tabindex="-1"
				<?php if ($imageUrl): ?>
					poster="<?= esc_url($imageUrl) ?>"
				<?php endif; ?>
			>
				<?php if ($webmUrl): ?>
					<source src="<?= esc_url($webmUrl) ?>" type="video/webm">
				<?php endif; ?>
				<?php if ($mp4Url): ?>
					<source src="<?= esc_url($mp4Url) ?>" type="video/mp4">
				<?php endif; ?>
			</video>
		<?php endif; ?>
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
									class="button is-primary is-medium is-fullwidth"
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
