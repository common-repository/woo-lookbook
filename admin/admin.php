<?php

/*
Class Name: WOO_F_LOOKBOOK_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Admin_Admin {
	protected $settings;
	protected $suffix = WP_DEBUG ? '' : '.min';

	function __construct() {
		$this->settings = new WOO_F_LOOKBOOK_Data();
		add_filter(
			'plugin_action_links_woo-lookbook/woo-lookbook.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
	}


	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		$screen_id = get_current_screen()->id;
		if ( 'woocommerce-lookbook_page_woocommerce-lookbook-settings' == $screen_id ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					wp_dequeue_script( $script->handle );
				}
			}

			/*Stylesheet*/
			wp_enqueue_style( 'woo-lookbook-button', WOO_F_LOOKBOOK_CSS . 'button.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-table', WOO_F_LOOKBOOK_CSS . 'table.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-transition', WOO_F_LOOKBOOK_CSS . 'transition.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-form', WOO_F_LOOKBOOK_CSS . 'form.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-icon', WOO_F_LOOKBOOK_CSS . 'icon.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-dropdown', WOO_F_LOOKBOOK_CSS . 'dropdown.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-checkbox', WOO_F_LOOKBOOK_CSS . 'checkbox.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-segment', WOO_F_LOOKBOOK_CSS . 'segment.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-menu', WOO_F_LOOKBOOK_CSS . 'menu.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook-tab', WOO_F_LOOKBOOK_CSS . 'tab.min.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'woo-lookbook', WOO_F_LOOKBOOK_CSS . 'woo-lookbook-admin.css', array(), WOO_F_LOOKBOOK_VERSION );
			wp_enqueue_style( 'select2', WOO_F_LOOKBOOK_CSS . 'select2.min.css', array(), WOO_F_LOOKBOOK_VERSION );

			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'woo-lookbook-transition', WOO_F_LOOKBOOK_JS . 'transition.min.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			wp_enqueue_script( 'woo-lookbook-dropdown', WOO_F_LOOKBOOK_JS . 'dropdown.min.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			wp_enqueue_script( 'woo-lookbook-checkbox', WOO_F_LOOKBOOK_JS . 'checkbox.min.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			wp_enqueue_script( 'woo-lookbook-tab', WOO_F_LOOKBOOK_JS . 'tab.min.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			wp_enqueue_script( 'woo-lookbook-address', WOO_F_LOOKBOOK_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'woo-lookbook', WOO_F_LOOKBOOK_JS . 'woo-lookbook-admin' . $this->suffix . '.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION, true );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), WOO_F_LOOKBOOK_VERSION, false
			);

		}


	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="edit.php?post_type=woocommerce-lookbook&page=woocommerce-lookbook-settings" title="' . esc_html__( 'Settings', 'woo-lookbook' ) . '">' . esc_html__( 'Settings', 'woo-lookbook' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Function init when run plugin+
	 */
	function init() {
		/*Register post type*/

		load_plugin_textdomain( 'woo-lookbook' );
		$this->load_plugin_textdomain();
		$this->register_post_type();


	}

	public function register_post_type() {
		$labels = array(
			'name'               => esc_html__( 'WC Lookbooks', 'woo-lookbook' ),
			'singular_name'      => esc_html__( 'WC Lookbook', 'woo-lookbook' ),
			'menu_name'          => esc_html__( 'WC Lookbooks', 'woo-lookbook' ),
			'name_admin_bar'     => esc_html__( 'WC Lookbook', 'woo-lookbook' ),
			'add_new'            => esc_html__( 'Add New', 'woo-lookbook' ),
			'add_new_item'       => esc_html__( 'Add New Lookbook', 'woo-lookbook' ),
			'new_item'           => esc_html__( 'New Lookbook', 'woo-lookbook' ),
			'edit_item'          => esc_html__( 'Edit Lookbook', 'woo-lookbook' ),
			'view_item'          => esc_html__( 'View Lookbook', 'woo-lookbook' ),
			'all_items'          => esc_html__( 'All Lookbooks', 'woo-lookbook' ),
			'search_items'       => esc_html__( 'Search Lookbooks', 'woo-lookbook' ),
			'parent_item_colon'  => esc_html__( 'Parent Lookbooks:', 'woo-lookbook' ),
			'not_found'          => esc_html__( 'No books found.', 'woo-lookbook' ),
			'not_found_in_trash' => esc_html__( 'No books found in Trash.', 'woo-lookbook' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'woo-lookbook' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'woocommerce-lookbook' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 2,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-location'
		);

		register_post_type( 'woocommerce-lookbook', $args );
	}

	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-lookbook' );
		// Global + Frontend Locale
		load_textdomain( 'woo-lookbook', WOO_F_LOOKBOOK_LANGUAGES . "woo-lookbook-$locale.mo" );
		load_plugin_textdomain( 'woo-lookbook', false, WOO_F_LOOKBOOK_LANGUAGES );
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_submenu_page(
			'edit.php?post_type=woocommerce-lookbook',
			esc_html__( 'LookBook for WooCommerce Setting page', 'woo-lookbook' ),
			esc_html__( 'Settings', 'woo-lookbook' ),
			'manage_options',
			'woocommerce-lookbook-settings',
			array( 'WOO_F_LOOKBOOK_Admin_Settings', 'page_callback' )
		);
	}

}

?>