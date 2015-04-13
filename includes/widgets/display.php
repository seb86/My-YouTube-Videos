<?php
/**
 * My YouTube Videos Display Widget
 *
 * @since    2.0.0
 * @author   Sebastien Dumont
 * @category Widgets
 * @extends  My_YouTube_Videos_Widget_Abstract
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class My_YouTube_Videos_Widget_Display extends My_YouTube_Videos_Widget_Abstract {

	/**
	 * Constructor
	 */
	function __construct() {
		$this->widget_name        = __( 'My YouTube Videos - Display', 'my-youtube-videos' );
		$this->widget_description = __( 'Display your YouTube videos.', 'my-youtube-videos' );
		$this->widget_id          = 'my_youtube_videos';
		$this->widget_cssclass    = 'widget_my_youtube_videos';

		$this->settings = array( 
			'title' => array( 
				'type'  => 'text',
				'std'   => __( 'My YouTube Videos', 'my-youtube-videos' ),
				'label' => __( 'Title', 'my-youtube-videos' )
			),
			'playlist_id' => array( 
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Playlist ID', 'my-youtube-videos')
			),
			'amount' => array( 
				'type'  => 'text',
				'std'   => '5',
				'label' => __( 'How many videos do you want to display?', 'my-youtube-videos')
			),
			'show_thumbs' => array( 
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show Thumbnails?', 'my-youtube-videos' )
			),
			'show_title' => array( 
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show Video Title?', 'my-youtube-videos' )
			),
			'show_date' => array( 
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Date Uploaded?', 'my-youtube-videos' )
			),
			'thumb_size' => array( 
				'type'  => 'select',
				'std'   => 'default',
				'label' => __( 'Thumbnail Size', 'my-youtube-videos' ),
				'options' => array(
					'default'  => __( 'Default', 'my-youtube-videos' ),
					'medium'   => __( 'Medium', 'my-youtube-videos' ),
					'high'     => __( 'High', 'my-youtube-videos' ),
					'standard' => __( 'Standard', 'my-youtube-videos' ),
				)
			),
			'list_container' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'List Container', 'my-youtube-videos' )
			),
			'list_item' => array( 
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'List Item', 'my-youtube-videos' )
			),
			'new_window' => array( 
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Open Videos in New Window?', 'my-youtube-videos' )
			)
		);

		parent::__construct();
	}

	/**
	 * Displays the widget.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$this->widget_start( $args, $instance );

		echo apply_filters( 'my_youtube_videos_widget_display_before_list', '<div id="my_youtube_videos" class="widget">' );

		print_r( $instance );

		echo my_youtube_videos_fetch_video_playlist( $instance );

		echo apply_filters( 'my_youtube_videos_widget_display_after_list', '</div>' );

		$this->widget_end( $args );

		echo $this->cache_widget( $args, ob_get_clean() );
	} // END widget()

} // My_YouTube_Videos_Widget_Display
?>