<?php
/**
 * Installation related functions and actions.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Install' ) ) {

/**
 * My_YouTube_Videos_Install Class
 */
class My_YouTube_Videos_Install {

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function __construct() {
		register_activation_hook( MY_YOUTUBE_VIDEOS_FILE,                                    array( $this, 'install' ) );
		add_action( 'admin_init',                                                            array( $this, 'check_version' ), 5 );
		add_action( 'admin_init',                                                            array( $this, 'install_actions' ), 10 );
		add_action( 'in_plugin_update_message-' . plugin_basename( MY_YOUTUBE_VIDEOS_FILE ), array( $this, 'in_plugin_update_message' ) );
	} // END __construct()

	/**
	 * When called, the plugin checks the version
	 * of the plugin and the database version in use.
	 * This function determins if the plugin requires
	 * to process an update.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && ( get_option( 'my_youtube_videos_version' ) != My_YouTube_Videos()->version || get_option( 'my_youtube_videos_db_version' ) != My_YouTube_Videos()->version ) )
			$this->install();

			do_action( 'my_youtube_videos_updated' );
	} // END check_version()

	/**
	 * Install actions such as updating the plugin when a button is pressed.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function install_actions() {
		// Update button
		if ( ! empty( $_GET['do_update_my_youtube_videos'] ) ) {
			$this->update();

			// Update complete
			delete_option( '_my_youtube_videos_needs_update' );
		}
	} // END install_action()

	/**
	 * Install My YouTube Videos
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function install() {
		$this->create_options();

		// Queue upgrades
		$current_db_version = get_option( 'my_youtube_videos_db_version', null );

		//if ( version_compare( $current_db_version, '2.0.0', '<' ) && null !== $current_db_version ) {
			update_option( '_my_youtube_videos_needs_update', 1 );
		//} else {
			update_option( 'my_youtube_videos_db_version', My_YouTube_Videos()->version );
		//}

		// Update version
		update_option( 'my_youtube_videos_version', My_YouTube_Videos()->version );

		// Trigger action
		do_action( 'my_youtube_videos_installed' );
	} // END install()

	/**
	 * Handle updates
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function update() {
		// Do updates
		$current_db_version = get_option( 'my_youtube_videos_db_version' );

		//if ( version_compare( $current_db_version, '2.0.0', '<' ) ) {
			include( 'updates/my-youtube-videos-update-2.0.0.php' );
			update_option( 'my_youtube_videos_db_version', '2.0.0' );
		//}

		update_option( 'my_youtube_videos_db_version', My_YouTube_Videos()->version );
	} // END update()

	/**
	 * Default Options
	 *
	 * Sets up the default options defined on the settings pages.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function create_options() {
		// Include settings so that we can run through defaults
		include_once( 'class-my-youtube-videos-admin-settings.php' );

		$settings = My_YouTube_Videos_Admin_Settings::get_settings_pages();

		foreach ( $settings as $section ) {
			foreach ( $section->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
	} // END create_options()

	/**
	 * Delete all plugin options.
	 *
	 * @since  2.0.0
	 * @access public
	 * @global $wpdb
	 * @return void
	 */
	public function delete_options() {
		global $wpdb;

		// Delete options
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'my_youtube_videos_%';" );
	} // END delete_options()

	/**
	 * Show details of plugin changes on the
	 * Installed Plugins screen.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function in_plugin_update_message() {
		$response = wp_remote_get( MY_YOUTUBE_VIDEOS_README_FILE );

		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {

			// Output Upgrade Notice
			$matches = null;
			$regexp  = '~==\s*Upgrade Notice\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote( MY_YOUTUBE_VIDEOS_VERSION ) . '\s*=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$notices = (array) preg_split('~[\r\n]+~', trim( $matches[1] ) );
				echo '<div class="my_youtube_videos_upgrade_notice" style="padding: 8px; margin: 6px 0;">';
				foreach ( $notices as $index => $line ) {
					echo '<p style="margin: 0; font-size: 1.1em; text-shadow: 0 1px 1px #3563e8;">' . preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) . '</p>';
				}
				echo '</div>';
			}

			// Output Changelog
			$matches = null;
			$regexp  = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*-(.*)=(.*)(=\s*' . preg_quote( MY_YOUTUBE_VIDEOS_VERSION ) . '\s*-(.*)=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$changelog = (array) preg_split('~[\r\n]+~', trim( $matches[2] ) );
				echo ' ' . __( 'What\'s new:', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '<div style="font-weight: normal;">';
				$ul = false;

				foreach ( $changelog as $index => $line ) {
					if ( preg_match('~^\s*\*\s*~', $line ) ) {
						if ( ! $ul ) {
							echo '<ul style="list-style: disc inside; margin: 9px 0 9px 20px; overflow:hidden; zoom: 1;">';
							$ul = true;
						}
						$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
						echo '<li style="width: 50%; margin: 0; float: left; ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . $line . '</li>';
					} else {
						if ( $ul ) {
							echo '</ul>';
							$ul = false;
						}
						echo '<p style="margin: 9px 0;">' . htmlspecialchars( $line ) . '</p>';
					}
				}

				if ( $ul ) {
					echo '</ul>';
				}

				echo '</div>';
			}

		}
	} // END in_plugin_update_message()

} // END if class.

} // END if class exists.
return new My_YouTube_Videos_Install();
?>