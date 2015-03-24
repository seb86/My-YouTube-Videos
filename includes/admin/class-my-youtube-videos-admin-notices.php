<?php
/**
 * Display notices in the WordPress admin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Admin_Notices' ) ) {

/**
 * My_YouTube_Videos_Admin_Notices Class
 */
class My_YouTube_Videos_Admin_Notices {

	/**
	 * Constructor
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_print_styles', array( $this, 'add_notices' ) );
	} // END __construct()

	/**
	 * Add admin notices and styles when needed.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function add_notices() {
		if ( get_option( '_my_youtube_videos_needs_update' ) == 1 ) {
			wp_enqueue_style( 'my-youtube-videos-activation', My_YouTube_Videos()->plugin_url() . '/assets/css/admin/activation.css' );
			add_action( 'admin_notices', array( $this, 'install_notice' ) );
		}
	} // END add_notices()

	/**
	 * Show the install notices.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function install_notice() {
		// If we need to update, include a message with the update button.
		if ( get_option( '_my_youtube_videos_needs_update' ) == 1 ) {
			include( 'views/html-notice-update.php' );
		}
	} // END install_notice()

} // END My_YouTube_Videos_Admin_Notices class.

} // END if class exists.

return new My_YouTube_Videos_Admin_Notices();

?>
