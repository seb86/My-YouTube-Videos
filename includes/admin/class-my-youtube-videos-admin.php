<?php
/**
 * My YouTube Videos Admin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Admin' ) ) {

class My_YouTube_Videos_Admin {

	/**
	 * Constructor
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function __construct() {
		// Actions
		add_action( 'init',              array( $this, 'includes' ) );

		// Filters
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_filter( 'update_footer',     array( $this, 'update_footer' ), 15 );
	} // END __construct()

	/**
	 * Include any classes we need within admin.
	 *
	 * @since  2.0.0
	 * @access public
	 * @filter my_youtube_videos_enable_admin_help_tab
	 */
	public function includes() {
		// Functions
		include( 'my-youtube-videos-admin-functions.php' );

		// Use this action to register custom post types, user roles and anything else
		do_action( 'my_youtube_videos_admin_include' );

		// Classes we only need if the ajax is not-ajax
		if ( ! is_ajax() ) {
			// Main Plugin
			include( 'class-my-youtube-videos-admin-menus.php' );
			include( 'class-my-youtube-videos-admin-notices.php' );

			// Plugin Help
			if ( apply_filters( 'my_youtube_videos_enable_admin_help_tab', true ) ) {
				include( 'class-my-youtube-videos-admin-help.php' );
			}
		}
	} // END includes()

	/**
	 * Filters the admin footer text by placing links
	 * for the plugin including a simply thank you to
	 * review the plugin on WordPress.org.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  $text
	 * @filter my_youtube_videos_admin_footer_review_text
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		$screen = get_current_screen();

		if ( in_array( $screen->id, my_youtube_videos_get_screen_ids() ) ) {
			$links = apply_filters( 'my_youtube_videos_admin_footer_text_links', array(
				My_YouTube_Videos()->web_url . '?utm_source=wpadmin&utm_campaign=footer' => __( 'Website', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				My_YouTube_Videos()->doc_url . '?utm_source=wpadmin&utm_campaign=footer' => __( 'Documentation', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
			) );

			$text    = '';

			$counter = 0;

			foreach ( $links as $key => $value ) {
				$text .= '<a target="_blank" href="' . $key . '">' . $value . '</a>';

				if( count( $links ) > 1 && count( $links ) != $counter ) {
					$text .= ' | ';
					$counter++;
				}
			}

			if ( apply_filters( 'my_youtube_videos_admin_footer_review_text', true ) ) {
				$text .= sprintf( __( 'If you like <strong>%1$s</strong> please leave a <a href="%2$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating on <a href="%2$s" target="_blank">WordPress.org</a>. A huge thank you in advance!', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name, My_YouTube_Videos()->wp_plugin_review_url );
			}

			return $text;
		}

		return $text;
	} // END admin_footer_text()

	/**
	 * Filters the update footer by placing details
	 * of the plugin and links to contribute or
	 * report issues with the plugin when viewing any
	 * of the plugin pages.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  $text
	 * @filter my_youtube_videos_update_footer_links
	 * @return string $text
	 */
	public function update_footer( $text ) {
		$screen = get_current_screen();

		if ( in_array( $screen->id, my_youtube_videos_get_screen_ids() ) ) {
			$version_link = esc_attr( admin_url( 'index.php?page=' . MY_YOUTUBE_VIDEOS_PAGE . '-about' ) );

			$text = '<span class="wrap">';

			$links = apply_filters( 'my_youtube_videos_update_footer_links', array(
				MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . 'blob/master/CONTRIBUTING.md?utm_source=wpadmin&utm_campaign=footer' => __( 'Contribute', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI . 'issues?state=open&utm_source=wpadmin&utm_campaign=footer' => __( 'Report Bugs', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
			) );

			foreach( $links as $key => $value ) {
				$text .= '<a target="_blank" class="add-new-h2" href="' . $key . '">' . $value . '</a>';
			}

			$text .= '</span>' . '</p>'.
			'<p class="alignright">'.
			sprintf( __( '%s Version', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name ).
			' : ' . esc_attr( My_YouTube_Videos()->version ) . '</p>';

			return $text;
		}

		return $text;
	} // END update_footer()

} // END class

} // END if class exists

return new My_YouTube_Videos_Admin();
?>