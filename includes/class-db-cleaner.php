<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/includes
 * @author     Utkarsh<iamutkarsh@live.com>
 */
class Db_Cleaner {


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		//$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Db_Cleaner_Loader. Orchestrates the hooks of the plugin.
	 * - Db_Cleaner_i18n. Defines internationalization functionality.
	 * - Db_Cleaner_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-db-cleaner-i18N.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-db-cleaner-admin.php';

		// include classes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-wp-orphan-data.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-wp-duplicate-data.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-wp-db-cleaner-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/helper.php';

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once( plugin_dir_path( __FILE__ ) . '../admin/wp-cli/class-wp-cleaner-cli.php' );
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Db_Cleaner_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Db_Cleaner_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Db_Cleaner_Admin();

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );

	}

}
