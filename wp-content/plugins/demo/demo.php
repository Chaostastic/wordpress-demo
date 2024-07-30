<?php

/*
 * Plugin Name: demo
 */

if (!defined('ABSPATH')) {
	exit;
}

define( 'DEMO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once DEMO_PLUGIN_DIR . 'includes/class-demo-activator.php';
require_once DEMO_PLUGIN_DIR . 'includes/class-demo-deactivator.php';
require_once DEMO_PLUGIN_DIR . 'rest-api/class-demo-routes.php';

register_activation_hook(__FILE__, array('Demo\Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Demo\DeActivator', 'de_activate'));
add_action('rest_api_init', array('Demo\REST_API\Routes', 'register_routes'));
