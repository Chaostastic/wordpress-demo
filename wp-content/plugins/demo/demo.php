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
            register_activation_hook(__FILE__, array($this, 'activate'));
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

        function activate() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $orgs_name = $wpdb->prefix . 'organisations';
            $orgs_sql = "CREATE TABLE $orgs_name (
              id int NOT NULL AUTO_INCREMENT,
              orgname varchar(255) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            $relations_name = $wpdb->prefix . 'relations';
            $relations_sql = "CREATE TABLE $relations_name (
              id int NOT NULL AUTO_INCREMENT,
              parent int NOT NULL,
              child  int NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($orgs_sql);
            dbDelta($relations_sql);
        }
	}

	new DemoPlugin();
}