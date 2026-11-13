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
		'section'     => 'dsa_typography',
		'label'       => __( 'Base font size (px)', '_s' ),
		'description' => sprintf(
			/* translators: 1: default size, 2: min size, 3: max size */
			__( 'Default text size for the whole site. Headings and spacing scale with it. Default %1$s, between %2$s and %3$s.', '_s' ),
			DSA_BASE_FONT_SIZE_DEFAULT,
			DSA_BASE_FONT_SIZE_MIN,
			DSA_BASE_FONT_SIZE_MAX
		),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => DSA_BASE_FONT_SIZE_MIN,
			'max'  => DSA_BASE_FONT_SIZE_MAX,
			'step' => 0.5,
		),
	) );
}
add_action( 'customize_register', 'dsa_customize_register_typography' );

function dsa_get_base_font_size() {
	return dsa_sanitize_base_font_size( get_theme_mod( DSA_BASE_FONT_SIZE_SETTING, DSA_BASE_FONT_SIZE_DEFAULT ) );
}

/**
 * Print the font size variable after the theme stylesheet.
 */
function dsa_typography_inline_css() {
	$size = dsa_get_base_font_size();
	if ( (float) $size === (float) DSA_BASE_FONT_SIZE_DEFAULT ) {
		return; // the stylesheet already defaults to 16px
	}
	wp_add_inline_style( '_s-style', ':root{--site-font-size:' . $size . 'px;}' );
}
add_action( 'wp_enqueue_scripts', 'dsa_typography_inline_css', 20 );

/**
 * Live preview in the Customizer without a page reload.
 */
function dsa_typography_preview_js() {
	wp_add_inline_script(
		'customize-preview',
		"wp.customize('" . DSA_BASE_FONT_SIZE_SETTING . "', function (setting) {
			setting.bind(function (size) {
				document.documentElement.style.setProperty('--site-font-size', parseFloat(size) + 'px');
			});
		});"
	);
}
add_action( 'customize_preview_init', 'dsa_typography_preview_js' );
