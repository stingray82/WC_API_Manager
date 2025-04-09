<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce API Manager Drop-In for Plugin Licensing
 */

// Fix: Redirect legacy WC_AM tabs to correct page if using custom menu slug
add_action( 'plugins_loaded', function () {
    if ( ! defined( 'RUP_LICENSE_PRODUCT_ID' ) ) return;

    $main_file = defined( 'RUP_LICENSE_MAIN_FILE' ) ? RUP_LICENSE_MAIN_FILE : __FILE__;
    if ( ! function_exists( 'get_plugin_data' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugin_data = get_plugin_data( $main_file );
    $prefix = 'rup_' . $plugin_data['TextDomain'];

    $product_id    = RUP_LICENSE_PRODUCT_ID;
    $expected_slug = defined( 'RUP_LICENSE_MENU_SLUG' )
        ? RUP_LICENSE_MENU_SLUG
        : constant( $prefix . '_slug' ) . '-license';

    $legacy_tabs = array(
        'wc_am_client_' . $product_id . '_dashboard',
        'wc_am_client_' . $product_id . '_deactivation',
    );

    if (
        isset( $_GET['page'], $_GET['tab'] ) &&
        in_array( $_GET['tab'], $legacy_tabs, true ) &&
        $_GET['page'] !== $expected_slug
    ) {
        $base = admin_url( 'admin.php' );
        if ( defined( 'RUP_LICENSE_MENU_TYPE' ) && RUP_LICENSE_MENU_TYPE === 'add_options_page' ) {
            $base = admin_url( 'options-general.php' );
        }

        wp_safe_redirect( $base . '?page=' . $expected_slug . '&tab=' . sanitize_text_field( $_GET['tab'] ) );
        exit;
    }
}, 0); // Run before WCAM client loads

// Load the WC_AM client class if not already loaded.
if ( ! class_exists( 'WC_AM_Client_2_10_0' ) ) {
    $lib_path = __DIR__ . '/wc-am-client.php';

    if ( file_exists( $lib_path ) ) {
        require_once $lib_path;
    } else {
        error_log( 'WC_AM client not found at: ' . $lib_path );
    }
}

// Load plugin header data
if ( ! function_exists( 'get_plugin_data' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$main_file = defined( 'RUP_LICENSE_MAIN_FILE' ) ? RUP_LICENSE_MAIN_FILE : __FILE__;
$plugin_data = get_plugin_data( $main_file );

$prefix = 'rup_' . $plugin_data['TextDomain'];

$constants = array(
    '_version'         => $plugin_data['Version'],
    '_slug'            => $plugin_data['TextDomain'],
    '_main_file'       => $main_file,
    '_dir'             => plugin_dir_path( $main_file ),
    '_url'             => plugin_dir_url( $main_file ),
    '_software_title'  => $plugin_data['Name'],
    '_textdomain'      => $plugin_data['TextDomain'],
);

foreach ( $constants as $suffix => $value ) {
    if ( ! defined( $prefix . $suffix ) ) {
        define( $prefix . $suffix, $value );
    }
}

add_action( 'init', function () use ( $prefix ) {
    global $wcam_lib;

    if ( ! defined( 'RUP_LICENSE_PRODUCT_ID' ) || ! defined( 'RUP_LICENSE_API_URL' ) ) {
        error_log( 'License config missing: RUP_LICENSE_PRODUCT_ID and RUP_LICENSE_API_URL are required.' );
        return;
    }

    $custom_menu = null;

    if ( defined( 'RUP_LICENSE_MENU_TYPE' ) ) {
        $custom_menu = array(
            'menu_type'   => RUP_LICENSE_MENU_TYPE,
            'parent_slug' => defined( 'RUP_LICENSE_MENU_PARENT' ) ? RUP_LICENSE_MENU_PARENT : '',
            'page_title'  => __( 'License Activation', constant( $prefix . '_textdomain' ) ),
            'menu_title'  => defined( 'RUP_LICENSE_MENU_TITLE' ) ? RUP_LICENSE_MENU_TITLE : __( 'API Key', constant( $prefix . '_textdomain' ) ),
            'capability'  => 'manage_options',
            'menu_slug'   => defined( 'RUP_LICENSE_MENU_SLUG' ) ? RUP_LICENSE_MENU_SLUG : constant( $prefix . '_slug' ) . '-license',
        );
    }

    $wcam_lib = new WC_AM_Client_2_10_0(
        constant( $prefix . '_main_file' ),
        RUP_LICENSE_PRODUCT_ID,
        null,
        constant( $prefix . '_version' ),
        'plugin',
        RUP_LICENSE_API_URL,
        constant( $prefix . '_software_title' ),
        constant( $prefix . '_textdomain' ),
        $custom_menu,
        defined( 'RUP_LICENSE_HIDE_NOTICE' ) ? RUP_LICENSE_HIDE_NOTICE : false
    );

    if ( is_object( $wcam_lib ) && $wcam_lib->get_api_key_status() ) {
        // License is active
        // require_once constant( $prefix . '_dir' ) . 'inc/main-plugin-code.php';
    } else {
        // License is not active
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