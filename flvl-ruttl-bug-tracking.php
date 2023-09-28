<?php
/*
 * Plugin Name:       Ruttl Bug Tracking
 * Description:       Add the ruttl bug tracking script to any part of your website.
 * Version:           1.0.1
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Floris van Leeuwen
 * Author URI:        https://flvl.nl/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       flvl-ruttl-bug tracking
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

const FLVL_RUTTL_BUGTRACKING_VERSION     = '1.0.1';
const FLVL_RUTTL_BUGTRACKING_PLUGIN_FILE = __FILE__;

/*
 * 1. Include the settings configuration.
 */
require_once __DIR__ . '/includes/settings.php';

/**
 * Add a link to the settings page to the plugin's entry in the plugins list.
 *
 * @param array $links The links to be displayed.
 *
 * @return array The links to be displayed.
 *
 * @since 1.0.0
 */
function flvl_ruttl_bug_tracking_add_settings_link( array $links ): array {
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		Flvl_Ruttl_Bug_Tracking_Settings::get_settings_url(),
		__( 'Settings', 'flvl-ruttl-bug-tracking' )
	);

	$links[] = $settings_link;

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( FLVL_RUTTL_BUGTRACKING_PLUGIN_FILE ), 'flvl_ruttl_bug_tracking_add_settings_link', 10, 1 );

/**
 * Show an admin notice if the Project ID is not set.
 *
 * @return void
 */
function flvl_ruttl_bug_tracking_show_admin_notice(): void {
	// Do not show on the settings page.
	if ( isset( $_GET['page'] ) && Flvl_Ruttl_Bug_Tracking_Settings::PAGE_SLUG === $_GET['page'] ) {
		return;
	}

	$settings_url = Flvl_Ruttl_Bug_Tracking_Settings::get_settings_url();

	echo sprintf(
		'<div class="notice notice-error"><p>%s</p></div>',
		sprintf(
			__( 'Please set the Ruttl Bug Tracking Project ID on the <a href="%s">settings page</a>.', 'flvl-ruttl-bug-tracking' ),
			$settings_url
		)
	);
}

/*
 * 2. Check if the Project ID is set. If not, show an admin notice and stop.
 */
if ( empty( Flvl_Ruttl_Bug_Tracking_Settings::get_setting( 'project_id' ) ) ) {
	add_action( 'admin_notices', 'flvl_ruttl_bug_tracking_show_admin_notice' );

	return;
}

/*
 * 3. Include the script.
 */
add_action( 'wp_footer', 'flvl_ruttl_bug_tracking_script', 50 );
add_action( 'admin_footer', 'flvl_ruttl_bug_tracking_script', 50 );

/**
 * Add the Ruttl Bug Tracking script to the footer.
 *
 * @return void
 *
 * @since 1.0.0
 */
function flvl_ruttl_bug_tracking_script(): void {
	$include_ruttl = ! is_admin() &&
	                 ( ! Flvl_Ruttl_Bug_Tracking_Settings::get_setting( 'include_only_logged_in' ) || is_user_logged_in() );

	/**
	 * Filter to conditionally include or exclude Ruttl bug tracking.
	 *
	 * This filter allows developers to programmatically control whether to include
	 * Ruttl bug tracking. By default, Ruttl bug tracking is not included in the admin area.
	 *
	 * @param bool $include_ruttl Whether to include Ruttl bug tracking. Default value is `! is_admin()`.
	 *
	 * @return bool  Modified value indicating whether to include Ruttl bug tracking.
	 *
	 * @since 1.0.0
	 */
	$include_ruttl = apply_filters( 'flvl_ruttl_bug_tracking/include_ruttl', $include_ruttl );

	if ( ! $include_ruttl ) {
		return;
	}

	$project_id = Flvl_Ruttl_Bug_Tracking_Settings::get_setting( 'project_id' );

	echo sprintf(
		'<script id="ruttl-site-embed-script" src="https://app.ruttl.com/plugin.js?id=%s&e=1" defer async></script>',
		$project_id
	);
}