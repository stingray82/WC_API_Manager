<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce API Manager Drop-In for Plugin Licensing
 *
 * This file:
 * - Loads the WC_AM_Client class
 * - Automatically adds the License Activation UI (default under Settings)
 * - Optionally gates plugin features until license is active
 *
 * REQUIREMENTS:
 * - Place `wc-am-client.php` in `inc/updater/`
 * - Update `_product_id` and `_api_url` below
 */

// Load the WC_AM client class if not already loaded.
if ( ! class_exists( 'WC_AM_Client_2_10_0' ) ) {
	//$lib_path = plugin_dir_path( __FILE__ ) . 'inc/updater/wc-am-client.php';
	$lib_path = plugin_dir_path(__FILE__) . 'wc-am-client.php';

	if ( file_exists( $lib_path ) ) {
		require_once $lib_path;
	} else {
		// Log or display error if file missing
		error_log( 'WC_AM client not found at: ' . $lib_path );
	}
}


// Load plugin header data
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$plugin_data = get_plugin_data( __FILE__ );

// Define dynamic constants using plugin TextDomain as prefix
$prefix = 'rup_' . $plugin_data['TextDomain'];

$constants = array(
	'_version'         => $plugin_data['Version'],
	'_slug'            => $plugin_data['TextDomain'],
	'_main_file'       => __FILE__,
	'_dir'             => plugin_dir_path( __FILE__ ),
	'_url'             => plugin_dir_url( __FILE__ ),

	// 🔧 REQUIRED: Customize for your plugin/store
	'_product_id'      => PRODUCT ID,
	'_api_url'         => 'Your SITE HERE', // No trailing slash

	// Automatically pulled from plugin header
	'_software_title'  => $plugin_data['Name'],
	'_textdomain'      => $plugin_data['TextDomain'],

	// Optional
	'_hide_notice'     => false,
);

foreach ( $constants as $suffix => $value ) {
	if ( ! defined( $prefix . $suffix ) ) {
		define( $prefix . $suffix, $value );
	}
}

// Run license logic only after WordPress loads properly
add_action( 'init', function () use ( $prefix ) {
	global $wcam_lib;

	// Optionally: force hashed instance ID (usually handled by WC_AM internally)
	//$instance = md5( site_url() );

	/**
	 * MENU CONFIGURATION
	 * To move the license screen:
	 *
	 * - 'add_options_page' → under Settings (default)
	 * - 'add_menu_page' → top-level menu
	 * - 'add_submenu_page' → under another plugin
	 *
	 * Example:
	 * $custom_menu = array(
	 *     'menu_type'   => 'add_menu_page',
	 *     'page_title'  => __( 'Activate License', constant( $prefix . '_textdomain' ) ),
	 *     'menu_title'  => __( 'License Key', constant( $prefix . '_textdomain' ) ),
	 *     'capability'  => 'manage_options',
	 *     'menu_slug'   => 'plugin-license',
	 *     'icon_url'    => 'dashicons-admin-network', // optional
	 *     'position'    => 65, // optional
	 * );
	 */

	$wcam_lib = new WC_AM_Client_2_10_0(
		constant( $prefix . '_main_file' ),
		constant( $prefix . '_product_id' ),
		null,
		constant( $prefix . '_version' ),
		'plugin',
		constant( $prefix . '_api_url' ),
		constant( $prefix . '_software_title' ),
		constant( $prefix . '_textdomain' ),
		// Optional:
		null, // ← $custom_menu (default: Settings → API Key)
		constant( $prefix . '_hide_notice' ),
		//$instance // Ensures valid unique ID
	);

	/**
	 * GATE PLUGIN FEATURES UNTIL LICENSE IS ACTIVE
	 */
	if ( is_object( $wcam_lib ) && $wcam_lib->get_api_key_status() ) {
		//  Load main plugin logic
		//require_once constant( $prefix . '_dir' ) . 'inc/main-plugin-code.php';
	} else {
		//  License not active → Optionally restrict plugin
		// add_action( 'admin_notices', 'your_custom_activation_notice' );
	}
});

if ( ! class_exists( 'WC_AM_License_Helper' ) ) {
	class WC_AM_License_Helper {
		public static function is_active() {
			global $wcam_lib;
			return is_object( $wcam_lib ) && $wcam_lib->get_api_key_status();
		}
	}
}
