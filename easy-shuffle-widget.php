<?php
/**
 * Easy Shuffle Widget
 *
 * @package Easy_Shuffle_Widget
 *
 * @license     http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @version     1.0
 *
 * Plugin Name: Easy Shuffle Widget
 * Description: Easily display random content, comments, or users in your sidebars.
 * Version:     1.0
 * Text Domain: easy-shuffle-widget
 * Domain Path: /lang
 * License:     GPL-2.0+
 */


// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


define( 'EASY_SHUFFLE_WIDGET_FILE', __FILE__ );


/**
 * Instantiates the main Widget instance
 *
 * @since 1.0.0
 */
function _easy_shuffle_widget_init() {

	include dirname( __FILE__ ) . '/inc/class-easy-shuffle-widget-utils.php';
	include dirname( __FILE__ ) . '/inc/class-easy-shuffle-widget-fields.php';
	include dirname( __FILE__ ) . '/inc/class-widget-easy-shuffle.php';
	include dirname( __FILE__ ) . '/inc/class-easy-shuffle-widget-views.php';
	include dirname( __FILE__ ) . '/inc/class-easy-shuffle-widget-init.php';

	$Easy_Shuffle_Widget_Init = new Easy_Shuffle_Widget_Init( __FILE__ );
	$Easy_Shuffle_Widget_Init->init();

}
add_action( 'plugins_loaded', '_easy_shuffle_widget_init', 99 );