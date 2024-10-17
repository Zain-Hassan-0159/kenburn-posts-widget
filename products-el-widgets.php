<?php

/**
 * Plugin Name:       Custom Products Widgets
 * Description:       Custom Products Widgets is created by Zain Hassan.
 * Version:           1.0.1
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



function cpw_el_category( $elements_manager ) {

	$elements_manager->add_category(
		'cpw-category',
		[
			'title' => esc_html__( 'Product Widgets', 'hz-widgets' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'cpw_el_category' );


/**
 * Register Custom Products Widgets.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_cpw_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/posts-widget.php' );

	$widgets_manager->register( new \Elementor_cpw_Widget() );

}
add_action( 'elementor/widgets/register', 'register_cpw_widget' );


function cpw_register_dependencies_scripts() {

	/* Styles */
	wp_register_style( 'posts-widget', plugins_url( 'assets/css/posts-widget.css', __FILE__ ));

}
add_action( 'wp_enqueue_scripts', 'cpw_register_dependencies_scripts' );

