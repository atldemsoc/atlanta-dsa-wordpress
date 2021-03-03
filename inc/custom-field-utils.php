<?php
require_once 'constants.php';

function validate_duo_type($type) {
	if (!in_array($type, DUO_TYPES)) {
		throw new Exception("'$type' is not a valid duo type");
	}
}

function validate_duo_field($field) {
	if (!in_array($field, DUO_FIELDS)) {
		throw new Exception("'$field' is not a valid duo field");
	}
}

function validate_duo_slot($slot) {
	if (!in_array($slot, DUO_SLOTS)) {
		throw new Exception("'$slot' is not a valid duo slot");
	}
}

function validate_definition($definition) {
	foreach(DUO_FIELDS as $field) {
		if (
			!array_key_exists($field, $definition)
			|| empty($definition[$field])
		) {
			throw new Exception("'$field' is missing from defintion");
		}
	}
}

function get_duo_key($slot, $field) {
	validate_duo_slot($slot);
	validate_duo_field($field);

	return "duo_{$slot}_{$field}";
}

function get_duo_definition_by_slot($slot) {
	validate_duo_slot($slot);

	$definition = [];

	foreach(DUO_FIELDS as $field) {
		$definition[$field] = get_duo_key($slot, $field);
	}

	return $definition;
}

function generate_duo_config($templateName, $slot, $label, $defaultType = DUO_TYPE_PRIMARY) {
	$definition = get_duo_definition_by_slot($slot);

	return array(
		'key' => "duo_{$slot}_{$templateName}",
		'title' => $label,
		'fields' => array (
			array (
				'key' => 'field_' . $definition[DUO_FIELD_ENABLED],
				'label' => 'Enable Block',
				'name' => $definition[DUO_FIELD_ENABLED],
				'type' => 'true_false',
				'ui' => true,
				'default_value' => false,
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_BULMA_TYPE],
				'label' => 'Block Theme',
				'name' => $definition[DUO_FIELD_BULMA_TYPE],
				'type' => 'radio',
				'default_value' => $defaultType,
				'choices' => array(
					DUO_TYPE_PRIMARY => 'Red',
					DUO_TYPE_DARK => 'Dark',
					DUO_TYPE_LIGHT => 'Light',
				),
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_IMAGE],
				'label' => 'Image',
				'name' => $definition[DUO_FIELD_IMAGE],
				'type' => 'image',
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_TITLE],
				'label' => 'Title',
				'name' => $definition[DUO_FIELD_TITLE],
				'type' => 'text',
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_BODY],
				'label' => 'Body',
				'name' => $definition[DUO_FIELD_BODY],
				'type' => 'textarea',
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_CTA_LABEL],
				'label' => 'Call to action',
				'name' => $definition[DUO_FIELD_CTA_LABEL],
				'type' => 'text',
				'required' => 0,
			),
			array (
				'key' => 'field_' . $definition[DUO_FIELD_CTA_URL],
				'label' => 'Call to action link',
				'name' => $definition[DUO_FIELD_CTA_URL],
				'type' => 'link',
				'required' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => $templateName,
				),
			),
		),
	);
}

function get_slot_content($slot) {
	$definition = get_duo_definition_by_slot($slot);
	$hasEnoughContent = true;
	$content = [];

	foreach(DUO_FIELDS as $field) {
		$fieldContent = get_field($definition[$field]);

		if (
			empty($fieldContent)
			|| (
				$field === DUO_FIELD_IMAGE
				&& empty($fieldContent['url'])
			)
			|| (
				$field === DUO_FIELD_CTA_URL
				&& empty($fieldContent['url'])
			)
		) {
			$hasEnoughContent = false;
			break;
		}

		if ($field === DUO_FIELD_IMAGE || $field === DUO_FIELD_CTA_URL) {
			$content[$field] = $fieldContent['url'];
		} else {
			$content[$field] = $fieldContent;
		}
	}

	return $hasEnoughContent ? $content : null;
}

function get_header_hero_image_config($templateName) {
	return array(
		'key' => 'header_hero_image_' . $templateName,
		'title' => 'Header Block',
		'fields' => array (
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_IMAGE_KEY,
				'label' => 'Image',
				'name' => HEADER_HERO_IMAGE_IMAGE_KEY,
				'type' => 'image',
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_ALIGNMENT_KEY,
				'label' => 'Image Alignment',
				'name' => HEADER_HERO_IMAGE_ALIGNMENT_KEY,
				'type' => 'radio',
				'choices' => array(
					'is-left' => 'Left',
					'is-center' => 'Center',
					'is-right' => 'Right',
				)
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_SUPERTITLE_KEY,
				'label' => 'Card Supertitle',
				'name' => HEADER_HERO_IMAGE_SUPERTITLE_KEY,
				'type' => 'text',
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_TITLE_KEY,
				'label' => 'Card Title',
				'name' => HEADER_HERO_IMAGE_TITLE_KEY,
				'type' => 'text',
				'required' => 1,
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_SUBTITLE_KEY,
				'label' => 'Card Subtitle',
				'name' => HEADER_HERO_IMAGE_SUBTITLE_KEY,
				'type' => 'text',
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_BODY_KEY,
				'label' => 'Card Body',
				'name' => HEADER_HERO_IMAGE_BODY_KEY,
				'type' => 'textarea',
				'required' => 1,
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_CTA_LABEL_KEY,
				'label' => 'Call to action',
				'name' => HEADER_HERO_IMAGE_CTA_LABEL_KEY,
				'type' => 'text',
				'required' => 0,
			),
			array (
				'key' => 'field_' . HEADER_HERO_IMAGE_CTA_URL_KEY,
				'label' => 'Call to action link',
				'name' => HEADER_HERO_IMAGE_CTA_URL_KEY,
				'type' => 'link',
				'required' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => $templateName,
				),
			),
		),
	);
}

function get_header_duo_config($templateName) {
	return array(
		'key' => 'header_duo_'  . $templateName,
		'title' => 'Header Block',
		'fields' => array (
			array (
				'key' => 'field_' . HEADER_DUO_BULMA_TYPE,
				'label' => 'Block Theme',
				'name' => HEADER_DUO_BULMA_TYPE,
				'type' => 'radio',
				'default_value' => DUO_TYPE_LIGHT,
				'choices' => array(
					DUO_TYPE_PRIMARY => 'Red',
					DUO_TYPE_DARK => 'Dark',
					DUO_TYPE_LIGHT => 'Light',
				),
				'required' => 0,
			),
			array (
				'key' => 'field_' . HEADER_DUO_IMAGE_KEY,
				'label' => 'Image',
				'name' => HEADER_DUO_IMAGE_KEY,
				'type' => 'image',
				'required' => 1,
			),
			array (
				'key' => 'field_' . HEADER_DUO_SUPERTITLE_KEY,
				'label' => ' Supertitle',
				'name' => HEADER_DUO_SUPERTITLE_KEY,
				'type' => 'text',
			),
			array (
				'key' => 'field_' . HEADER_DUO_TITLE_KEY,
				'label' => ' Title',
				'name' => HEADER_DUO_TITLE_KEY,
				'type' => 'text',
				'required' => 1,
			),
			array (
				'key' => 'field_' . HEADER_DUO_SUBTITLE_KEY,
				'label' => ' Subtitle',
				'name' => HEADER_DUO_SUBTITLE_KEY,
				'type' => 'text',
			),
			array (
				'key' => 'field_' . HEADER_DUO_BODY_KEY,
				'label' => ' Body',
				'name' => HEADER_DUO_BODY_KEY,
				'type' => 'textarea',
				'required' => 1,
			),
			array (
				'key' => 'field_' . HEADER_DUO_CTA_LABEL_KEY,
				'label' => 'Call to action',
				'name' => HEADER_DUO_CTA_LABEL_KEY,
				'type' => 'text',
				'required' => 0,
			),
			array (
				'key' => 'field_' . HEADER_DUO_CTA_URL_KEY,
				'label' => 'Call to action link',
				'name' => HEADER_DUO_CTA_URL_KEY,
				'type' => 'link',
				'required' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => $templateName,
				),
			),
		),
	);
}
