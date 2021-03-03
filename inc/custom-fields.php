<?php
require_once('constants.php');
require_once('custom-field-utils.php');

function my_acf_add_local_field_groups() {

	acf_add_local_field_group(get_header_hero_image_config('template-home.php'));

	acf_add_local_field_group(generate_duo_config('template-home.php', DUO_SLOT_PRIMARY, 'Block 1: Top'),  DUO_TYPE_DARK);
	acf_add_local_field_group(generate_duo_config('template-home.php', DUO_SLOT_SECONDARY, 'Block 2: Middle', DUO_TYPE_LIGHT));
	acf_add_local_field_group(generate_duo_config('template-home.php', DUO_SLOT_TERTIARY, 'Block 3: Bottom'), DUO_TYPE_PRIMARY);

	acf_add_local_field_group(get_header_duo_config('template-four-duos.php'));
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_PRIMARY, 'Block 1: Top'),  DUO_TYPE_PRIMARY);
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_SECONDARY, 'Block 2: Middle', DUO_TYPE_LIGHT));
	acf_add_local_field_group(generate_duo_config('template-four-duos.php', DUO_SLOT_TERTIARY, 'Block 3: Bottom'), DUO_TYPE_DARK);
}

add_action('acf/init', 'my_acf_add_local_field_groups');
