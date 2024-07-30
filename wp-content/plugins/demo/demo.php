<?php

/*
 * Plugin Name: demo
 */

if (!defined('ABSPATH')) {
	exit;
}

class DemoPlugin {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-demo-activator.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-demo-deactivator.php';
        register_activation_hook(__FILE__, array('Demo\Activator', 'activate'));
        register_deactivation_hook(__FILE__, array('Demo\DeActivator', 'de_activate'));
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    function post_orgs($request) {
        $this->add_orgs($request->get_json_params());
    }

    function add_orgs($parent) {
        global $wpdb;
        $parent_name = $parent['org_name'];
        $wpdb->insert($wpdb->prefix . 'demo_organisations', array('orgname' => $parent_name));
        if (array_key_exists('daughters', $parent)) {
            foreach ($parent['daughters'] as $daughter) {
                $daughter_name = $daughter['org_name'];
                $wpdb->insert($wpdb->prefix . 'demo_relations', array('parent' => $parent_name, 'child' => $daughter_name));
                $this->add_orgs($daughter);
            }
        }
    }

    function register_routes() {
        register_rest_route('demo/v1', '/organisations', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'post_orgs'),
        ));
    }
}

new DemoPlugin();