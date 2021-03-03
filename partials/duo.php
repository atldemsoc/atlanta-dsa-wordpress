<?php
require_once get_template_directory() . '/inc/constants.php';
require_once get_template_directory() . '/inc/custom-field-utils.php';

if(!empty($duoSlot)):
	$content = get_slot_content($duoSlot);

	if($content !== null):
		$heroClass = "is-{$content[DUO_FIELD_BULMA_TYPE]}";
		$buttonClass = $content[DUO_FIELD_BULMA_TYPE] === DUO_TYPE_PRIMARY ? 'is-dark' : 'is-primary';
?>
	<div
		id="red-block"
		class="duo hero <?= $heroClass ?> is-halfheight"
	>
		<div class="hero-body container">
			<div class="duo__inner">
				<div class="duo__image">
					<img src="<?= $content[DUO_FIELD_IMAGE] ?>"
							 class="image"
					/>
				</div>
				<div class="duo__copy">
					<div class="title"><?= $content[DUO_FIELD_TITLE] ?></div>
					<div class="content">
						<p><?= $content[DUO_FIELD_BODY] ?></p>
					</div>
					<div class="buttons">
						<a
							href="<?= $content[DUO_FIELD_CTA_URL] ?>"
							class="button <?= $buttonClass ?> is-medium"
						>
							<?= $content[DUO_FIELD_CTA_LABEL] ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	endif;
endif;

