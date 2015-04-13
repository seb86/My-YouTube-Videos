<?php
/**
 * My YouTube Videos Shortcodes.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Class
 * @package  My YouTube Videos/Classes
 * @license  GPL-2.0+
 */
class My_YouTube_Videos_Shortcodes {

	/**
	 * Initiate Shortcodes
	 *
	 * @since  2.0.0
	 * @access public static
	 */
	public static function init() {
		$shortcodes = array(
			'my_youtube_videos_display' => __CLASS__ . '::list_videos',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	} // END init()

	/**
	 * Shortcode Wrapper
	 *
	 * @since  2.0.0
	 * @access public static
	 * @param  mixed  $function
	 * @param  array  $atts (default: array())
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function, 
		$atts    = array(), 
		$wrapper = array(
			'class'  => 'my_youtube_videos', 
			'before' => null, 
			'after'  => null
		)
	) {
		ob_start();

		$before = empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		$after  = empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		echo $before;
		call_user_func( $function, $atts );
		echo $after;

		return ob_get_clean();
	}

	/**
	 * Display videos shortcode.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  mixed  $atts
	 * @return string
	 */
	public static function list_videos( $atts ) {
		return self::shortcode_wrapper( array( 'My_YouTube_Videos_Shortcode_Display', 'output' ), $atts );
	} // END list_videos()

} // END My_YouTube_Videos_Shortcodes class
?>
