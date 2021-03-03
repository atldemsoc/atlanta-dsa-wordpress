<?php
require_once 'constants.php';

/**
 * Create A Simple Theme Options Panel
 * From https://www.wpexplorer.com/wordpress-theme-options/
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'ATL_DSA_Theme_Options' ) ) {

	class ATL_DSA_Theme_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'ATL_DSA_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'ATL_DSA_Theme_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_options() {
			return get_option( 'theme_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'ATL DSA', 'text-domain' ),
				esc_html__( 'ATL DSA', 'text-domain' ),
				'manage_options',
				'theme-settings',
				array( 'ATL_DSA_Theme_Options', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'theme_options', 'theme_options', array( 'ATL_DSA_Theme_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Checkbox
				if ( ! empty( $options[SETTINGS_ENABLE_GA_KEY] ) ) {
					$options[SETTINGS_ENABLE_GA_KEY] = true;
				} else {
					unset( $options[SETTINGS_ENABLE_GA_KEY] ); // Remove from options if not checked
				}

				// Input
				if ( ! empty( $options[SETTINGS_GA_UA_KEY] ) ) {
					$options[SETTINGS_GA_UA_KEY] = sanitize_text_field( $options[SETTINGS_GA_UA_KEY] );
				} else {
					unset( $options[SETTINGS_GA_UA_KEY] ); // Remove from options if empty
				}

				// Select
				/*if ( ! empty( $options['select_example'] ) ) {
					$options['select_example'] = sanitize_text_field( $options['select_example'] );
				}*/

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h1><?php esc_html_e( 'Atlanta DSA Theme Options', 'text-domain' ); ?></h1>

				<form method="post" action="options.php">

					<?php settings_fields( 'theme_options' ); ?>

					<h2>Analytics Settings</h2>

					<hr/>

					<table class="form-table ATL_DSA-custom-admin-login-table">

						<tr valign="top">
							<th scope="row">
								<div><?php esc_html_e( 'DSA Chapter Name', 'text-domain' ); ?></div>
								<div><small><?php esc_html_e( 'DSA will be added to the end automatically, so rather than "Atlanta DSA" just put "Atlanta".', 'text-domain' ); ?></small></div>
							</th>
							<td>
								<?php $value = self::get_theme_option( SETTINGS_CHAPTER_NAME_KEY ); ?>
								<input type="text" name="theme_options[<?= SETTINGS_CHAPTER_NAME_KEY ?>]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Checkbox example ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Enable Google Analytics', 'text-domain' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( SETTINGS_ENABLE_GA_KEY ); ?>
								<input type="checkbox" name="theme_options[<?= SETTINGS_ENABLE_GA_KEY ?>]" <?php checked( $value, true ); ?>>
							</td>
						</tr>

						<?php // Text input example ?>
						<tr valign="top">
							<th scope="row">
								<div><?php esc_html_e( 'Google Analytics UA ID', 'text-domain' ); ?></div>
								<div><small><?php esc_html_e( 'Looks like UA-123456789-1', 'text-domain' ); ?></small></div>
							</th>
							<td>
								<?php $value = self::get_theme_option( SETTINGS_GA_UA_KEY ); ?>
								<input type="text" name="theme_options[<?= SETTINGS_GA_UA_KEY ?>]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Select example ?>
						<!--<tr valign="top" class="ATL_DSA-custom-admin-screen-background-section">
							<th scope="row"><?php /*esc_html_e( 'Select Example', 'text-domain' ); */?></th>
							<td>
								<?php /*$value = self::get_theme_option( 'select_example' ); */?>
								<select name="theme_options[select_example]">
									<?php
/*									$options = array(
										'1' => esc_html__( 'Option 1', 'text-domain' ),
										'2' => esc_html__( 'Option 2', 'text-domain' ),
										'3' => esc_html__( 'Option 3', 'text-domain' ),
									);
									foreach ( $options as $id => $label ) { */?>
										<option value="<?php /*echo esc_attr( $id ); */?>" <?php /*selected( $value, $id, true ); */?>>
											<?php /*echo strip_tags( $label ); */?>
										</option>
									<?php /*} */?>
								</select>
							</td>
						</tr>-->

					</table>

					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new ATL_DSA_Theme_Options();

// Helper function to use in your theme to return a theme option value
function ATL_DSA_get_theme_option( $id = '' ) {
	return ATL_DSA_Theme_Options::get_theme_option( $id );
}
