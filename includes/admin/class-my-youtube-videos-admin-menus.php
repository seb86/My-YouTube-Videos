<?php
/**
 * Setup menus in the WordPress admin.
 *
 * @since    2.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  My YouTube Videos
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos_Admin_Menus' ) ) {

/**
 * Class - My_YouTube_Videos_Admin_Menus
 *
 * @since 2.0.0
 */
class My_YouTube_Videos_Admin_Menus {

	/**
	 * Constructor
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Add admin menus
		add_action( 'admin_menu',        array( $this, 'admin_menu' ), 9 );

		// Add menu seperator
		//add_action( 'admin_init',        array( $this, 'add_admin_menu_separator' ) );

		// Add menu order and highlighter
		//add_filter( 'menu_order',        array( $this, 'menu_order' ) );

		//add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
	} // END __construct()

	/**
	 * Add menu seperator
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  $position
	 * @global $menu
	 */
	public function add_admin_menu_separator( $position ) {
		global $menu;

		if ( current_user_can( My_YouTube_Videos()->manage_plugin ) ) {
			$menu[ $position ] = array(
				0	=> '',
				1	=> 'read',
				2	=> 'separator' . $position,
				3	=> '',
				4	=> 'wp-menu-separator my-youtube-videos'
			);
		}
	} // END add_admin_menu_separator()

	/**
	 * Add menu items.
	 *
	 * @since  2.0.0
	 * @access public
	 * @global $menu
	 * @global $my_youtube_videos
	 * @global $wp_version
	 */
	public function admin_menu() {
		global $menu, $my_youtube_videos, $wp_version;

		/*if ( current_user_can( My_YouTube_Videos()->manage_plugin ) ) {
			$menu[] = array( '', 'read', 'separator-my-youtube-videos', '', 'wp-menu-separator my-youtube-videos' );
		}*/

		//add_menu_page( My_YouTube_Videos()->title_name, My_YouTube_Videos()->menu_name, My_YouTube_Videos()->manage_plugin, MY_YOUTUBE_VIDEOS_PAGE, array( $this, 'my_youtube_videos_page' ), null, '25.5' );

		//$settings_page = add_submenu_page( MY_YOUTUBE_VIDEOS_PAGE, sprintf( __( '%s Settings', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ), My_YouTube_Videos()->title_name ), __( 'Settings', MY_YOUTUBE_VIDEOS_TEXT_DOMAIN ) , My_YouTube_Videos()->manage_plugin, MY_YOUTUBE_VIDEOS_PAGE . '-settings', array( $this, 'settings_page' ) );

		add_options_page( My_YouTube_Videos()->title_name, My_YouTube_Videos()->menu_name, My_YouTube_Videos()->manage_plugin, MY_YOUTUBE_VIDEOS_PAGE . '-settings', array( $this, 'settings_page' ) );

		register_setting( 'my_youtube_videos_status_settings_fields', 'my_youtube_videos_status_options' );
	} // END admin_menu()

	/**
	 * Reorder the plugin menu items in admin.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  mixed $menu_order
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array
		$my_youtube_videos_menu_order = array();

		// Get the index of our custom separator
		$my_youtube_videos_separator = array_search( 'separator-my-youtube-videos', $menu_order );

		// Loop through menu order and do some rearranging
		foreach ( $menu_order as $index => $item ) {
			if ( ( ( str_replace( '_', '-', MY_YOUTUBE_VIDEOS_SLUG ) ) == $item ) ) {
				$my_youtube_videos_menu_order[] = 'separator-' . str_replace( '_', '-', MY_YOUTUBE_VIDEOS_SLUG );
				$my_youtube_videos_menu_order[] = $item;
				$my_youtube_videos_menu_order[] = 'admin.php?page=' . MY_YOUTUBE_VIDEOS_PAGE;
				unset( $menu_order[$my_youtube_videos_separator] );
			}
			elseif ( !in_array( $item, array( 'separator-' . str_replace( '_', '-', MY_YOUTUBE_VIDEOS_SLUG ) ) ) ) {
				$my_youtube_videos_menu_order[] = $item;
			}
		}

		// Return menu order
		return $my_youtube_videos_menu_order;
	} // END menu_order()

	/**
	 * Sets the menu order depending on user access.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return bool
	 */
	public function custom_menu_order() {
		if ( ! current_user_can( My_YouTube_Videos()->manage_plugin ) ) {
			return false;
		}

		return true;
	} // END custom_menu_order()

	/**
	 * Initialize the My YouTube Videos main page.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function my_youtube_videos_page() {
		include_once( 'class-my-youtube-videos-admin-page.php' );
		My_YouTube_Videos_Admin_Page::output();
	} // END my_youtube_videos_page()

	/**
	 * Initialize the My YouTube Videos settings page.
	 * @since  2.0.0
	 * @access public
	 */
	public function settings_page() {
		include_once( 'class-my-youtube-videos-admin-settings.php' );
		My_YouTube_Videos_Admin_Settings::output();
	}

} // END My_YouTube_Videos_Admin_Menus class.

} // END if class exists.

return new My_YouTube_Videos_Admin_Menus();
?>