<?php
/**
 * My YouTube Videos Widgets
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  My YouTube Videos/Functions
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( 'abstracts/abstract-my-youtube-videos-widget.php' );
include_once( 'widgets/display.php' );

function my_youtube_videos_register_widgets(){
	register_widget( 'My_YouTube_Videos_Widget_Display' );
}

add_action( 'widgets_init', 'my_youtube_videos_register_widgets' );

?>