<?php
/**
 * Runs on Uninstall of My YouTube Videos
 *
 * This file will remove all options for the plugin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

global $wpdb;

// For a single site
if ( ! is_multisite() ) {

	include_once( 'includes/admin/class-my-youtube-videos-admin-settings.php' );

	$settings = new My_YouTube_Videos_Admin_Settings();

	$uninstall = $settings->get_option( 'my_youtube_videos_uninstall_data' );

	if ( ! empty( $uninstall ) ) {
		// Delete options
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'my_youtube_videos_%';");
	}
}

?>