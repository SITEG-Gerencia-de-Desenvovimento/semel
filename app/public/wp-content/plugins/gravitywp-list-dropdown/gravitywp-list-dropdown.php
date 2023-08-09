<?php
/**
 * Plugin Name: GravityWP - List Dropdown
 * Description: Gives the option of adding a drop down (select) list to a list field column
 * Version: 2.0
 * Requires PHP: 7.0
 * Author: GravityWP
 * Author URI: https://gravitywp.com
 * License: GPL2
 * Text Domain: gravitywp-list-dropdown
 * Domain Path: /languages
 * Credits: Adrian Gordon for the initial List Dropdown plugin.
 */

defined( 'ABSPATH' ) || die();

define( 'GWP_LIST_DROPDOWN_VERSION', '2.0' );

// If Gravity Forms is not active, show admin notice.
add_action( 'plugins_loaded', array( 'GWP_List_Dropdown_Bootstrap', 'check_gravityforms_is_installed' ) );

// When Gravity Forms is loaded, load GravityWP List Dropdown Add-On.
add_action( 'gform_loaded', array( 'GWP_List_Dropdown_Bootstrap', 'load' ), 5 );

class GWP_List_Dropdown_Bootstrap {

	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		// Autoloader.
		require_once __DIR__ . '/lib/autoload.php';

		require_once 'class-gravitywp-list-dropdown.php';
		GFAddOn::register( 'GravityWP\List_Dropdown\GravityWPListDropDown' );
	}

	public static function check_gravityforms_is_installed() {
		if ( ! class_exists( 'GFForms' ) ) {
			add_action( 'admin_notices', array( 'GWP_List_Dropdown_Bootstrap', 'gravityforms_not_active_notice' ) );
			return;
		}
	}

	public static function gravityforms_not_active_notice() {
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'GravityWP List Dropdown requires Gravity Forms to be installed and active. You can download %s here.', 'gravitywp-list-dropdown' ), '<a href="https://gravityforms.com" target="_blank">Gravity Forms</a>' ) . '</strong></p></div>';
	}
}