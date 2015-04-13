<?php
/*
 * Plugin Name:       My YouTube Videos
 * Plugin URI:        https://github.com/seb86/My-YouTube-Videos
 * Description:       Displays your latest uploaded videos from your YouTube account on a full page or in your sidebar using the widget.
 * Version:           2.0.0 Beta 2
 * Author:            Sébastien Dumont
 * Author URI:        http://www.sebastiendumont.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-youtube-videos
 * Domain Path:       languages
 * Network:           false
 * GitHub Plugin URI: https://github.com/seb86/My-YouTube-Videos
 *
 * My YouTube Videos is distributed under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * My YouTube Videos is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with My YouTube Videos.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @package My_YouTube_Videos
 * @author  Sébastien Dumont
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'My_YouTube_Videos' ) ) {

/**
 * Main My YouTube Videos Class
 *
 * @since 2.0.0
 */
final class My_YouTube_Videos {

	/**
	 * The single instance of the class
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $_instance = null;

	/**
	 * Slug
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $plugin_slug = 'my_youtube_videos';

	/**
	 * Text Domain
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $text_domain = 'my-youtube-videos';

	/**
	 * The Plugin Name.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $name = "My YouTube Videos";

	/**
	 * The Plugin Version.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $version = "2.0.0";

	/**
	 * The WordPress version the plugin requires minumum.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $wp_version_min = "4.0";

	/**
	 * The Plugin URI.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $web_url = "http://www.sebastiendumont.com/plugins/my-youtube-videos/";

	/**
	 * The Plugin documentation URI.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $doc_url = "https://github.com/seb86/My-YouTube-Videos/wiki/";

	/**
	 * The WordPress.org Plugin URI.
	 *
	 * @since   2.0.0
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_url = "https://wordpress.org/plugins/my-youtube-videos";

	/**
	 * The WordPress.org Plugin Support URI.
	 *
	 * @since   2.0.0
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_support_url = "https://wordpress.org/support/plugin/my-youtube-videos";

	/**
	 * The WordPress.org Plugin Review URI.
	 *
	 * @since   1.0.2
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_review_url = 'https://wordpress.org/support/view/plugin-reviews/my-youtube-videos?filter=5#postform';

	/**
	 * GitHub Repo URI
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $github_repo_url = "https://github.com/seb86/My-YouTube-Videos/";

	/**
	 * The Plugin menu name.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $menu_name = "My YouTube Videos";

	/**
	 * The Plugin title page name.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $title_name = "My YouTube Videos";

	/**
	 * Manage Plugin.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $manage_plugin = "manage_options";

	/**
	 * Main My YouTube Videos Instance
	 *
	 * Ensures only one instance of My YouTube Videos is loaded or can be loaded.
	 *
	 * @since  2.0.0
	 * @access public static
	 * @see    My_YouTube_Videos()
	 * @return My YouTube Videos instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new My_YouTube_Videos;
		}

		return self::$_instance;
	} // END instance()

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'my-youtube-videos' ), $this->version );
	} // END __clone()

	/**
	 * Disable unserializing of the class
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'my-youtube-videos' ), $this->version );
	} // END __wakeup()

	/**
	 * Constructor
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Auto-load classes on demand
		if ( function_exists( "__autoload" ) )
			spl_autoload_register( "__autoload" );

		spl_autoload_register( array( $this, 'autoload' ) );

		// Define constants
		$this->define_constants();

		// Check plugin requirements
		$this->check_requirements();

		// Include required files
		$this->includes();

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta',                                    array( $this, 'plugin_row_meta' ), 10, 2 );
		add_action( 'init',                                               array( $this, 'init_my_youtube_videos' ), 0 );

		// Loaded action
		do_action( 'my_youtube_videos_loaded' );
	} // END __construct()

	/**
	 * Plugin action links.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  mixed $links
	 * @return void
	 */
	public function action_links( $links ) {
		if( current_user_can( $this->manage_plugin ) ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'options-general.php?page=' . MY_YOUTUBE_VIDEOS_PAGE . '-settings' ) . '">' . __( 'Settings', 'my-youtube-videos' ) . '</a>',
				'<a href="' . $this->wp_plugin_support_url . '" target="_blank">' . __( 'Support', 'my-youtube-videos' ) . '</a>'
			);
			return array_merge( $plugin_links, $links );
		}
		return $links;
	} // END action_links()

	/**
	 * Plugin row meta links
	 *
	 * @filter my_youtube_videos_about_text_link
	 * @filter my_youtube_videos_documentation_url
	 * @since  2.0.0
	 * @access public
	 * @param  array  $input already defined meta links
	 * @param  string $file  plugin file path and name being processed
	 * @return array  $input
	 */
	public function plugin_row_meta( $input, $file ) {
		if ( plugin_basename( __FILE__ ) !== $file ) {
			return $input;
		}

		$links = array(
			'<a href="' . esc_url( apply_filters( 'my_youtube_videos_documentation_url', $this->doc_url ) ) . '">' . __( 'Documentation', 'my-youtube-videos' ) . '</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	} // END plugin_row_meta()

	/**
	 * Auto-load My YouTube Videos classes on demand to reduce memory consumption.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  string $class
	 * @return void
	 */
	public function autoload( $class ) {
		$file  = 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
		$path  = '';

		if ( strpos( $class, 'my_youtube_videos_shortcode_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/classes/shortcodes/';
		}
		else if ( strpos( $class, 'my_youtube_videos_admin' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/admin/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			include_once( $path . $file );
			return;
		}
	} // END autoload()

	/**
	 * Define Constants
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function define_constants() {
		if ( ! defined( 'MY_YOUTUBE_VIDEOS' ) )                       define( 'MY_YOUTUBE_VIDEOS', $this->name );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_FILE' ) )                  define( 'MY_YOUTUBE_VIDEOS_FILE', __FILE__ );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_VERSION' ) )               define( 'MY_YOUTUBE_VIDEOS_VERSION', $this->version );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_WP_VERSION_REQUIRE' ) )    define( 'MY_YOUTUBE_VIDEOS_WP_VERSION_REQUIRE', $this->wp_version_min );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_PAGE' ) )                  define( 'MY_YOUTUBE_VIDEOS_PAGE', str_replace('_', '-', $this->plugin_slug) );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_SCREEN_ID' ) )             define( 'MY_YOUTUBE_VIDEOS_SCREEN_ID', strtolower( str_replace( ' ', '-', MY_YOUTUBE_VIDEOS_PAGE ) ) );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_SLUG' ) )                  define( 'MY_YOUTUBE_VIDEOS_SLUG', $this->plugin_slug );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_TEXT_DOMAIN' ) )           define( 'MY_YOUTUBE_VIDEOS_TEXT_DOMAIN', $this->text_domain );
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_DEFAULT_SETTINGS_TAB' ) )  define( 'MY_YOUTUBE_VIDEOS_DEFAULT_SETTINGS_TAB', 'general');
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI' ) )       define( 'MY_YOUTUBE_VIDEOS_GITHUB_REPO_URI', $this->github_repo_url );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		if ( ! defined( 'MY_YOUTUBE_VIDEOS_SCRIPT_MODE' ) )           define( 'MY_YOUTUBE_VIDEOS_SCRIPT_MODE', $suffix );
	} // END define_constants()

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @since  2.0.0
	 * @access private
	 * @global string $wp_version
	 * @return bool
	 */
	private function check_requirements() {
		global $wp_version;

		if ( ! version_compare( $wp_version, MY_YOUTUBE_VIDEOS_WP_VERSION_REQUIRE, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'display_req_notice' ) );
			return false;
		}

		return true;
	} // END check_requirements()

	/**
	 * Display the requirement notice.
	 *
	 * @since 1.0.0
	 * @access static
	 */
	static function display_req_notice() {
		echo '<div id="message" class="error"><p><strong>';
		echo sprintf( __('Sorry, %s requires WordPress ' . MY_YOUTUBE_VIDEOS_WP_VERSION_REQUIRE . ' or higher. Please upgrade your WordPress setup', 'my-youtube-videos'), MY_YOUTUBE_VIDEOS );
		echo '</strong></p></div>';
	} // END display_req_notice()

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		include_once( 'includes/my-youtube-videos-core-functions.php' ); // Contains core functions for the front/back end.

		// Include Widgets
		$this->include_widgets();

		if ( is_admin() ) {
			$this->admin_includes();
		}

		if ( ! is_admin() || defined('DOING_AJAX') ) {
			$this->frontend_includes();
		}
	} // END includes()

	/**
	 * Include required admin files.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'includes/admin/class-my-youtube-videos-install.php' ); // Install plugin
		include_once( 'includes/admin/class-my-youtube-videos-admin.php' );   // Admin section
	} // END admin_includes()

	/**
	 * Include required frontend files.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
		// Shortcodes class
		include_once( 'includes/classes/class-my-youtube-videos-shortcodes.php' );
		include_once( 'includes/classes/shortcodes/my-youtube-videos-shortcode-display.php' );

		add_action( 'init', array( 'My_YouTube_Videos_Shortcodes', 'init' ) );
	} // END frontend_includes()

	/**
	 * Include widgets.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function include_widgets() {
		include_once( 'includes/my-youtube-videos-widgets.php' ); // Includes the widgets listed and registers each one.
	} // END include_widgets()

	/**
	 * Runs when the plugin is initialized.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init_my_youtube_videos() {
		// Set up localisation
		$this->load_plugin_textdomain();

		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();
	} // END init_my_youtube_videos()

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any
	 * following ones if the same translation is present.
	 *
	 * @since  2.0.0
	 * @access public
	 * @filter my_youtube_videos_languages_directory
	 * @filter plugin_locale
	 * @return void
	 */
	public function load_plugin_textdomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( MY_YOUTUBE_VIDEOS_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'my_youtube_videos_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale',  get_locale(), $this->text_domain );
		$mofile = sprintf( '%1$s-%2$s.mo', $this->text_domain, $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->text_domain . '/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/my-youtube-videos/ folder
			load_textdomain( $this->text_domain, $mofile_global );
		}
		else if ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/my-youtube-videos/languages/ folder
			load_textdomain( $this->text_domain, $mofile_local );
		}
		else {
			// Load the default language files
			load_plugin_textdomain( $this->text_domain, false, $lang_dir );
		}
	} // END load_plugin_textdomain()

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	} // END plugin_url()

	/**
	 * Get the plugin path.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	} // END plugin_path()

	/**
	 * Registers and enqueues stylesheets and javascripts
	 * for the administration panel and the front of the site.
	 *
	 * @since  2.0.0
	 * @access private
	 * @filter my_youtube_videos_admin_params
	 * @filter my_youtube_videos_params
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			// Main Plugin Javascript
			$this->load_file( $this->plugin_slug . '_admin_script', '/assets/js/admin/my-youtube-videos' . MY_YOUTUBE_VIDEOS_SCRIPT_MODE . '.js', true, array('jquery', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip'), $this->version );

			// TipTip
			$this->load_file( 'jquery-tiptip', '/assets/js/jquery-tiptip/jquery.tipTip' . MY_YOUTUBE_VIDEOS_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Chosen
			$this->load_file( 'ajax-chosen', '/assets/js/chosen/ajax-chosen.jquery' . MY_YOUTUBE_VIDEOS_SCRIPT_MODE . '.js', true, array('jquery', 'chosen'), $this->version );
			$this->load_file( 'chosen', '/assets/js/chosen/chosen.jquery' . MY_YOUTUBE_VIDEOS_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Chosen RTL
			if ( is_rtl() ) {
				$this->load_file( 'chosen-rtl', '/assets/js/chosen/chosen-rtl' . MY_YOUTUBE_VIDEOS_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			}

			// Variables for Admin JavaScripts
			wp_localize_script( $this->plugin_slug . '_admin_script', 'my_youtube_videos_admin_params', apply_filters( 'my_youtube_videos_admin_params', array(
				'plugin_url'         => $this->plugin_url(),
				'i18n_nav_warning'   => __( 'The changes you made will be lost if you navigate away from this page.', 'my-youtube-videos' ),
			) ) );

			// Stylesheets
			$this->load_file( $this->plugin_slug . '_admin_style', '/assets/css/admin/my-youtube-videos.css' );
		} // END if is_admin()
	} // END register_scripts_and_styles()

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @since  2.0.0
	 * @access private
	 * @param  string  $name      The ID to register with WordPress.
	 * @param  string  $file_path The path to the actual file.
	 * @param  bool    $is_script Optional, argument for if the incoming file_path is a JavaScript source file.
	 * @param  array   $support   Optional, for requiring other javascripts for the source file you are calling.
	 * @param  string  $version   Optional, can match the version of the plugin or version of the source file.
	 * @global string  $wp_version
	 */
	private function load_file( $name, $file_path, $is_script = false, $support = array(), $version = '' ) {
		global $wp_version;

		$url  = $this->plugin_url() . $file_path;
		$file = $this->plugin_path() . $file_path;

		if ( file_exists( $file ) ) {
			if ( $is_script ) {
				wp_register_script( $name, $url, $support, $version );
				wp_enqueue_script( $name );
			}
			else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // END load_file()
} // END My_YouTube_Videos()

} // END class_exists('My_YouTube_Videos')

/**
 * Returns the instance of My_YouTube_Videos to prevent the need to use globals.
 *
 * @since  2.0.0
 * @return My YouTube Videos
 */
function My_YouTube_Videos() {
	return My_YouTube_Videos::instance();
}

// Global for backwards compatibility.
$GLOBALS["my_youtube_videos"] = My_YouTube_Videos();
?>
