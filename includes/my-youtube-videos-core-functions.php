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
 * Get an image size.
 *
 * Variable is filtered by my_youtube_videos_get_image_size_{image_size}
 *
 * @since  1.0.0
 * @access public
 * @param  string $image_size
 * @return array
 */
function my_youtube_videos_get_image_size( $image_size ) {
	if ( in_array( $image_size, array( '_thumbnail', '_single' ) ) ) {
		$size           = get_option( $image_size . '_image_size', array() );
		$size['width']  = isset( $size['width'] ) ? $size['width'] : '300';
		$size['height'] = isset( $size['height'] ) ? $size['height'] : '300';
		$size['crop']   = isset( $size['crop'] ) ? $size['crop'] : 1;
	}
	else {
		$size = array(
			'width'  => '300',
			'height' => '300',
			'crop'   => 1
		);
	}

	return apply_filters( 'my_youtube_videos_get_image_size_' . $image_size, $size );
} // END my_youtube_videos_get_image_size()

/**
 * Queue some JavaScript code to be output in the footer.
 *
 * @since  1.0.0
 * @access public
 * @param  string $code
 * @global $my_youtube_videos_queued_js
 */
function my_youtube_videos_enqueue_js( $code ) {
	global $my_youtube_videos_queued_js;

	if ( empty( $my_youtube_videos_queued_js ) )
		$my_youtube_videos_queued_js = "";
	$my_youtube_videos_queued_js .= "\n" . $code . "\n";
} // END my_youtube_videos_enqueue_js()

/**
 * Output any queued javascript code in the footer.
 *
 * @since  1.0.0
 * @access public
 * @global $my_youtube_videos_queued_js
 * @return $my_youtube_videos_queued_js
 */
function my_youtube_videos_print_js() {
	global $my_youtube_videos_queued_js;

	if ( ! empty( $my_youtube_videos_queued_js ) ) {
		echo "<!-- My YouTube Videos JavaScript-->\n<script type=\"text/javascript\">\njQuery(document).ready(function($) {";

		// Sanitize
		$my_youtube_videos_queued_js = wp_check_invalid_utf8( $my_youtube_videos_queued_js );
		$my_youtube_videos_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $my_youtube_videos_queued_js );
		$my_youtube_videos_queued_js = str_replace( "\r", '', $my_youtube_videos_queued_js );

		echo $my_youtube_videos_queued_js . "});\n</script>\n";

		unset( $my_youtube_videos_queued_js );
	}

} // END my_youtube_videos_print_js()

/**
 * Set a cookie - wrapper for setcookie using WP constants
 *
 * @since 1.0.0
 * @param string  $name   Name of the cookie being set
 * @param string  $value  Value of the cookie
 * @param integer $expire Expiry of the cookie
 * @return void
 */
function my_youtube_videos_setcookie( $name, $value, $expire = 0 ) {
	if ( ! headers_sent() ) {
		setcookie( $name, $value, $expire, COOKIEPATH, COOKIE_DOMAIN, false );
	} else if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		trigger_error( "Cookie cannot be set - headers already sent", E_USER_NOTICE );
	}
} // END my_youtube_videos_setcookie()

?>