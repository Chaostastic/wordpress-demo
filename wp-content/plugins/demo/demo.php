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
require_once DEMO_PLUGIN_DIR . 'routes/class-demo-routes.php';

register_activation_hook(__FILE__, array(new Demo\Activator(), 'activate'));
register_deactivation_hook(__FILE__, array(new Demo\DeActivator(), 'deactivate'));
add_action('rest_api_init', array(new Demo\Routes(), 'register_routes'));

const MY_ACF_PATH = DEMO_PLUGIN_DIR . 'includes/acf/';
define( 'MY_ACF_URL', plugin_dir_url( __FILE__ ) . 'includes/acf/' );
include_once(MY_ACF_PATH . 'acf.php');
add_filter('acf/settings/url', 'my_acf_settings_url');

function my_acf_settings_url( $url ): string {
    return MY_ACF_URL;
}

if (!file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php')) {
    add_filter('acf/settings/show_admin', '__return_false');
    add_filter('acf/settings/show_updates', '__return_false', 100);
}
