<?php

/*
 * Plugin Name: demo
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DemoPlugin')) {
	class DemoPlugin {
        public function __construct() {
            add_action('rest_api_init', array($this, 'register_routes'));
        }

        function hello_world() {
            return rest_ensure_response('Hello World!');
        }

        function register_routes() {
            register_rest_route('hello-world/v1', '/hello', array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array($this, 'hello_world'),
            ));
        }
	}

	new DemoPlugin();
}