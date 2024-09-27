<?php

/**
 * Plugin Name:       Ken Burn Posts Widget
 * Description:       Ken Burn Posts Widget is created by Zain Hassan.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Zain Hassan
 * Author URI:        https://hassanzain.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hz-widgets
*/

if(!defined('ABSPATH')){
    exit;
}



function kpbw_el_category( $elements_manager ) {

	$elements_manager->add_category(
		'kpbw-category',
		[
			'title' => esc_html__( 'Custom Widgets', 'hz-widgets' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'kpbw_el_category' );


/**
 * Register Ken Burn Posts Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_kpbw_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/posts-widget.php' );

	$widgets_manager->register( new \Elementor_kpbw_Widget() );

}
add_action( 'elementor/widgets/register', 'register_kpbw_widget' );


function kpbw_register_dependencies_scripts() {

	/* Styles */
	wp_register_style( 'kenburn-posts-widget', plugins_url( 'assets/css/kenburn-posts-widget.css', __FILE__ ));

	/* Scripts */
	wp_register_script( 'kenburn-posts-widget', plugins_url( 'assets/js/kenburn-posts-widget.js', __FILE__ ));

}
add_action( 'wp_enqueue_scripts', 'kpbw_register_dependencies_scripts' );

