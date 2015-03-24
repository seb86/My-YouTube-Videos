<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated my-youtube-videos-message">
	<p><?php echo sprintf( __( '<strong>%s Data Update Required</strong> &#8211; I just need to update you to the latest version.', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->name ); ?></p>
	<p class="submit"><a href="<?php echo add_query_arg( 'do_update_my_youtube_videos', 'true', admin_url( 'admin.php?page=' . MY_YOUTUBE_VIDEOS_PAGE . '-settings' ) ); ?>" class="my-youtube-videos-update-now button-primary"><?php _e( 'Run the updater', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ); ?></a></p>
</div>
<script type="text/javascript">
	jQuery('.my-youtube-videos-update-now').click('click', function(){
		var answer = confirm( '<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ); ?>' );
		return answer;
	});
</script>
