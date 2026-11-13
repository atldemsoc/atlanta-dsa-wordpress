<?php
/**
 * _s Theme Customizer
 *
 * @package _s
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function _s_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => '_s_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => '_s_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', '_s_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function _s_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function _s_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function _s_customize_preview_js() {
	wp_enqueue_script( '_s-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', '_s_customize_preview_js' );

/**
 * Typography: base font size.
 *
 * Exposed to CSS as --site-font-size on :root. The stylesheet uses it as the
 * root (html) font size, and almost all text and spacing in the theme is in
 * rem, so this scales the whole site's type.
 */
const DSA_BASE_FONT_SIZE_SETTING = 'dsa_base_font_size';
const DSA_BASE_FONT_SIZE_DEFAULT = 16;
const DSA_BASE_FONT_SIZE_MIN = 12;
const DSA_BASE_FONT_SIZE_MAX = 24;

function dsa_sanitize_base_font_size( $value ) {
	if ( ! is_numeric( $value ) ) {
		return DSA_BASE_FONT_SIZE_DEFAULT;
	}
	$value = round( (float) $value * 2 ) / 2; // half-pixel steps
	return max( DSA_BASE_FONT_SIZE_MIN, min( DSA_BASE_FONT_SIZE_MAX, $value ) );
}

function dsa_customize_register_typography( $wp_customize ) {
	$wp_customize->add_section( 'dsa_typography', array(
		'title'    => __( 'Typography', '_s' ),
		'priority' => 40,
	) );

	$wp_customize->add_setting( DSA_BASE_FONT_SIZE_SETTING, array(
		'default'           => DSA_BASE_FONT_SIZE_DEFAULT,
		'sanitize_callback' => 'dsa_sanitize_base_font_size',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_BASE_FONT_SIZE_SETTING, array(
		'priority'    => 5,
		'section'     => 'dsa_typography',
		'label'       => __( 'Base font size (px)', '_s' ),
		'description' => __( 'Default 16.', '_s' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => DSA_BASE_FONT_SIZE_MIN,
			'max'  => DSA_BASE_FONT_SIZE_MAX,
			'step' => 0.5,
		),
	) );
}
add_action( 'customize_register', 'dsa_customize_register_typography' );

/**
 * Header Bar: height of the red navigation bar at the top of every page.
 *
 * Exposed to CSS as --site-navbar-height. 0 means automatic (the theme's
 * default height). Shorter bars shrink the logo to fit.
 */
const DSA_NAVBAR_HEIGHT_SETTING = 'dsa_navbar_height';
const DSA_NAVBAR_HEIGHT_MIN = 48;
const DSA_NAVBAR_HEIGHT_MAX = 120;

function dsa_sanitize_navbar_height( $value ) {
	if ( ! is_numeric( $value ) || (int) $value <= 0 ) {
		return 0; // automatic
	}
	return max( DSA_NAVBAR_HEIGHT_MIN, min( DSA_NAVBAR_HEIGHT_MAX, (int) round( (float) $value ) ) );
}

function dsa_customize_register_header_bar( $wp_customize ) {
	$wp_customize->add_section( 'dsa_header_bar', array(
		'title'    => __( 'Header Bar', '_s' ),
		'priority' => 41,
	) );

	$wp_customize->add_setting( DSA_NAVBAR_HEIGHT_SETTING, array(
		'default'           => 0,
		'sanitize_callback' => 'dsa_sanitize_navbar_height',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_NAVBAR_HEIGHT_SETTING, array(
		'priority'    => 10,
		'section'     => 'dsa_header_bar',
		'label'       => __( 'Height (px)', '_s' ),
		'description' => __( '0 = automatic.', '_s' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => DSA_NAVBAR_HEIGHT_MAX,
			'step' => 1,
		),
	) );
}
add_action( 'customize_register', 'dsa_customize_register_header_bar' );

/**
 * Header Bar: the name next to the logo (e.g. "Atlanta DSA" or "ATL DSA"),
 * optionally with the word "DSA" in bold.
 */
const DSA_SITE_NAME_SETTING = 'dsa_site_name';
const DSA_SITE_NAME_BOLD_SETTING = 'dsa_site_name_bold_dsa';

/**
 * What the header showed before this setting existed: the chapter name from
 * the "ATL DSA" settings page (or "Metro Atlanta") followed by "DSA".
 */
function dsa_default_site_name() {
	$chapter = function_exists( 'ATL_DSA_get_theme_option' ) ? ATL_DSA_get_theme_option( SETTINGS_CHAPTER_NAME_KEY ) : '';
	return trim( ( $chapter ? $chapter : 'Metro Atlanta' ) . ' DSA' );
}

function dsa_get_site_name() {
	$name = trim( (string) get_theme_mod( DSA_SITE_NAME_SETTING, '' ) );
	return '' !== $name ? $name : dsa_default_site_name();
}

/**
 * The name as HTML, with each separate word "DSA" wrapped for bold styling
 * when enabled.
 */
function dsa_get_site_name_html() {
	$html = esc_html( dsa_get_site_name() );
	if ( get_theme_mod( DSA_SITE_NAME_BOLD_SETTING, true ) ) {
		$html = preg_replace( '/\bDSA\b/', '<span class="site-nav__name-dsa">DSA</span>', $html );
	}
	return $html;
}

function dsa_the_site_name() {
	echo dsa_get_site_name_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
}

function dsa_sanitize_site_name( $value ) {
	return sanitize_text_field( $value );
}

function dsa_sanitize_bool( $value ) {
	return ! empty( $value );
}

function dsa_customize_register_site_name( $wp_customize ) {
	$wp_customize->add_setting( DSA_SITE_NAME_SETTING, array(
		'default'           => dsa_default_site_name(),
		'sanitize_callback' => 'dsa_sanitize_site_name',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_SITE_NAME_SETTING, array(
		'section'     => 'dsa_header_bar',
		'label'       => __( 'Name next to the logo', '_s' ),
		'description' => __( 'e.g. Atlanta DSA or ATL DSA.', '_s' ),
		'type'        => 'text',
		'priority'    => 5,
	) );

	$wp_customize->add_setting( DSA_SITE_NAME_BOLD_SETTING, array(
		'default'           => true,
		'sanitize_callback' => 'dsa_sanitize_bool',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( DSA_SITE_NAME_BOLD_SETTING, array(
		'section'     => 'dsa_header_bar',
		'label'       => __( 'Bold "DSA"', '_s' ),
		'type'        => 'checkbox',
		'priority'    => 6,
	) );

	// Live preview: re-render just the name.
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'dsa_site_name', array(
			'selector'            => '.site-nav__name',
			'settings'            => array( DSA_SITE_NAME_SETTING, DSA_SITE_NAME_BOLD_SETTING ),
			'container_inclusive' => false,
			'render_callback'     => 'dsa_get_site_name_html',
		) );
	}
}
add_action( 'customize_register', 'dsa_customize_register_site_name' );

function dsa_get_base_font_size() {
	return dsa_sanitize_base_font_size( get_theme_mod( DSA_BASE_FONT_SIZE_SETTING, DSA_BASE_FONT_SIZE_DEFAULT ) );
}

/**
 * Print the Customizer CSS variables after the theme stylesheet. Only
 * changed settings are printed; style.css has the defaults as fallbacks.
 */
function dsa_customizer_inline_css() {
	$vars = array();

	$size = dsa_get_base_font_size();
	if ( (float) $size !== (float) DSA_BASE_FONT_SIZE_DEFAULT ) {
		$vars[] = '--site-font-size:' . $size . 'px';
	}

	$navbar_height = dsa_sanitize_navbar_height( get_theme_mod( DSA_NAVBAR_HEIGHT_SETTING, 0 ) );
	if ( $navbar_height > 0 ) {
		$vars[] = '--site-navbar-height:' . $navbar_height . 'px';
	}

	if ( $vars ) {
		wp_add_inline_style( '_s-style', ':root{' . implode( ';', $vars ) . ';}' );
	}
}
add_action( 'wp_enqueue_scripts', 'dsa_customizer_inline_css', 20 );

/**
 * Live preview in the Customizer without a page reload. Mirrors the PHP
 * sanitizers so the preview matches what is saved.
 */
function dsa_customizer_preview_js() {
	$js = sprintf(
		"(function (root) {
			wp.customize('%1\$s', function (setting) {
				setting.bind(function (value) {
					var size = Math.round(parseFloat(value) * 2) / 2;
					if (isNaN(size)) { size = %2\$s; }
					size = Math.min(%4\$s, Math.max(%3\$s, size));
					root.style.setProperty('--site-font-size', size + 'px');
				});
			});
			wp.customize('%5\$s', function (setting) {
				setting.bind(function (value) {
					var height = Math.round(parseFloat(value));
					if (isNaN(height) || height <= 0) {
						// 'initial' unsets the variable so the CSS fallbacks
						// (automatic height) apply, even over a saved value.
						root.style.setProperty('--site-navbar-height', 'initial');
						return;
					}
					height = Math.min(%7\$s, Math.max(%6\$s, height));
					root.style.setProperty('--site-navbar-height', height + 'px');
				});
			});
		})(document.documentElement);",
		DSA_BASE_FONT_SIZE_SETTING,
		DSA_BASE_FONT_SIZE_DEFAULT,
		DSA_BASE_FONT_SIZE_MIN,
		DSA_BASE_FONT_SIZE_MAX,
		DSA_NAVBAR_HEIGHT_SETTING,
		DSA_NAVBAR_HEIGHT_MIN,
		DSA_NAVBAR_HEIGHT_MAX
	);
	wp_add_inline_script( 'customize-preview', $js );
}
add_action( 'customize_preview_init', 'dsa_customizer_preview_js' );
