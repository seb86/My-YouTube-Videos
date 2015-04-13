<?php
/**
 * My YouTube Videos Admin Functions
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all My YouTube Videos screen ids
 *
 * @since  2.0.0
 * @access public
 * @return array
 */
function my_youtube_videos_get_screen_ids() {
	$my_youtube_videos_screen_id = MY_YOUTUBE_VIDEOS_SCREEN_ID;

	return array( 'settings_page_' . $my_youtube_videos_screen_id . '-settings' );
} // END my_youtube_videos_get_screen_ids()

/**
 * Output admin fields.
 *
 * Loops though the plugin name options array and outputs each field.
 *
 * @since  2.0.0
 * @access public
 * @param  array $options Opens array to output
 */
function my_youtube_videos_admin_fields( $options ) {
	if ( ! class_exists( 'My_YouTube_Videos_Admin_Settings' ) ) {
		include 'class-my-youtube-videos-admin-settings.php';
	}

	My_YouTube_Videos_Admin_Settings::output_fields( $options );
} // END my_youtube_videos_admin_fields()

/**
 * Update all settings which are passed.
 *
 * @since  2.0.0
 * @access public
 * @param  array $options
 * @return void
 */
function my_youtube_videos_update_options( $options ) {
	if ( ! class_exists( 'My_YouTube_Videos_Admin_Settings' ) ) {
		include 'class-my-youtube-videos-admin-settings.php';
	}

	My_YouTube_Videos_Admin_Settings::save_fields( $options );
} // END my_youtube_videos_update_options()

/**
 * Get a setting from the settings API.
 *
 * @since  2.0.0
 * @access public
 * @param  mixed $option_name
 * @param  mixed $default
 * @return string
 */
function my_youtube_videos_settings_get_option( $option_name, $default = '' ) {
	if ( ! class_exists( 'My_YouTube_Videos_Admin_Settings' ) ) {
		include 'class-my-youtube-videos-admin-settings.php';
	}

	return My_YouTube_Videos_Admin_Settings::get_option( $option_name, $default );
} // END my_youtube_videos_settings_get_option()

?>