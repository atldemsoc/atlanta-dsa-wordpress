<?php
/**
 * Site fonts, set in Customize > Typography:
 * - Body font, Heading font and Menu font (header and footer menus)
 * - "Uppercase headings" toggle
 *
 * The font choices set CSS variables (--site-font-body, --site-font-heading,
 * --site-heading-stretch, --site-font-menu, --site-menu-stretch) used by
 * style/_fonts.scss. Uppercase headings adds the body class
 * "has-uppercase-headings".
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
const DSA_HEADING_UPPERCASE_SETTING = 'dsa_heading_uppercase';
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
			'stack'   => '"Union Gothic Web", "Arial Narrow", sans-serif-condensed, sans-serif',
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

/** For the heading and menu fonts, which can also be "same as body". */
function dsa_sanitize_secondary_font( $value ) {
	return ( DSA_FONT_SAME_AS_BODY === $value || array_key_exists( $value, dsa_font_choices() ) )
		? $value
		: DSA_FONT_SAME_AS_BODY;
}

/** Kept for compatibility with the earlier name. */
function dsa_sanitize_heading_font( $value ) {
	return dsa_sanitize_secondary_font( $value );
}

function dsa_sanitize_checkbox( $value ) {
	return ! empty( $value );
}

/* ------------------------------------------------------------------------
 * Styrene B files
 * --------------------------------------------------------------------- */

const DSA_STYRENE_WEIGHTS = array( 400 => 'Regular', 700 => 'Bold', 900 => 'Black' );
const DSA_FONT_FORMATS = array( 'woff2' => 'woff2', 'woff' => 'woff', 'otf' => 'opentype' );

/**
 * Styrene B files keyed by weight: uploads/fonts/styrene/ first, then the
 * Media Library. Each weight uses the first format found (woff2, woff, otf).
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
 * WordPress also checks a file's real type, which it often reports
 * differently for fonts (e.g. "application/vnd.ms-opentype"); accept font
 * files by extension for administrators.
 */
function dsa_check_font_filetype( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $data;
	}
	$ext   = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	$fonts = array( 'woff2' => 'font/woff2', 'woff' => 'font/woff', 'otf' => 'font/otf' );
	if ( isset( $fonts[ $ext ] ) ) {
		$data['ext']  = $ext;
		$data['type'] = $fonts[ $ext ];
	}
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

	$styrene_note = dsa_styrene_font_files()
		? ''
		: ' ' . __( 'Styrene B files not found (upload StyreneB-Regular, -Bold and -Black to the Media Library); until then it shows Manifold DSA.', '_s' );

	$wp_customize->add_setting( DSA_BODY_FONT_SETTING, array(
		'default'           => DSA_FONT_DEFAULT,
		'sanitize_callback' => 'dsa_sanitize_body_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_BODY_FONT_SETTING, array(
		'section'     => 'dsa_typography',
		'label'       => __( 'Body font', '_s' ),
		'description' => __( 'Font for paragraphs, buttons and forms.', '_s' ) . $styrene_note,
		'type'        => 'select',
		'choices'     => $body_choices,
	) );

	$wp_customize->add_setting( DSA_HEADING_FONT_SETTING, array(
		'default'           => DSA_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_secondary_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_HEADING_FONT_SETTING, array(
		'section'     => 'dsa_typography',
		'label'       => __( 'Heading font', '_s' ),
		'description' => __( 'Font for page titles and headings (H1–H6). Union Gothic is a condensed headline font.', '_s' ),
		'type'        => 'select',
		'choices'     => $other_choices,
	) );

	$wp_customize->add_setting( DSA_HEADING_UPPERCASE_SETTING, array(
		'default'           => false,
		'sanitize_callback' => 'dsa_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_HEADING_UPPERCASE_SETTING, array(
		'section' => 'dsa_typography',
		'label'   => __( 'Uppercase headings', '_s' ),
		'description' => __( 'Show all page titles and headings (H1–H6) in capital letters.', '_s' ),
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( DSA_MENU_FONT_SETTING, array(
		'default'           => DSA_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_secondary_font',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( DSA_MENU_FONT_SETTING, array(
		'section'     => 'dsa_typography',
		'label'       => __( 'Menu font', '_s' ),
		'description' => __( 'Font for the menus in the header bar and footer. The "Atlanta DSA" name next to the logo keeps the body font.', '_s' ),
		'type'        => 'select',
		'choices'     => $other_choices,
	) );
}
// After the Typography section is registered in inc/customizer.php.
add_action( 'customize_register', 'dsa_customize_register_fonts', 20 );

/* ------------------------------------------------------------------------
 * Output
 * --------------------------------------------------------------------- */

/**
 * CSS variable declarations for the chosen fonts ('' if all defaults).
 */
function dsa_font_css_vars( $body_key, $heading_key, $menu_key = DSA_FONT_SAME_AS_BODY ) {
	$choices = dsa_font_choices();
	$vars    = array();

	if ( DSA_FONT_DEFAULT !== $body_key ) {
		$vars[] = '--site-font-body:' . $choices[ $body_key ]['stack'];
	}

	foreach ( array( 'heading' => $heading_key, 'menu' => $menu_key ) as $role => $key ) {
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
		dsa_sanitize_secondary_font( get_theme_mod( DSA_MENU_FONT_SETTING, DSA_FONT_SAME_AS_BODY ) )
	);
	if ( $vars ) {
		$css .= ':root{' . $vars . ';}';
	}
	if ( $css ) {
		wp_add_inline_style( '_s-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'dsa_fonts_inline_css', 20 );

function dsa_heading_uppercase_body_class( $classes ) {
	if ( get_theme_mod( DSA_HEADING_UPPERCASE_SETTING, false ) ) {
		$classes[] = 'has-uppercase-headings';
	}
	return $classes;
}
add_filter( 'body_class', 'dsa_heading_uppercase_body_class' );

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
			wp.customize('%5\$s', function (setting) {
				setting.bind(function (on) {
					document.body.classList.toggle('has-uppercase-headings', !!on);
				});
			});
		})(document.documentElement);",
		DSA_BODY_FONT_SETTING,
		DSA_FONT_DEFAULT,
		DSA_HEADING_FONT_SETTING,
		DSA_MENU_FONT_SETTING,
		DSA_HEADING_UPPERCASE_SETTING
	);

	wp_add_inline_script( 'customize-preview', $js );
}
add_action( 'customize_preview_init', 'dsa_fonts_preview_js' );
