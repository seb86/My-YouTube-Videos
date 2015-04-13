<?php
/**
 * My YouTube Videos Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
include( 'my-youtube-videos-conditional-functions.php' );
include( 'my-youtube-videos-formatting-functions.php' );

/**
 * Fetch Videos from Channel
 *
 * @access public
 * @param  array $atts
 */
function my_youtube_videos_fetch_video_channel( $atts ) {
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
	$transient_name = 'my_youtube_channel_' . $name . '_' . $amount;

	if ( my_youtube_videos_load_feed_data( $transient_name ) === false ) {

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
	my_youtube_videos_display_videos( 'channel', $transient_name );
} // END my_youtube_videos_fetch_video_channel()

/**
 * Fetch Videos from a Playlist
 *
 * @access public
 * @param  array $atts
 */
function my_youtube_videos_fetch_video_playlist( $atts ) {
	// API Key
	$api_key             = get_option( 'my_youtube_videos_api_key' );

	// Default Values
	$playlist_ID         = get_option( 'my_youtube_videos_playlist_id' );
	$amount              = get_option( 'my_youtube_videos_display_how_many' );
	$time_to_cache       = get_option( 'my_youtube_videos_time_to_cache_feed' );
	$multiply_cache_time = get_option( 'my_youtube_videos_multiply_cache' );

	// If attributes exist, then override default.
	if ( isset( $atts['amount'] ) )              $amount = $atts['amount'];
	if ( isset( $atts['playlist_id'] ) )         $playlist_ID = $atts['playlist_id'];
	//if ( isset( $atts['time_to_cache'] ) )       $time_to_cache = $atts['time_to_cache'];
	//if ( isset( $atts['multiply_cache_time'] ) ) $multiply_cache_time = $atts['multiply_cache_time'];

	// The name of the transient we are storing the feed data in.
	$transient_name = 'my_youtube_plist_' . $amount . '_' . $playlist_ID;

	// Check that the data is not already available.
	if ( my_youtube_videos_load_feed_data( $transient_name ) === false ) {

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
	my_youtube_videos_display_videos( 'playlist', $transient_name );
} // END my_youtube_videos_fetch_video_playlist()

/**
 * Displays the YouTube videos.
 *
 * @access public
 * @param  string $from
 * @param  string $transient_name
 * @filter my_youtube_videos_item_data
 * @filter my_youtube_videos_date_format
 */
function my_youtube_videos_display_videos( $from = '', $transient_name, $options = array() ) {
	$show_thumbs    = get_option( 'my_youtube_videos_display_video_thumbnail' );
	$show_title     = get_option( 'my_youtube_videos_display_video_title' );
	$show_date      = get_option( 'my_youtube_videos_display_date_uploaded' );
	$thumb_size     = get_option( 'my_youtube_videos_thumbnail_size' );
	$list_container = get_option( 'my_youtube_videos_list_container' );
	$list_item      = get_option( 'my_youtube_videos_list_item' );
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
			$data = my_youtube_videos_load_feed_data( $transient_name );

			// Get only the list of videos from the playlist.
			$videos = $data['items'];

			// Display each video.
			foreach ( $videos as $video ) {
				echo '<li';
				if ( ! empty( $list_item ) ) echo ' class="' . $list_item . '"' ;
				echo apply_filters( 'my_youtube_videos_item_data', ' data-etag=' . $video['etag'] . ' data-id="' . $video['id'] . '"' );
				echo '>';

				if ( $show_thumbs == 'yes' ) {
					echo html_entity_decode( get_option( 'my_youtube_videos_before_video_thumb' ) );

					echo '<a href="https://www.youtube.com/watch?v=' . $video['snippet']['resourceId']['videoId'] . '"';
					if ( $new_window == 'yes' ) echo ' target="_blank"';
					echo '>';
					echo '<img src="' . $video['snippet']['thumbnails'][$thumb_size]['url'] . '" alt="' . $video['snippet']['description'] . '" />';
					echo '</a>';

					echo html_entity_decode( get_option( 'my_youtube_videos_after_video_thumb' ) );
				}

				if ( $show_title == 'yes' ) {
					echo html_entity_decode( $prefix_title );

					echo '<a href="https://www.youtube.com/watch?v=' . $video['snippet']['resourceId']['videoId'] . '"';
					if ( $new_window == 'yes' ) echo ' target="_blank"';
					echo '>';

					echo $video['snippet']['title'];

					echo html_entity_decode( $affix_title );

					echo '</a>';
				}

				if ( $show_date == 'yes' )
					$date = new DateTime( $video['snippet']['publishedAt'] );
					echo '<span data-publishedAt="' . $video['snippet']['publishedAt'] . '">' . __( 'Uploaded On', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) . ': ' . $date->format( apply_filters( 'my_youtube_videos_date_format', "F j, Y" ) ) . '</span>';

				echo '</li>';
			}

		break;

		case 'channel' :
			//echo 'Need to do this next';
		break;
	}

	echo '</ul>';

	do_action( 'my_youtube_videos_after_display' );
} // END my_youtube_videos_display_videos()

/**
 * Load the feed data when called.
 *
 * @access public
 * @param  string $value
 * @return array
 */
function my_youtube_videos_load_feed_data( $value ) {
	return get_transient( $value );
} // END my_youtube_videos_load_feed_data()

?>