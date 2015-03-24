<?php
/**
 * Admin View: Settings
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
?>
<div class="wrap my-youtube-videos">
	<form method="post" id="mainform" action="" enctype="multipart/form-data">
		<h2 class="nav-tab-wrapper">
			<?php echo My_YouTube_Videos()->name; ?>
			<?php
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . admin_url( 'options-general.php?page=' . MY_YOUTUBE_VIDEOS_PAGE . '-settings&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				}
				do_action( 'my_youtube_videos_settings_tabs' );
			?>
		</h2>
		<?php
		do_action( 'my_youtube_videos_sections_' . $current_tab );
		do_action( 'my_youtube_videos_settings_' . $current_tab );
		?>
		<p class="submit">
			<?php if ( ! isset( $GLOBALS['hide_save_button'] ) ) { ?>
				<input name="save" class="button-primary" type="submit" value="<?php _e( 'Save changes', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ); ?>" />
			<?php } ?>
			<input type="hidden" name="subtab" id="last_tab" />
			<?php wp_nonce_field( 'my-youtube-videos-settings' ); ?>
		</p>
	</form>
</div>
