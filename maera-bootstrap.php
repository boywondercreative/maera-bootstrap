<?php
/*
Plugin Name:         Maera Bootstrap Shell
Plugin URI:
Description:         Add the bootstrap shell to the Maera theme
Version:             0.9.1
Author:              Aristeides Stathopoulos, Dimitris Kalliris
Author URI:          https://press.codes
Text Domain:         maera_bs
*/

define( 'MAERA_BS_SHELL_VER', '0.9.1' );
define( 'MAERA_BS_SHELL_URL', plugins_url( '', __FILE__ ) );
define( 'MAERA_BS_SHELL_PATH', dirname( __FILE__ ) );

/**
 * Include the shell
 */
function maera_shell_bootstrap_include( $shells ) {

	// Add our shell to the array of available shells
	$shells[] = array(
		'value' => 'bootstrap',
		'label' => 'Bootstrap',
		'class' => 'Maera_BS',
	);

	return $shells;

}
add_filter( 'maera/shells/available', 'maera_shell_bootstrap_include' );

/**
 * Plugin textdomains
 */
function maera_bootstrap_texdomain() {
	$lang_dir    = get_template_directory() . '/languages';
	$custom_path = WP_LANG_DIR . '/maera-' . get_locale() . '.mo';

	if ( file_exists( $custom_path ) ) {
		load_textdomain( 'maera_bs', $custom_path );
	} else {
		load_plugin_textdomain( 'maera_bs', false, $lang_dir );
	}
}
add_action( 'plugins_loaded', 'maera_bootstrap_texdomain' );

if ( ! class_exists( 'Maera_BS' ) ) {

	/**
	* The Bootstrap Shell module
	*/
	class Maera_BS {

		private static $instance;

		/**
		 * Class constructor
		 */
		public function __construct() {

			if ( ! defined( 'MAERA_SHELL_PATH' ) ) {
				define( 'MAERA_SHELL_PATH', dirname( __FILE__ ) );
			}

			$this->required_plugins();

			add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );

			// Include other classes
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-widget-dropdown.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-widgets.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-styles.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-structure.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-compiler.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-images.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-excerpt.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-scripts.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-meta.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-layout.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-navbar.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-social.php' );
			include_once( MAERA_SHELL_PATH . '/includes/variables.php' );
			include_once( MAERA_SHELL_PATH . '/includes/class-maera-bs-timber.php' );

			// Include the customizer
			include_once( MAERA_SHELL_PATH . '/customizer.php' );

			// Instantianate addon classes
			$bs_structure = new Maera_BS_Structure();
			$bs_widgets   = new Maera_BS_Widgets();
			$bs_styles    = new Maera_BS_Styles();
			$bs_compiler  = new Maera_BS_Compiler();
			$bs_images    = new Maera_BS_Images();
			$bs_excerpt   = new Maera_BS_Excerpt();
			$bs_timber    = new Maera_BS_Timber();
			$bs_scripts   = new Maera_BS_Scripts();
			$bs_layout    = new Maera_BS_Layout();
			$bs_navbar    = new Maera_BS_Navbar();
			$bs_social    = new Maera_BS_Social();

		}


		/**
		 * Singleton
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}


		/**
		 * Add theme supports
		 */
		function theme_supports() {

			add_theme_support( 'kirki' );
			add_theme_support( 'maera_image' );
			add_theme_support( 'maera_color' );
			add_theme_support( 'less_compiler' );

		}


		/**
		* Build the array of required plugins.
		* You can use the 'maera/required_plugins' filter to add or remove plugins.
		*/
		function required_plugins() {

			$plugins[] = array(
				'name' => 'Breadcrumb Trail',
				'file' => 'breadcrumb-trail.php',
				'slug' => 'breadcrumb-trail'
			);
			$plugins[] = array(
				'name' => 'Less & scss compilers',
				'file' => 'less-plugin.php',
				'slug' => 'lessphp'
			);

			$plugins = new Maera_Required_Plugins( $plugins );

		}

	}

}

/**
* Licensing handler
*/
function maera_bs_licensing() {

	if ( is_admin() && class_exists( 'Maera_Updater' ) ) {
		$maera_md_license = new Maera_Updater( 'plugin', __FILE__, 'Maera Bootstrap Shell', MAERA_BS_SHELL_VER );
	}

}
add_action( 'init', 'maera_bs_licensing' );
