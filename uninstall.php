<?php
/**
 * Easy Shuffle Widget Uninstall actions
 *
 * Removes all options set by the plugin.
 *
 * @package Easy_Shuffle_Widget
 * @subpackage Admin
 *
 * @since 1.0
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// remove our options
delete_option( 'eshuflw_shown_items' );
delete_option( 'eshuflw_use_css' );