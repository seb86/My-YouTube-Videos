<?php
/**
 * My YouTube Videos Appearance Tab Settings
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Settings_Appearance_Tab' ) ) {

/**
 * My_YouTube_Videos_Settings_Appearance_Tab
 */
class My_YouTube_Videos_Settings_Appearance_Tab extends My_YouTube_Videos_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'appearance';

		$this->label = __( 'Appearance', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN );

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
				'title' => __( 'Appearance Settings', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'type'  => 'title',
				'desc'  => __( 'These settings will be the default settings for the shortcodes and widgets.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'    => $this->id . '_options'
			),

			array(
				'title'    => __( 'Thumbnail Size', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Select the size of the video thumbnail you want to display.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_thumbnail_size',
				'class'    => 'chosen_select',
				'css'      => 'min-width:150px;',
				'default'  => 'default',
				'type'     => 'select',
				'options'  => array(
					'default'  => __( 'Default', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'medium'   => __( 'Medium', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'high'     => __( 'High', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
					'standard' => __( 'Standard', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				),
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'List Container', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Enter the class name of the list container.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_list_container',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false
			),

			array(
				'title'    => __( 'List Item', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'Enter the class name that will be applied to each list item.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_list_item',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false
			),

			array(
				'title'    => __( 'Before Video Thumbnail', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => '',
				'id'       => 'my_youtube_videos_before_video_thumb',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false,
				'html'     => 'allowed'
			),

			array(
				'title'    => __( 'After Video Thumbnail', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => '',
				'id'       => 'my_youtube_videos_after_video_thumb',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false,
				'html'     => 'allowed'
			),

			array(
				'title'    => __( 'Video Title Prefix', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => '',
				'id'       => 'my_youtube_videos_title_prefix',
				'default'  => '<h2>',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false,
				'html'     => 'allowed'
			),

			array(
				'title'    => __( 'Video Title Affix', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => '',
				'id'       => 'my_youtube_videos_title_affix',
				'default'  => '</h2>',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'autoload' => false,
				'html'     => 'allowed'
			),

			array(
				'title'    => __( 'Open Videos in Pop-up?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to view the videos in a pop-up display when a video is clicked on.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_open_popup',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			array(
				'title'    => __( 'Open Videos in New Window?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'desc'     => __( 'If yes, enable to view the video on YouTube.com in a new window.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ),
				'id'       => 'my_youtube_videos_open_new_window',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			array( 'type' => 'sectionend', 'id' => $this->id . '_options'),
		)); // End general settings
	}

}

} // end if class exists

return new My_YouTube_Videos_Settings_Appearance_Tab();
?>