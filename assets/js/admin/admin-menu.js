/**
 * My YouTube Videos Admin Menu JS
 */
jQuery(function($){
	// Hide the 'My YouTube Videos' item under the custom menu
	$('.wp-submenu-head:contains("' + my_youtube_videos_admin_params.plugin_menu_name + '")').next().children(':first').hide();
});