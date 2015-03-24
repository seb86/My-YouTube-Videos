<?php
/**
 * Update My YouTube Videos to 2.0.0
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos/Admin/Updates
 * @version  2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Fetch Current Values
$youtube_user    = get_option( 'myyt_username' );
$how_many_videos = get_option( 'myyt_display_many' );
$thumbnails      = get_option( 'myyt_display_thumb' );
$upload_date     = get_option( 'myyt_display_dateadded' );
$hd_videos       = get_option( 'myyt_enable_hd' );

// Update Values
if ( ! empty( $youtube_user ) )    update_option( 'my_youtube_videos_name', $youtube_user );
if ( ! empty( $how_many_videos ) ) update_option( 'my_youtube_videos_display_how_many', $how_many_videos );
if ( ! empty( $upload_date ) )     update_option( 'my_youtube_videos_display_date_uploaded', $upload_date );

// Delete Old Values
delete_option( 'myyt_username' );
delete_option( 'myyt_display_many' );
delete_option( 'myyt_display_thumb' );
delete_option( 'myyt_display_dateadded' );
delete_option( 'myyt_enable_hd' );

?>