// WooCommerce API Manager Licensing Setup
define( 'RUP_LICENSE_PRODUCT_ID', 64 ); // Required: Your WooCommerce Product ID
define( 'RUP_LICENSE_API_URL', 'https://yourstore.com' ); // Required: Root URL of your store (no trailing slash)

/**
 * License Page Menu Options
 * Uncomment ONE of the following blocks below to change where the license screen appears.
 */

/**
 * Default — Under Settings → "License Key"
 */
// define( 'RUP_LICENSE_MENU_TYPE', 'add_options_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License Key' );
// define( 'RUP_LICENSE_MENU_SLUG', 'license-key' );
// define( 'RUP_LICENSE_MENU_PARENT', '' ); // Not needed for options page

/**
 * Custom — Under Settings → "Awesome Plugin Key"
 */
// define( 'RUP_LICENSE_MENU_TYPE', 'add_options_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'Awesome Plugin Key' );
// define( 'RUP_LICENSE_MENU_SLUG', 'awesome-plugin-key' );
// define( 'RUP_LICENSE_MENU_PARENT', '' );

/**
 * Top-Level Menu → "License"
 */
// define( 'RUP_LICENSE_MENU_TYPE', 'add_menu_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License' );
// define( 'RUP_LICENSE_MENU_SLUG', 'top-level-license' );
// define( 'RUP_LICENSE_MENU_PARENT', '' ); // Not used in this type

/**
 * submenu under your plugin’s top-level menu (e.g., Awesome Plugin)
 */
// define( 'RUP_LICENSE_MENU_TYPE', 'add_submenu_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License' );
// define( 'RUP_LICENSE_MENU_SLUG', 'awesome-plugin-license' );
// define( 'RUP_LICENSE_MENU_PARENT', 'awesome-plugin' ); // <- Slug of your top-level menu

// oad the license logic drop-in
require_once plugin_dir_path( __FILE__ ) . 'inc/updater/wc-api-license.php';
