<?php
/**
 * Plugin Name: LookBook for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-lookbook/
 * Description: Allows you to create realistic lookbooks of your products. Help your customers visualize what they purchase from you.
 * Version: 1.1.5
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-lookbook
 * Domain Path: /languages
 * Copyright 2018-2024 VillaTheme.com. All rights reserved.
 * Requires Plugins: woocommerce
 * Requires at least: 5.0
 * Tested up to: 6.5
 * WC requires at least: 7.0
 * WC tested up to: 8.8
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WOO_F_LOOKBOOK_VERSION', '1.1.5' );
define( 'WOO_F_LOOKBOOK_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
/**
 * Detect plugin. For use on Front End only.
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-lookbook/woocommerce-lookbook.php' ) ) {
	return;
}

/**
 * Class WOO_LOOKBOOK
 */
class WOO_F_LOOKBOOK {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Compatible with High-Performance order storage (COT)
		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );

	}

	public function init() {
		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lookbook" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "support.php";
		}

		$environment = new VillaTheme_Require_Environment( [
				'plugin_name'     => 'LookBook for WooCommerce',
				'php_version'     => '7.0',
				'wp_version'      => '5.0',
				'wc_version'      => '5.0',
				'require_plugins' => [
					[
						'slug' => 'woocommerce',
						'name' => 'WooCommerce',
					],
				]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lookbook" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
		require_once $init_file;

		add_image_size( 'lookbook', 400, 400, false );
	}

	public function before_woocommerce_init() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}

new WOO_F_LOOKBOOK();