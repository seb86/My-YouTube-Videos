<?php
/**
 * My YouTube Videos General Tab Settings
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Settings_General_Tab' ) ) {

/**
 * My_YouTube_Videos_Settings_General_Tab
 */
class My_YouTube_Videos_Settings_General_Tab extends My_YouTube_Videos_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'general';

		$this->label = __( 'General', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN );

		add_filter( 'my_youtube_videos_settings_submenu_array',     array( $this, 'add_menu_page' ),     20 );
		add_filter( 'my_youtube_videos_settings_tabs_array',        array( $this, 'add_settings_page' ), 20 );

		add_action( 'my_youtube_videos_settings_' . $this->id,      array( $this, 'output' ) );
		add_action( 'my_youtube_videos_settings_save_' . $this->id, array( $this, 'save' ) );
	} // END __construct()

	/**
	 * Save settings
	 *
	 * @since  2.0.0
	 * @access public
	 * @global $current_tab
	 */
	public function save() {
		global $current_tab;

		$settings = $this->get_settings();
		My_YouTube_Videos_Admin_Settings::save_fields( $settings, $current_tab );
	}

	/**
	 * Get settings array
	 *
	 * @since  2.0.0
	 * @access public
	 * @return array
	 */
	public function get_settings() {
		return apply_filters( 'my_youtube_videos_' . $this->id . '_settings', array(
			array(
				'title' => __( 'General Settings', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'type'  => 'title',
				'desc'  => __( 'These settings will be the default settings for the shortcodes and widgets.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'    => $this->id . '_options'
			),

			array(
				'title'    => __( 'API Key', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . ' *',
				'desc'     => sprintf( __( 'This is required for the videos to be fetched. See <a href="%s" target="_blank">Getting Started with YouTube Data API</a>', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), 'https://developers.google.com/youtube/v3/getting-started' ),
				'id'       => 'my_youtube_videos_api_key',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:340px;',
				'autoload' => false
			),

			array(
				'title'    => __( 'Channel Name or ID', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . ' ',
				'desc'     => sprintf( __( 'See "<a href="%s" target="_blank">Account Advanced</a>" on YouTube.com for details.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), 'https://www.youtube.com/account_advanced' ),
				'id'       => 'my_youtube_videos_name',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:340px;',
				'autoload' => false
			),

			/*array(
				'title'    => __( 'Show Videos From', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Select where your videos will be displaying from.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_display_from',
				'class'    => 'chosen_select',
				'css'      => 'min-width:150px;',
				'default'  => 'channels',
				'type'     => 'select',
				'options'  => array(
					'channels'      => __( 'Channel', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					//'most_viewed' => __( 'Most Viewed', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'playlistItems'    => __( 'Playlist', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				),
				'desc_tip' => true,
			),*/

			array(
				'title'    => __( 'Playlist ID', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . ' *',
				'desc'     => __( 'Enter the main playlist ID you want to display videos from.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_playlist_id',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:340px;',
				'autoload' => false,
				'desc_tip' => true
			),

			array(
				'title'             => __( 'Display How Many', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'              => __( 'Enter the amount of videos you wish to display.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'                => 'my_youtube_videos_display_how_many',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'           => 1,
					'step'          => 1
				),
				'css'               => 'width:50px;',
				'default'           => '5',
				'autoload'          => false
			),

			/*array(
				'title'    => __( 'Order By', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Select the order you want to display the videos in?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_order_by',
				'class'    => 'chosen_select',
				'css'      => 'min-width:300px;',
				'default'  => '',
				'type'     => 'select',
				'options'  => array(
					'date'       => __( 'Date', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'rating'     => __( 'Ratings', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'relevance'  => __( 'Relevance', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'title'      => __( 'Title', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'videoCount' => __( 'Video Count', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'viewCount'  => __( 'View Count', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				),
				'desc_tip' => true,
			),*/

			array(
				'title'    => __( 'Display Thumbails?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to display the video thumbnail.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_display_video_thumbnail',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			array(
				'title'    => __( 'Display Titles?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to display the video titles.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_display_video_title',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			array(
				'title'    => __( 'Display Date Uploaded?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to display the date the video was uploaded on.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_display_date_uploaded',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			/*array(
				'title'    => __( 'Display Rating?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to display the video rating.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_display_rating',
				'default'  => 'no',
				'type'     => 'checkbox'
			),*/

			array(
				'title'    => __( 'Time to Cache Feed', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Select how much time you wish to cache the data feed before it is fetched again.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_time_to_cache_feed',
				'class'    => 'chosen_select',
				'css'      => 'min-width:150px;',
				'default'  => 'HOUR_IN_SECONDS',
				'type'     => 'select',
				'options'  => array(
					'MINUTE_IN_SECONDS' => __( 'By Minutes', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'HOUR_IN_SECONDS'   => __( 'By Hours', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'DAY_IN_SECONDS'    => __( 'By Days', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'WEEK_IN_SECONDS'   => __( 'By Weeks', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				),
				'desc_tip' => true,
			),

			array(
				'title'             => __( 'Multiple Time to Cache', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'              => __( 'Enter a number you will be multiplying the cache above.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'                => 'my_youtube_videos_multiply_cache',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'           => 1,
					'step'          => 1
				),
				'css'               => 'width:50px;',
				'default'           => '1',
				'autoload'          => false
			),

			array(
				'title'   => __( 'Remove all data on uninstall?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'    => __( 'If enabled, all settings for this plugin will all be deleted when uninstalling via Plugins > Delete.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'      => 'my_youtube_videos_uninstall_data',
				'default' => 'no',
				'type'    => 'checkbox'
			),

			array( 'type' => 'sectionend', 'id' => $this->id . '_options'),
		)); // End general settings
	}

}

} // end if class exists

return new My_YouTube_Videos_Settings_General_Tab();
?>