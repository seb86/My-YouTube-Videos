<?php
/**
 * This outputs the admin pages for My YouTube Videos.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Admin_Page' ) ) {

	/**
	 * My_YouTube_Videos_Admin_Page Class
	 *
	 * @since 1.0.0
	 */
	class My_YouTube_Videos_Admin_Page {

		/**
		 * Handles output of the plugin page in admin.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public static function output() {
			$view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : '';

			if ( false === ( $page_content = get_transient( 'my_youtube_videos_html_' . $view ) ) ) {
				$page_content = do_action('my_youtube_videos_html_content_' . $view);

				if ( $page_content ) {
					set_transient( 'my_youtube_videos_html_' . $view, wp_kses_post( $page_content ), 60*60*24*7 ); // Cached for a week.
				}
			}

			include_once( 'views/html-admin-page.php' );
		} // END output()

	} // END My_YouTube_Videos_Admin_Page() class.

} // END if class exists.

return new My_YouTube_Videos_Admin_Page();
?>