<?php
/**
 * My YouTube Videos Shortcode Display
 *
 * Displays videos from either a YouTube Channel or Playlist.
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
	 * @access public static
	 * @param  array  $atts
	 * @return string
	 */
	public static function get( $atts ) {
		return My_YouTube_Videos_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	} // END get()

	/**
	 * Output the shortcode.
	 *
	 * @access public static
	 * @param  array  $atts
	 * @return void
	 */
	public static function output( $atts ) {
		extract( shortcode_atts( array(
			//'from'                => get_option( 'my_youtube_videos_display_from' ),
			'amount'              => get_option( 'my_youtube_videos_display_how_many' ),
			'playlist_id'         => get_option( 'my_youtube_videos_playlist_id' ),
			'name'                => get_option( 'my_youtube_videos_name' ),
			//'time_to_cache'       => get_option( 'my_youtube_videos_time_to_cache_feed' ),
			//'multiply_cache_time' => get_option( 'my_youtube_videos_multiply_cache' ),
		), $atts ) );

		$from = '';

		// Check if attribute "from" was added to the shortcode.
		if ( isset ( $atts['from'] ) ) {
			if ( $atts['from'] == 'playlist' ) {
				$from = 'playlistItems';
			} else if ( $atts['from'] == 'channel' ) {
				$from = 'channels';
			}
		}

		switch( $from ) {
			default :
			case 'playlistItems':
				my_youtube_videos_fetch_video_playlist( $atts );
			break;

			case 'channels':
				my_youtube_videos_fetch_video_channel( $atts );
			break;
		}
	} // END output()

} // END class

?>