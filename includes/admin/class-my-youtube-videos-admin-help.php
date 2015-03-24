<?php
/**
 * Help is provided for this plugin on the plugin pages.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Admin_Help' ) ) {

/**
 * Class - My_YouTube_Videos_Admin_Help
 *
 * @since 2.0.0
 */
class My_YouTube_Videos_Admin_Help {

	/**
	 * Constructor
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'current_screen', array( $this, 'add_help_tabs' ), 50 );
	} // END __construct()

	/**
	 * Adds help tabs to the plugin pages.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function add_help_tabs() {
		$screen = get_current_screen();

		if ( ! in_array( $screen->id, my_youtube_videos_get_screen_ids() ) )
			return;

		$screen->add_help_tab( array(
			'id'      => 'my_youtube_videos_docs_tab',
			'title'   => __( 'Documentation', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'Thank you for using <strong>%s</strong> :) Should you need help using or extending %s please read the documentation.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name, My_YouTube_Videos()->name ) . '</p>' .
				'<p><a href="' . My_YouTube_Videos()->doc_url . '" class="button button-primary">' . sprintf( __( '%s Documentation', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name ) . '</a></p>'
		) );

		$screen->add_help_tab( array(
			'id'      => 'my_youtube_videos_support_tab',
			'title'   => __( 'Support', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'After <a href="%s">reading the documentation</a>, for further assistance you can use the <a href="%s">community forum</a>.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->doc_url, My_YouTube_Videos()->wp_plugin_support_url ) . '</p>' .
				'<p><a href="' . My_YouTube_Videos()->wp_plugin_support_url . '" class="button button-primary">' . __( 'Community Support', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '</a></p>'
		) );

		$screen->add_help_tab( array(
			'id'      => 'my_youtube_videos_bugs_tab',
			'title'   => __( 'Found a bug?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'If you find a bug within <strong>%s</strong> you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. Be as descriptive as possible. Thank you.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name, MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . 'issues?state=open', MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . 'blob/master/CONTRIBUTING.md', admin_url( 'admin.php?page=' . MY_YOUTUBE_VIDEOS_PAGE . '-status' ) ) . '</p>' .
				'<p><a href="' . MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . 'issues?state=open" class="button button-primary">' . __( 'Report a bug', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '</a></p>'
		) );

		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '</strong></p>' .
			'<p><a href="' . My_YouTube_Videos()->web_url . '" target="_blank">' . sprintf( __( 'About %s', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name ) . '</a></p>' .
			'<p><a href="' . My_YouTube_Videos()->wp_plugin_url . '" target="_blank">' . __( 'Plugin on WordPress.org', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '</a></p>' .
			'<p><a href="' . MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . '" target="_blank">' . __( 'Plugin on Github', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . '</a></p>'
		);
	} // END add_help_tabs()

} // END My_YouTube_Videos_Admin_Help class.

} // END if class exists.

return new My_YouTube_Videos_Admin_Help();
?>