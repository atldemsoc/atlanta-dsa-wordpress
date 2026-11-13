<?php
/**
 * Site fonts: Customize > Typography "Body font" and "Heading font".
 *
 * The choices set CSS variables (--site-font-body, --site-font-heading,
 * --site-heading-stretch) that style.css uses; see style/_fonts.scss.
 * Manifold DSA, Union Gothic and Geist ship with the theme (fonts/).
 *
 * Styrene B is NOT bundled: its desktop license doesn't allow web use. If the
 * chapter gets a web license, upload the web font files to
 * wp-content/uploads/fonts/styrene/ named StyreneB-Regular, StyreneB-Bold and
 * StyreneB-Black (.woff2, .woff or .otf). Until then, choosing Styrene B
 * falls back to Manifold DSA.
 *
 * @package _s
 */

const DSA_BODY_FONT_SETTING = 'dsa_body_font';
const DSA_HEADING_FONT_SETTING = 'dsa_heading_font';
const DSA_FONT_DEFAULT = 'manifold';
const DSA_HEADING_FONT_SAME_AS_BODY = 'same';

const DSA_FONT_FALLBACK_STACK = 'BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif';

/**
 * Available fonts. 'body' => false means headings only.
 */
function dsa_font_choices() {
	return array(
		'manifold' => array(
			'label'   => 'Manifold DSA',
			'stack'   => '"ManifoldDSA", ' . DSA_FONT_FALLBACK_STACK,
			'body'    => true,
		),
		'styrene'  => array(
			'label'   => 'Styrene B',
			// Falls back to Manifold when the Styrene files aren't installed.
			'stack'   => '"Styrene B Web", "ManifoldDSA", ' . DSA_FONT_FALLBACK_STACK,
			'body'    => true,
		),
		'geist'    => array(
			'label'   => 'Geist Sans',
			'stack'   => '"Geist Web", ' . DSA_FONT_FALLBACK_STACK,
			'body'    => true,
		),
		'union'    => array(
			'label'   => 'Union Gothic (headings only)',
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

function dsa_sanitize_heading_font( $value ) {
	return ( DSA_HEADING_FONT_SAME_AS_BODY === $value || array_key_exists( $value, dsa_font_choices() ) )
		? $value
		: DSA_HEADING_FONT_SAME_AS_BODY;
}

/**
 * Styrene B web font files found in uploads/fonts/styrene/, keyed by weight.
 * Each weight uses the first format found (woff2, woff, otf).
 */
function dsa_styrene_font_files() {
	$uploads = wp_upload_dir( null, false );
	if ( ! empty( $uploads['error'] ) ) {
		return array();
	}
	$dir = trailingslashit( $uploads['basedir'] ) . 'fonts/styrene/';
	$url = trailingslashit( $uploads['baseurl'] ) . 'fonts/styrene/';

	$weights = array( 400 => 'Regular', 700 => 'Bold', 900 => 'Black' );
	$formats = array( 'woff2' => 'woff2', 'woff' => 'woff', 'otf' => 'opentype' );
	$files   = array();

	foreach ( $weights as $weight => $name ) {
		foreach ( $formats as $ext => $format ) {
			$file = "StyreneB-{$name}.{$ext}";
			if ( file_exists( $dir . $file ) ) {
				$files[ $weight ] = array( 'url' => $url . $file, 'format' => $format );
				break;
			}
		}
	}

	return $files;
}

function dsa_customize_register_fonts( $wp_customize ) {
	$choices = dsa_font_choices();

	$body_choices = array();
	foreach ( $choices as $key => $font ) {
		if ( $font['body'] ) {
			$body_choices[ $key ] = $font['label'];
		}
	}

	$heading_choices = array( DSA_HEADING_FONT_SAME_AS_BODY => __( 'Same as body font', '_s' ) );
	foreach ( $choices as $key => $font ) {
		$heading_choices[ $key ] = str_replace( ' (headings only)', '', $font['label'] );
	}

	$styrene_note = dsa_styrene_font_files()
		? __( 'Styrene B files found in uploads/fonts/styrene/.', '_s' )
		: __( 'Styrene B needs a web license; until its files are uploaded to wp-content/uploads/fonts/styrene/, choosing it shows Manifold DSA.', '_s' );

	$wp_customize->add_setting( DSA_BODY_FONT_SETTING, array(
		'default'           => DSA_FONT_DEFAULT,
		'sanitize_callback' => 'dsa_sanitize_body_font',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_BODY_FONT_SETTING, array(
		'section'     => 'dsa_typography',
		'label'       => __( 'Body font', '_s' ),
		'description' => __( 'Font for paragraphs, menus, buttons and forms.', '_s' ) . ' ' . $styrene_note,
		'type'        => 'select',
		'choices'     => $body_choices,
	) );

	$wp_customize->add_setting( DSA_HEADING_FONT_SETTING, array(
		'default'           => DSA_HEADING_FONT_SAME_AS_BODY,
		'sanitize_callback' => 'dsa_sanitize_heading_font',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_HEADING_FONT_SETTING, array(
		'section'     => 'dsa_typography',
		'label'       => __( 'Heading font', '_s' ),
		'description' => __( 'Font for page titles and headings (H1–H6). Union Gothic is a condensed headline font.', '_s' ),
		'type'        => 'select',
		'choices'     => $heading_choices,
	) );
}
// After the Typography section is registered in inc/customizer.php.
add_action( 'customize_register', 'dsa_customize_register_fonts', 20 );

/**
 * CSS variable declarations for the chosen fonts ('' if all defaults).
 */
function dsa_font_css_vars( $body_key, $heading_key ) {
	$choices = dsa_font_choices();
	$vars    = array();

	if ( DSA_FONT_DEFAULT !== $body_key ) {
		$vars[] = '--site-font-body:' . $choices[ $body_key ]['stack'];
	}

	if ( DSA_HEADING_FONT_SAME_AS_BODY !== $heading_key ) {
		$vars[] = '--site-font-heading:' . $choices[ $heading_key ]['stack'];
		if ( ! empty( $choices[ $heading_key ]['stretch'] ) ) {
			$vars[] = '--site-heading-stretch:' . $choices[ $heading_key ]['stretch'];
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
		dsa_sanitize_heading_font( get_theme_mod( DSA_HEADING_FONT_SETTING, DSA_HEADING_FONT_SAME_AS_BODY ) )
	);
	if ( $vars ) {
		$css .= ':root{' . $vars . ';}';
	}
	if ( $css ) {
		wp_add_inline_style( '_s-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'dsa_fonts_inline_css', 20 );

/**
 * Live preview of font changes in the Customizer.
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
					// Always set an explicit value: removing the property would
					// fall back to the saved setting, not the default.
					var font = dsaFonts[key];
					if (!font || !font.body) {
						font = dsaFonts['%2\$s'];
					}
					root.style.setProperty('--site-font-body', font.stack);
				});
			});
			wp.customize('%3\$s', function (setting) {
				setting.bind(function (key) {
					var font = dsaFonts[key]; // undefined = same as body
					root.style.setProperty('--site-font-heading', font ? font.stack : 'var(--site-font-body)');
					root.style.setProperty('--site-heading-stretch', font && font.stretch ? font.stretch : 'normal');
				});
			});
		})(document.documentElement);",
		DSA_BODY_FONT_SETTING,
		DSA_FONT_DEFAULT,
		DSA_HEADING_FONT_SETTING
	);

	wp_add_inline_script( 'customize-preview', $js );
}
add_action( 'customize_preview_init', 'dsa_fonts_preview_js' );
