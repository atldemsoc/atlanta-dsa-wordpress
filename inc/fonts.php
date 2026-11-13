<?php
/**
 * Site fonts, set in Customize > Typography: a font and a weight for body
 * text, headings, menus (header and footer) and buttons, plus "Uppercase"
 * toggles for headings, menus and buttons.
 *
 * Fonts set --site-font-<role> (and --site-<role>-stretch for condensed
 * fonts). Weights set --site-weight-<role> and add the body class
 * "has-<role>-weight"; uppercase adds "has-uppercase-<role>s". Unchanged
 * settings print nothing, so the theme's own styles apply. The CSS is in
 * style/_fonts.scss.
 *
 * Manifold DSA, Union Gothic and Geist ship with the theme (fonts/).
 * Styrene B is loaded from files named StyreneB-Regular, StyreneB-Bold and
 * StyreneB-Black (.woff2, .woff or .otf), either in
 * wp-content/uploads/fonts/styrene/ or uploaded to the Media Library.
 * Without them, choosing Styrene B shows Manifold DSA.
 *
 * @package _s
 */

const DSA_BODY_FONT_SETTING = 'dsa_body_font';
const DSA_HEADING_FONT_SETTING = 'dsa_heading_font';
const DSA_MENU_FONT_SETTING = 'dsa_menu_font';
const DSA_BUTTON_FONT_SETTING = 'dsa_button_font';
const DSA_HEADING_UPPERCASE_SETTING = 'dsa_heading_uppercase';
const DSA_MENU_UPPERCASE_SETTING = 'dsa_menu_uppercase';
const DSA_BUTTON_UPPERCASE_SETTING = 'dsa_button_uppercase';
const DSA_BODY_WEIGHT_SETTING = 'dsa_body_weight';
const DSA_HEADING_WEIGHT_SETTING = 'dsa_heading_weight';
const DSA_MENU_WEIGHT_SETTING = 'dsa_menu_weight';
const DSA_BUTTON_WEIGHT_SETTING = 'dsa_button_weight';
const DSA_WEIGHT_THEME_DEFAULT = 'default';
const DSA_FONT_DEFAULT = 'manifold';
const DSA_FONT_SAME_AS_BODY = 'same';
const DSA_STYRENE_CACHE_KEY = 'dsa_styrene_font_files';

const DSA_FONT_FALLBACK_STACK = 'BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif';

/**
 * Available fonts. 'body' => false means not offered as the body font.
 */
function dsa_font_choices() {
	return array(
		'manifold' => array(
			'label' => 'Manifold DSA',
			'stack' => '"ManifoldDSA", ' . DSA_FONT_FALLBACK_STACK,
			'body'  => true,
		),
		'styrene'  => array(
			'label' => 'Styrene B',
			// Shows Manifold DSA when the Styrene files aren't found.
			'stack' => '"Styrene B Web", "ManifoldDSA", ' . DSA_FONT_FALLBACK_STACK,
			'body'  => true,
		),
		'geist'    => array(
			'label' => 'Geist Sans',
			'stack' => '"Geist Web", ' . DSA_FONT_FALLBACK_STACK,
			'body'  => true,
		),
		'union'    => array(
			'label'   => 'Union Gothic',
			'stack'   => '"Union Gothic Web", "Arial Narrow", sans-serif',
			// Condensed width, as on socialists.nyc.
			'stretch' => '83%',
			'body'    => false,
		),
	);
}

function dsa_sanitize_body_font( $value ) {
	$choices = dsa_font_choices();
	return ( isset( $choices[ $value ] ) && $choices[ $value ]['body'] ) ? $value : DSA_FONT_DEFAULT;
}

/** Heading, menu and button fonts: a font key, or "same as body". */
function dsa_sanitize_secondary_font( $value ) {
	return ( DSA_FONT_SAME_AS_BODY === $value || array_key_exists( $value, dsa_font_choices() ) )
		? $value
		: DSA_FONT_SAME_AS_BODY;
}

function dsa_font_weight_choices() {
	return array(
		300 => __( 'Light (300)', '_s' ),
		400 => __( 'Regular (400)', '_s' ),
		500 => __( 'Medium (500)', '_s' ),
		600 => __( 'Semibold (600)', '_s' ),
		700 => __( 'Bold (700)', '_s' ),
		800 => __( 'Extra bold (800)', '_s' ),
		900 => __( 'Black (900)', '_s' ),
	);
}

/** A weight, or "default" for the theme's own weights. */
function dsa_sanitize_optional_weight( $value ) {
	return ( DSA_WEIGHT_THEME_DEFAULT !== $value && array_key_exists( (int) $value, dsa_font_weight_choices() ) )
		? (int) $value
		: DSA_WEIGHT_THEME_DEFAULT;
}

/** Saved weights: array( 'body' | 'heading' | 'menu' | 'button' => int|'default' ). */
function dsa_get_font_weights() {
	return array(
		'body'    => dsa_sanitize_optional_weight( get_theme_mod( DSA_BODY_WEIGHT_SETTING, DSA_WEIGHT_THEME_DEFAULT ) ),
		'heading' => dsa_sanitize_optional_weight( get_theme_mod( DSA_HEADING_WEIGHT_SETTING, DSA_WEIGHT_THEME_DEFAULT ) ),
		'menu'    => dsa_sanitize_optional_weight( get_theme_mod( DSA_MENU_WEIGHT_SETTING, DSA_WEIGHT_THEME_DEFAULT ) ),
		'button'  => dsa_sanitize_optional_weight( get_theme_mod( DSA_BUTTON_WEIGHT_SETTING, DSA_WEIGHT_THEME_DEFAULT ) ),
	);
}

/* ------------------------------------------------------------------------
 * Styrene B files
 * --------------------------------------------------------------------- */

const DSA_STYRENE_WEIGHTS = array( 400 => 'Regular', 700 => 'Bold', 900 => 'Black' );
const DSA_FONT_FORMATS = array( 'woff2' => 'woff2', 'woff' => 'woff', 'otf' => 'opentype' );

/**
 * Styrene B files keyed by weight: uploads/fonts/styrene/ first, then the
 * Media Library. Each weight uses the first format found (woff2, woff, otf).
 * Cached for a day; uploading or deleting media, or opening the Customizer,
 * clears the cache.
 */
function dsa_styrene_font_files() {
	$cached = get_transient( DSA_STYRENE_CACHE_KEY );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$files   = array();
	$uploads = wp_upload_dir( null, false );

	if ( empty( $uploads['error'] ) ) {
		$dir = trailingslashit( $uploads['basedir'] ) . 'fonts/styrene/';
		$url = trailingslashit( $uploads['baseurl'] ) . 'fonts/styrene/';

		foreach ( DSA_STYRENE_WEIGHTS as $weight => $name ) {
			foreach ( DSA_FONT_FORMATS as $ext => $format ) {
				$file = "StyreneB-{$name}.{$ext}";
				if ( file_exists( $dir . $file ) ) {
					$files[ $weight ] = array( 'url' => $url . $file, 'format' => $format );
					break;
				}
			}
		}
	}

	if ( count( $files ) < count( DSA_STYRENE_WEIGHTS ) ) {
		$files += dsa_styrene_media_library_files();
		ksort( $files );
	}

	set_transient( DSA_STYRENE_CACHE_KEY, $files, DAY_IN_SECONDS );
	return $files;
}

/**
 * Styrene B files uploaded to the Media Library. WordPress may add a suffix
 * such as "-1" to the file name if the same name was uploaded before.
 */
function dsa_styrene_media_library_files() {
	$attachments = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 30,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'     => '_wp_attached_file',
				'value'   => 'StyreneB-',
				'compare' => 'LIKE',
			),
		),
	) );

	$found = array();
	foreach ( $attachments as $attachment ) {
		$file = basename( (string) get_post_meta( $attachment->ID, '_wp_attached_file', true ) );
		if ( ! preg_match( '/^StyreneB-(Regular|Bold|Black)(?:-\d+)?\.(woff2|woff|otf)$/i', $file, $m ) ) {
			continue;
		}
		$weight = array_search( ucfirst( strtolower( $m[1] ) ), DSA_STYRENE_WEIGHTS, true );
		$ext    = strtolower( $m[2] );
		$rank   = array_search( $ext, array_keys( DSA_FONT_FORMATS ), true );
		// Prefer the best format; among equal formats, the newest upload.
		if ( ! isset( $found[ $weight ] ) || $rank < $found[ $weight ]['rank'] ) {
			$found[ $weight ] = array(
				'url'    => wp_get_attachment_url( $attachment->ID ),
				'format' => DSA_FONT_FORMATS[ $ext ],
				'rank'   => $rank,
			);
		}
	}

	foreach ( $found as $weight => $file ) {
		unset( $found[ $weight ]['rank'] );
	}
	return $found;
}

function dsa_clear_styrene_cache() {
	delete_transient( DSA_STYRENE_CACHE_KEY );
}
add_action( 'add_attachment', 'dsa_clear_styrene_cache' );
add_action( 'delete_attachment', 'dsa_clear_styrene_cache' );
add_action( 'customize_register', 'dsa_clear_styrene_cache', 1 );

/**
 * Let administrators upload font files (.woff2, .woff, .otf) to the Media
 * Library, so the Styrene B files can be added from the WordPress admin.
 */
function dsa_allow_font_uploads( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['woff2'] = 'font/woff2';
		$mimes['woff']  = 'font/woff';
		$mimes['otf']   = 'font/otf';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'dsa_allow_font_uploads' );

/**
 * WordPress also checks a file's real type, which servers report in many
 * ways for fonts (e.g. "application/vnd.ms-opentype"), and then rejects it.
 * For administrators, accept a font extension when the detected type is one
 * a font file can have.
 */
function dsa_check_font_filetype( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $data;
	}
	$ext   = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	$fonts = array( 'woff2' => 'font/woff2', 'woff' => 'font/woff', 'otf' => 'font/otf' );
	if ( ! isset( $fonts[ $ext ] ) ) {
		return $data;
	}

	$font_types = array(
		'font/woff2', 'font/woff', 'font/otf', 'font/sfnt', 'font/ttf',
		'application/font-woff', 'application/font-woff2', 'application/font-sfnt',
		'application/x-font-otf', 'application/vnd.ms-opentype', 'application/octet-stream',
	);
	if ( function_exists( 'finfo_open' ) && is_readable( $file ) ) {
		$finfo     = finfo_open( FILEINFO_MIME_TYPE );
		$real_type = $finfo ? finfo_file( $finfo, $file ) : false;
		if ( $finfo ) {
			finfo_close( $finfo );
		}
		if ( $real_type && ! in_array( $real_type, $font_types, true ) ) {
			return $data; // Not a font file: leave WordPress's rejection.
		}
	}

	$data['ext']  = $ext;
	$data['type'] = $fonts[ $ext ];
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'dsa_check_font_filetype', 10, 4 );

/* ------------------------------------------------------------------------
 * Customizer
 * --------------------------------------------------------------------- */

function dsa_customize_register_fonts( $wp_customize ) {
	$choices = dsa_font_choices();

	$body_choices = array();
	$other_choices = array( DSA_FONT_SAME_AS_BODY => __( 'Same as body font', '_s' ) );
	foreach ( $choices as $key => $font ) {
		if ( $font['body'] ) {
			$body_choices[ $key ] = $font['label'];
		}
		$other_choices[ $key ] = $font['label'];
	}

	$styrene_note = dsa_styrene_font_files() ? '' : __( 'Styrene B files not found; it will show Manifold DSA.', '_s' );

	$wp_customize->add_setting( DSA_BODY_FONT_SETTING, array(
		'default'           => DSA_FONT_DEFAULT,
		'sanitize_callback' => 'dsa_sanitize_body_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_BODY_FONT_SETTING, array(
		'priority'    => 10,
		'section'     => 'dsa_typography',
		'label'       => __( 'Body font', '_s' ),
		'description' => $styrene_note,
		'type'        => 'select',
		'choices'     => $body_choices,
	) );

	$wp_customize->add_setting( DSA_HEADING_FONT_SETTING, array(
		'default'           => DSA_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_secondary_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_HEADING_FONT_SETTING, array(
		'priority'    => 12,
		'section'     => 'dsa_typography',
		'label'       => __( 'Heading font', '_s' ),
		'type'        => 'select',
		'choices'     => $other_choices,
	) );

	$wp_customize->add_setting( DSA_HEADING_UPPERCASE_SETTING, array(
		'default'           => false,
		'sanitize_callback' => 'dsa_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_HEADING_UPPERCASE_SETTING, array(
		'priority'    => 14,
		'section' => 'dsa_typography',
		'label'   => __( 'Uppercase headings', '_s' ),
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( DSA_MENU_FONT_SETTING, array(
		'default'           => DSA_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_secondary_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_MENU_FONT_SETTING, array(
		'priority'    => 15,
		'section'     => 'dsa_typography',
		'label'       => __( 'Menu font', '_s' ),
		'description' => __( 'Also used for the name next to the logo.', '_s' ),
		'type'        => 'select',
		'choices'     => $other_choices,
	) );

	// Font weights.
	$weights      = dsa_font_weight_choices();
	$theme_weight = array( DSA_WEIGHT_THEME_DEFAULT => __( 'Theme default', '_s' ) ) + $weights;

	$weight_controls = array(
		DSA_BODY_WEIGHT_SETTING    => array(
			'label'       => __( 'Body weight', '_s' ),
			'default'     => DSA_WEIGHT_THEME_DEFAULT,
			'sanitize'    => 'dsa_sanitize_optional_weight',
			'choices'     => $theme_weight,
			'priority'    => 11,
		),
		DSA_HEADING_WEIGHT_SETTING => array(
			'label'       => __( 'Heading weight', '_s' ),
			'default'     => DSA_WEIGHT_THEME_DEFAULT,
			'sanitize'    => 'dsa_sanitize_optional_weight',
			'choices'     => $theme_weight,
			'priority'    => 13,
		),
		DSA_MENU_WEIGHT_SETTING    => array(
			'label'       => __( 'Menu weight', '_s' ),
			'default'     => DSA_WEIGHT_THEME_DEFAULT,
			'sanitize'    => 'dsa_sanitize_optional_weight',
			'choices'     => $theme_weight,
			'priority'    => 16,
		),
		DSA_BUTTON_WEIGHT_SETTING  => array(
			'label'       => __( 'Button weight', '_s' ),
			'default'     => DSA_WEIGHT_THEME_DEFAULT,
			'sanitize'    => 'dsa_sanitize_optional_weight',
			'choices'     => $theme_weight,
			'priority'    => 19,
		),
	);

	$wp_customize->add_setting( DSA_MENU_UPPERCASE_SETTING, array(
		'default'           => false,
		'sanitize_callback' => 'dsa_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_MENU_UPPERCASE_SETTING, array(
		'section'  => 'dsa_typography',
		'label'    => __( 'Uppercase menus', '_s' ),
		'type'     => 'checkbox',
		'priority' => 17,
	) );

	$wp_customize->add_setting( DSA_BUTTON_FONT_SETTING, array(
		'default'           => DSA_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_secondary_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_BUTTON_FONT_SETTING, array(
		'section'  => 'dsa_typography',
		'label'    => __( 'Button font', '_s' ),
		'type'     => 'select',
		'choices'  => $other_choices,
		'priority' => 18,
	) );

	$wp_customize->add_setting( DSA_BUTTON_UPPERCASE_SETTING, array(
		'default'           => false,
		'sanitize_callback' => 'dsa_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_BUTTON_UPPERCASE_SETTING, array(
		'section'  => 'dsa_typography',
		'label'    => __( 'Uppercase buttons', '_s' ),
		'type'     => 'checkbox',
		'priority' => 20,
	) );

	foreach ( $weight_controls as $id => $control ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $control['default'],
			'sanitize_callback' => $control['sanitize'],
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( $id, array(
			'section'     => 'dsa_typography',
			'label'       => $control['label'],
			'type'        => 'select',
			'choices'     => $control['choices'],
			'priority'    => $control['priority'],
		) );
	}
}
// After the Typography section is registered in inc/customizer.php.
add_action( 'customize_register', 'dsa_customize_register_fonts', 20 );

/* ------------------------------------------------------------------------
 * Output
 * --------------------------------------------------------------------- */

/**
 * CSS variable declarations for the chosen fonts ('' if all defaults).
 */
function dsa_font_css_vars( $body_key, $heading_key, $menu_key = DSA_FONT_SAME_AS_BODY, $button_key = DSA_FONT_SAME_AS_BODY ) {
	$choices = dsa_font_choices();
	$vars    = array();

	if ( DSA_FONT_DEFAULT !== $body_key ) {
		$vars[] = '--site-font-body:' . $choices[ $body_key ]['stack'];
	}

	foreach ( array( 'heading' => $heading_key, 'menu' => $menu_key, 'button' => $button_key ) as $role => $key ) {
		if ( DSA_FONT_SAME_AS_BODY === $key ) {
			continue;
		}
		$vars[] = "--site-font-{$role}:" . $choices[ $key ]['stack'];
		if ( ! empty( $choices[ $key ]['stretch'] ) ) {
			$vars[] = "--site-{$role}-stretch:" . $choices[ $key ]['stretch'];
		}
	}

	return implode( ';', $vars );
}

function dsa_styrene_font_face_css() {
	$css = '';
	foreach ( dsa_styrene_font_files() as $weight => $file ) {
		$css .= sprintf(
			'@font-face{font-family:"Styrene B Web";src:url("%s") format("%s");font-weight:%d;font-style:normal;font-display:swap;}',
			esc_url_raw( $file['url'] ),
			$file['format'],
			$weight
		);
	}
	return $css;
}

function dsa_fonts_inline_css() {
	$css  = dsa_styrene_font_face_css();
	$vars = dsa_font_css_vars(
		dsa_sanitize_body_font( get_theme_mod( DSA_BODY_FONT_SETTING, DSA_FONT_DEFAULT ) ),
		dsa_sanitize_secondary_font( get_theme_mod( DSA_HEADING_FONT_SETTING, DSA_FONT_SAME_AS_BODY ) ),
		dsa_sanitize_secondary_font( get_theme_mod( DSA_MENU_FONT_SETTING, DSA_FONT_SAME_AS_BODY ) ),
		dsa_sanitize_secondary_font( get_theme_mod( DSA_BUTTON_FONT_SETTING, DSA_FONT_SAME_AS_BODY ) )
	);

	$weights = dsa_get_font_weights();
	$weight_vars = array();
	foreach ( array_keys( $weights ) as $role ) {
		if ( DSA_WEIGHT_THEME_DEFAULT !== $weights[ $role ] ) {
			$weight_vars[] = "--site-weight-{$role}:" . $weights[ $role ];
		}
	}
	if ( $weight_vars ) {
		$vars = implode( ';', array_filter( array( $vars, implode( ';', $weight_vars ) ) ) );
	}

	if ( $vars ) {
		$css .= ':root{' . $vars . ';}';
	}
	if ( $css ) {
		wp_add_inline_style( '_s-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'dsa_fonts_inline_css', 20 );

/** Body classes for the uppercase and weight settings. */
function dsa_typography_body_classes( $classes ) {
	if ( get_theme_mod( DSA_HEADING_UPPERCASE_SETTING, false ) ) {
		$classes[] = 'has-uppercase-headings';
	}
	if ( get_theme_mod( DSA_MENU_UPPERCASE_SETTING, false ) ) {
		$classes[] = 'has-uppercase-menus';
	}
	if ( get_theme_mod( DSA_BUTTON_UPPERCASE_SETTING, false ) ) {
		$classes[] = 'has-uppercase-buttons';
	}
	$weights = dsa_get_font_weights();
	foreach ( array_keys( $weights ) as $role ) {
		if ( DSA_WEIGHT_THEME_DEFAULT !== $weights[ $role ] ) {
			$classes[] = "has-{$role}-weight";
		}
	}
	return $classes;
}
add_filter( 'body_class', 'dsa_typography_body_classes' );

/**
 * Live preview of font changes in the Customizer. Always sets explicit
 * values: removing a property would fall back to the saved setting.
 */
function dsa_fonts_preview_js() {
	$fonts = array();
	foreach ( dsa_font_choices() as $key => $font ) {
		$fonts[ $key ] = array(
			'stack'   => $font['stack'],
			'stretch' => isset( $font['stretch'] ) ? $font['stretch'] : '',
			'body'    => $font['body'],
		);
	}

	$js = 'var dsaFonts = ' . wp_json_encode( $fonts ) . ';' . sprintf(
		"(function (root) {
			wp.customize('%1\$s', function (setting) {
				setting.bind(function (key) {
					var font = dsaFonts[key];
					if (!font || !font.body) {
						font = dsaFonts['%2\$s'];
					}
					root.style.setProperty('--site-font-body', font.stack);
				});
			});
			function bindSecondary(settingId, role) {
				wp.customize(settingId, function (setting) {
					setting.bind(function (key) {
						var font = dsaFonts[key]; // undefined = same as body
						root.style.setProperty('--site-font-' + role, font ? font.stack : 'var(--site-font-body)');
						root.style.setProperty('--site-' + role + '-stretch', font && font.stretch ? font.stretch : 'normal');
					});
				});
			}
			bindSecondary('%3\$s', 'heading');
			bindSecondary('%4\$s', 'menu');
			bindSecondary('%12\$s', 'button');
			function bindClass(settingId, className) {
				wp.customize(settingId, function (setting) {
					setting.bind(function (on) {
						document.body.classList.toggle(className, !!on);
					});
				});
			}
			bindClass('%5\$s', 'has-uppercase-headings');
			bindClass('%10\$s', 'has-uppercase-menus');
			bindClass('%11\$s', 'has-uppercase-buttons');
			var weights = %6\$s;
			function bindWeight(settingId, role) {
				wp.customize(settingId, function (setting) {
					setting.bind(function (value) {
						var weight = parseInt(value, 10);
						var on = weights.indexOf(weight) !== -1;
						document.body.classList.toggle('has-' + role + '-weight', on);
						if (on) {
							root.style.setProperty('--site-weight-' + role, String(weight));
						}
					});
				});
			}
			bindWeight('%7\$s', 'body');
			bindWeight('%8\$s', 'heading');
			bindWeight('%9\$s', 'menu');
			bindWeight('%13\$s', 'button');
		})(document.documentElement);",
		DSA_BODY_FONT_SETTING,
		DSA_FONT_DEFAULT,
		DSA_HEADING_FONT_SETTING,
		DSA_MENU_FONT_SETTING,
		DSA_HEADING_UPPERCASE_SETTING,
		wp_json_encode( array_keys( dsa_font_weight_choices() ) ),
		DSA_BODY_WEIGHT_SETTING,
		DSA_HEADING_WEIGHT_SETTING,
		DSA_MENU_WEIGHT_SETTING,
		DSA_MENU_UPPERCASE_SETTING,
		DSA_BUTTON_UPPERCASE_SETTING,
		DSA_BUTTON_FONT_SETTING,
		DSA_BUTTON_WEIGHT_SETTING
	);

	wp_add_inline_script( 'customize-preview', $js );
}
add_action( 'customize_preview_init', 'dsa_fonts_preview_js' );
