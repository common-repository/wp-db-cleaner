<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Db_Cleaner
 * @subpackage Db_Cleaner/admin
 * @author     Utkarsh <iamutkarsh@live.com>
 */
if ( ! class_exists( 'Db_Cleaner_Admin' ) ) {


	class Db_Cleaner_Admin {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->init();
			add_action( 'admin_menu', array( $this, 'add_tool_page' ) );
		}

		/**
		 * Add tools admin menu
		 */
		public function add_tool_page() {
			add_management_page( 'WP DB Clean', 'WP DB Clean', 'manage_options', 'db-clean', array(
				$this,
				'get_clean_ui'
			) );
		}

		/**
		 * Load template file for function
		 */
		public function get_clean_ui() {
			__dbc_delete_data();
			require_once( plugin_dir_path( __FILE__ ) . '/template/db-cleaner-page.php' );
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Db_Cleaner_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Db_Cleaner_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_style( 'wp-db-clean', plugin_dir_url( __FILE__ ) . 'css/db-cleaner.css', array(), WP_DB_CLEAN_VERSION, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Db_Cleaner_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Db_Cleaner_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_script( 'wp-db-clean', plugin_dir_url( __FILE__ ) . 'js/db-cleaner.js', array( 'jquery' ), WP_DB_CLEAN_VERSION, false );

		}

		private function init() {
			global $orphan_data, $duplicate_data;
			$orphan_data    = new Wp_Orphan_Data();
			$duplicate_data = new Wp_Duplicate_Data();
		}

	}

}
