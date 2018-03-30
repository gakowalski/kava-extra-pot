<?php
/**
 * Plugin Name: Kava Extra
 * Plugin URI:  https://zemez.io/
 * Description: Kava Theme extra plugin
 * Version:     1.0.0
 * Author:      Zemez
 * Author URI:  https://zemez.io/
 * Text Domain: kava-extra
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Kava_Extra` doesn't exists yet.
if ( ! class_exists( 'Kava_Extra' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Kava_Extra {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.0.0';

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Load the CX Loader.
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Load the plugin modules.
			add_action( 'after_setup_theme', array( $this, 'framework_modules' ), 0 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			if ( ! $this->is_kava_theme() ) {
				//return false;
			}

			require $this->plugin_path( 'includes/assets.php' );
			require $this->plugin_path( 'includes/post-format.php' );

			kava_extra_assets()->init();
			kava_extra_post_format()->init();

			do_action( 'kava-extra/init', $this );

		}

		/**
		 * [kava_extra_framework_modules description]
		 *
		 * @return [type] [description]
		 */
		public function framework_modules() {

			require $this->plugin_path( 'includes/post-meta.php' );

			kava_extra_post_meta()->init();

			do_action( 'kava-extra/cx-framework-modules-init', $this );

		}

		/**
		 * Load the theme modules.
		 *
		 * @since  1.0.0
		 */
		public function framework_loader() {
			require $this->plugin_path( 'framework/loader.php' );

			new Kava_Extra_CX_Loader(
				array(
					$this->plugin_path( 'framework/modules/post-meta/cherry-x-post-meta.php' ),
					$this->plugin_path( 'framework/modules/interface-builder/cherry-x-interface-builder.php' ),
				)
			);
		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Kava Theme Check
		 *
		 * @return boolean
		 */
		public function is_kava_theme() {
			$theme = wp_get_theme();

			if ( 'KavaTheme' == $theme->name || 'kava' == $theme->template ) {
				return true;
			}

			return false;
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'kava-extra', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'kava-extra/template-path', 'kava-extra/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'kava_extra' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function kava_extra() {
		return Kava_Extra::get_instance();
	}
}

kava_extra();