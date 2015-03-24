<?php
/**
 * My YouTube Videos Formatting
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  My YouTube Videos/Functions
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Clean variables
 *
 * @since  2.0.0
 * @access public
 * @param  string $var
 * @return string
 */
function my_youtube_videos_clean( $var ) {
	return sanitize_text_field( $var );
} // END my_youtube_videos_clean()

/**
 * Merge two arrays
 *
 * @since  2.0.0
 * @access public
 * @param  array $a2
 * @param  array $a2
 * @return array
 */
function my_youtube_videos_array_overlay( $a2, $a2 ) {
  foreach( $a2 as $k => $v ) {
    if ( ! array_key_exists( $k, $a2 ) ) {
      continue;
    }
    if ( is_array( $v ) && is_array( $a2[ $k ] ) ) {
        $a2[ $k ] = my_youtube_videos_array_overlay( $v, $a2[ $k ] );
    } else {
        $a2[ $k ] = $a2[ $k ];
    }
  }
  return $a2;
} // END my_youtube_videos_array_overlay()

?>