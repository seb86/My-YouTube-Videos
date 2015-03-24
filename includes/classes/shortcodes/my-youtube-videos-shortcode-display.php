<?php
/**
 * Display Videos Shortcodes
 *
 * Displays the videos from Channel or Playlist.
 *
 * @version  2.0.0
 * @author   Sébastien Dumont
 * @category Shortcodes
 * @package  My YouTube Videos/Shortcodes
 * @license  GPL-2.0+
 */
class My_YouTube_Videos_Shortcode_Display {

	/**
	 * Get the shortcode content.
	 *
	 * @access public
	 * @param  array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		return My_YouTube_Videos_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	} // END get()

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param  array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		extract( shortcode_atts( array(
			'from'                => get_option( 'my_youtube_videos_display_from' ),
			'amount'              => get_option( 'my_youtube_videos_display_how_many' ),
			'playlist_id'         => get_option( 'my_youtube_videos_playlist_id' ),
			'name'                => get_option( 'my_youtube_videos_name' ),
			//'time_to_cache'       => get_option( 'my_youtube_videos_time_to_cache_feed' ),
			//'multiply_cache_time' => get_option( 'my_youtube_videos_multiply_cache' ),
		), $atts ) );

		// Check if attribute "from" was added to the shortcode.
		if ( isset ( $atts['from'] ) ) {
			if ( $atts['from'] == 'playlist' ) {
				$from = 'playlistItems';
			} else if ( $atts['from'] == 'channel' ) {
				$from = 'channels';
			}
		}

		switch( $from ) {
			case 'channels':
				self::fetch_video_channel( $atts );
			break;

			case 'playlistItems':
				self::fetch_video_playlist( $atts );
			break;
		}
	} // END output()

	/**
	 * Fetch Videos from Channel
	 *
	 * @access private
	 * @param  array $atts
	 */
	private static function fetch_video_channel( $atts ) {
		// API Key
		$api_key             = get_option( 'my_youtube_videos_api_key' );

		// Default Values
		$name                = get_option( 'my_youtube_videos_name' );
		$amount              = get_option( 'my_youtube_videos_display_how_many' );
		$time_to_cache       = get_option( 'my_youtube_videos_time_to_cache_feed' );
		$multiply_cache_time = get_option( 'my_youtube_videos_multiply_cache' );

		// If shortcode attributes exist, then override default.
		if ( isset( $atts['name'] ) )                $name = $atts['name'];
		if ( isset( $atts['amount'] ) )              $amount = $atts['amount'];
		//if ( isset( $atts['time_to_cache'] ) )       $time_to_cache = $atts['time_to_cache'];
		//if ( isset( $atts['multiply_cache_time'] ) ) $multiply_cache_time = $atts['multiply_cache_time'];

		// The name of the transient we are storing the feed data in.
		$transient_name = 'my_youtube_channel_' . $amount . '_' . $name;
		//if ( isset( $atts['time_to_cache'] ) )       $transient_name .= '_' . $time_to_cache;
		//if ( isset( $atts['multiply_cache_time'] ) ) $transient_name .= '_' . $multiply_cache_time;

		if ( self::load_feed_data( $transient_name ) === false ) {

			// Feed URL
			$feed = 'https://www.googleapis.com/youtube/v3/channels?forUsername=' . $name . '&part=snippet,contentDetails&maxResults=' . $amount . '&key=' . $api_key;

			// Fetch data
			$response = wp_remote_get( $feed, array( 'timeout' => 120 ) );

			// Check for error
			if ( is_wp_error( $response ) ) return;

			// Parse remote data
			$data = wp_remote_retrieve_body( $response );

			// Check for error
			if ( is_wp_error( $data ) ) return;

			// Decode json data
			$data = json_decode( $data, true );

			// Save data
			set_transient( $transient_name, $data, $multiply_cache_time * constant( $time_to_cache ) );
		}

		// Display the videos
		self::display_videos( 'channel', $transient_name );
	} // END fetch_video_channel()

	/**
	 * Fetch Videos from a Playlist
	 *
	 * @access private
	 * @param  array $atts
	 */
	private static function fetch_video_playlist( $atts ) {
		// API Key
		$api_key             = get_option( 'my_youtube_videos_api_key' );

		// Default Values
		$playlist_ID         = get_option( 'my_youtube_videos_playlist_id' );
		$amount              = get_option( 'my_youtube_videos_display_how_many' );
		//$time_to_cache       = get_option( 'my_youtube_videos_time_to_cache_feed' );
		//$multiply_cache_time = get_option( 'my_youtube_videos_multiply_cache' );

		// If shortcode attributes exist, then override default.
		if ( isset( $atts['amount'] ) )              $amount = $atts['amount'];
		if ( isset( $atts['playlist_id'] ) )         $playlist_ID = $atts['playlist_id'];
		//if ( isset( $atts['time_to_cache'] ) )       $time_to_cache = $atts['time_to_cache'];
		//if ( isset( $atts['multiply_cache_time'] ) ) $multiply_cache_time = $atts['multiply_cache_time'];

		// The name of the transient we are storing the feed data in.
		$transient_name = 'my_youtube_playlist_' . $amount . '_' . $playlist_ID;
		//if ( isset( $atts['time_to_cache'] ) )       $transient_name .= '_' . $time_to_cache;
		//if ( isset( $atts['multiply_cache_time'] ) ) $transient_name .= '_' . $multiply_cache_time;

		// Check that the data is not already available.
		if ( self::load_feed_data( $transient_name ) === false ) {

			// Feed URL
			$feed = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=' . $amount . '&playlistId=' . $playlist_ID . '&key=' . $api_key;

			// Fetch data
			$response = wp_remote_get( $feed, array( 'timeout' => 120 ) );

			// Check for error
			if ( is_wp_error( $response ) ) return;

			// Parse remote data
			$data = wp_remote_retrieve_body( $response );

			// Check for error
			if ( is_wp_error( $data ) ) return;

			// Decode json data
			$data = json_decode( $data, true );

			// Save Data
			set_transient( $transient_name, $data, $multiply_cache_time * constant( $time_to_cache ) );
		} // end if

		// Display the videos
		self::display_videos( 'playlist', $transient_name );
	} // END fetch_video_playlist()

	/**
	 * Displays YouTube Videos.
	 *
	 * @access public
	 * @filter my_youtube_videos_item_data
	 */
	public function display_videos( $from = '', $transient_name ) {
		$show_thumbs    = get_option( 'my_youtube_videos_display_video_thumbnail' );
		$show_title     = get_option( 'my_youtube_videos_display_video_title' );
		$show_date      = get_option( 'my_youtube_videos_display_date_uploaded' );
		$thumb_size     = get_option( 'my_youtube_videos_thumbnail_size' );
		$list_container = get_option( 'my_youtube_videos_list_container' );
		$prefix_title   = get_option( 'my_youtube_videos_title_prefix' );
		$affix_title    = get_option( 'my_youtube_videos_title_affix' );
		$new_window     = get_option( 'my_youtube_videos_open_new_window' );

		do_action( 'my_youtube_videos_before_display' );

		echo '<ul';
		if ( ! empty( $list_container ) ) echo ' class="' . $list_container . '"';
		echo '>';

		switch ( $from ) {
			case 'playlist' :

				// Load the saved results.
				$data = self::load_feed_data( $transient_name );

				// Get only the list of videos from the playlist.
				$videos = $data['items'];

				// Display each video.
				foreach ( $videos as $video ) {
					echo '<li' . apply_filters( 'my_youtube_videos_item_data', ' data-etag="' . $video['etag'] . '" data-id="' . $video['id'] . '"' ) . '>';

					if ( $show_date == 'yes' ) echo '<span>' . __( 'Uploaded On', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . ': ' .$video['snippet']['publishedAt'] . '</span>';

					if ( $show_title == 'yes' ) {
						echo html_entity_decode( $prefix_title );

						echo '<a href="https://www.youtube.com/watch?v=' . $video['snippet']['resourceId']['videoId'] . '"';
						if ( $new_window == 'yes' ) echo ' target="_blank"';
						echo '>';

						echo $video['snippet']['title'];

						echo html_entity_decode( $affix_title );

						echo '</a>';
					}

					if ( $show_thumbs == 'yes' ) {
						echo html_entity_decode( get_option( 'my_youtube_videos_before_video_thumb' ) );

						echo '<a href="https://www.youtube.com/watch?v=' . $video['snippet']['resourceId']['videoId'] . '"';
						if ( $new_window == 'yes' ) echo ' target="_blank"';
						echo '>';
						echo '<img src="' . $video['snippet']['thumbnails'][$thumb_size]['url'] . '" alt="" />';
						echo '</a>';

						echo html_entity_decode( get_option( 'my_youtube_videos_after_video_thumb' ) );
					}

					echo '</li>';
				}

			break;

			case 'channel' :
				//echo 'Need to do this next';
			break;
		}

		echo '</ul>';

		do_action( 'my_youtube_videos_after_display' );
	} // END display_videos()

	public function load_feed_data( $value ) {
		return get_transient( $value );
	}

}

?>