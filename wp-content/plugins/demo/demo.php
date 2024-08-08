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
require_once DEMO_PLUGIN_DIR . 'includes/class-demo-api-key-setting.php';
require_once DEMO_PLUGIN_DIR . 'includes/class-demo-form-page.php';
require_once DEMO_PLUGIN_DIR . 'routes/class-demo-routes.php';

register_activation_hook(__FILE__, array(new Demo\Activator(), 'activate'));
register_deactivation_hook(__FILE__, array(new Demo\DeActivator(), 'deactivate'));
add_action('rest_api_init', array(new Demo\Routes(), 'registerRoutes'));
add_action( 'admin_init', array(new Demo\APIKeySetting(), 'init'));
add_action('wp', array(new Demo\FormPage(), 'load'));