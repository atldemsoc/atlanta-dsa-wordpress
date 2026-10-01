<?php
require_once('constants.php');
require_once('custom-field-utils.php');

function my_acf_add_local_field_groups() {

	// The V2 (video hero) home template shares the home template's header and
	// block fields, so a page can be switched between the two without losing content.
	acf_add_local_field_group(with_page_template(get_header_hero_image_config('template-home.php'), 'template-home-v2.php'));
	acf_add_local_field_group(get_header_hero_video_config('template-home-v2.php'));
	acf_add_local_field_group(get_header_hero_second_button_config('template-home-v2.php'));

	acf_add_local_field_group(with_page_template(generate_duo_config('template-home.php', DUO_SLOT_PRIMARY, 'Block 1: Top'), 'template-home-v2.php'),  DUO_TYPE_DARK);
	acf_add_local_field_group(with_page_template(generate_duo_config('template-home.php', DUO_SLOT_SECONDARY, 'Block 2: Middle', DUO_TYPE_LIGHT), 'template-home-v2.php'));
	acf_add_local_field_group(with_page_template(generate_duo_config('template-home.php', DUO_SLOT_TERTIARY, 'Block 3: Bottom'), 'template-home-v2.php'), DUO_TYPE_PRIMARY);

	acf_add_local_field_group(get_header_duo_config('template-four-duos.php'));
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_PRIMARY, 'Block 1: Top'),  DUO_TYPE_PRIMARY);
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_SECONDARY, 'Block 2: Middle', DUO_TYPE_LIGHT));
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_TERTIARY, 'Block 3: Bottom'), DUO_TYPE_DARK);
}

add_action('acf/init', 'my_acf_add_local_field_groups');
