WooCommerce API Manager Drop-In Licensing for WordPress Plugins
===============================================================

This drop-in enables easy integration with [WooCommerce API
Manager](https://woocommerce.com/products/woocommerce-api-manager/) for
licensing and update checking in your WordPress plugins. It includes dynamic
setup, optional feature gating, and flexible admin menu placement.

📂 File Structure
----------------

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
inc/
└── updater/
    ├── wc-am-client.php        ← Official API Manager client class
    └── wc-api-license.php      ← Drop-in for license screen + checks
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

✅ How to Use
------------

### 1. Include the Drop-In

In your main plugin file (e.g., `plugin.php`), define your plugin-specific
constants and include the drop-in:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ php
// Required product info
define( 'RUP_LICENSE_PRODUCT_ID', 64 ); // Replace with your product ID
define( 'RUP_LICENSE_API_URL', 'https://yourstore.com' ); // Your WooCommerce store (no trailing slash)

// 🔽 Uncomment ONE of the menu blocks below:

// 1️⃣ Default: Settings → License Key
// define( 'RUP_LICENSE_MENU_TYPE', 'add_options_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License Key' );
// define( 'RUP_LICENSE_MENU_SLUG', 'license-key' );
// define( 'RUP_LICENSE_MENU_PARENT', '' );

// 2️⃣ Custom: Settings → Awesome Plugin Key
// define( 'RUP_LICENSE_MENU_TYPE', 'add_options_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'Awesome Plugin Key' );
// define( 'RUP_LICENSE_MENU_SLUG', 'awesome-plugin-key' );
// define( 'RUP_LICENSE_MENU_PARENT', '' );

// 3️⃣ Top-Level Admin Menu
// define( 'RUP_LICENSE_MENU_TYPE', 'add_menu_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License' );
// define( 'RUP_LICENSE_MENU_SLUG', 'top-level-license' );

// 4️⃣ Submenu under your plugin menu
// define( 'RUP_LICENSE_MENU_TYPE', 'add_submenu_page' );
// define( 'RUP_LICENSE_MENU_TITLE', 'License' );
// define( 'RUP_LICENSE_MENU_SLUG', 'awesome-plugin-license' );
// define( 'RUP_LICENSE_MENU_PARENT', 'awesome-plugin' );

// Include the drop-in file
require_once plugin_dir_path( __FILE__ ) . 'inc/updater/wc-api-license.php';
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

🔐 License Gating (Optional)
---------------------------

To restrict plugin functionality unless the license is active, the drop-in
checks activation status using:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ php
$wcam_lib->get_api_key_status(); // false = offline check, true = live
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can safely gate premium features using this.

🧩 Why This is Useful
--------------------

-   ✅ Easy copy-paste setup

-   ✅ Constants-based config = DRY and portable

-   ✅ Compatible with any plugin structure

-   ✅ Maintains update logic separately

📜 License
---------

This integration assumes usage under the terms of the WooCommerce API Manager
and your plugin’s license (GPL-2.0+ or compatible).
