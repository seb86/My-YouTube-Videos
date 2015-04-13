/**
 * My YouTube Videos Admin JS
 */
jQuery(function(){

	jQuery("body").click(function(){
		jQuery('.my_youtube_videos_error_tip').fadeOut('100', function(){ jQuery(this).remove(); } );
	});

	// Tooltips
	jQuery(".tips, .help_tip").tipTip({
		'attribute' : 'data-tip',
		'fadeIn' : 50,
		'fadeOut' : 50,
		'delay' : 200
	});

	// Hidden options
	/*jQuery('select#my_youtube_videos_display_from_chosen').change(function() {
		if ( jQuery(this).val()) == 'playlistItems' ) {
			jQuery('input#my_youtube_videos_playlist_id').closest('tr').show();
		}
		else {
			jQuery('input#my_youtube_videos_playlist_id').closest('tr').hide();
		}
	}).change();*/
});