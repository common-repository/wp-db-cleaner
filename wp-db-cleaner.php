<?php

/**
 * @link              https://wordpress.org/plugins/wp-db-cleaner
 * @since             1.0
 * @package           Db_Cleaner
 *
 * @wordpress-plugin
 * Plugin Name:       WP DB Cleaner
 * Plugin URI:        https://wordpress.org/plugins/wp-db-cleaner
 * Description:       Clean WordPress orphan and duplicate data from database
 * Version:           2.0.1
 * Author:            littlemonks
 * Author URI:        https://github.com/mehulkaklotar/wp-db-cleaner
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-db-cleaner
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WP_DB_CLEAN_VERSION' ) ) {
	define( 'WP_DB_CLEAN_VERSION', '2.0.1' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-db-cleaner-activator.php
 */
function wp_db_clean_activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-db-cleaner-activator.php';
	Db_Cleaner_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-db-cleaner-deactivator.php
 */
function wp_db_clean_deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-db-cleaner-deactivator.php';
	Db_Cleaner_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wp_db_clean_activate_plugin_name' );
register_deactivation_hook( __FILE__, 'wp_db_clean_deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-db-cleaner.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_db_cleaner() {
	$plugin = new Db_Cleaner();
}

run_wp_db_cleaner();
