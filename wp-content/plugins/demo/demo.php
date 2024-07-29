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

        function post_orgs($request) {
            $this->add_orgs($request->get_json_params());
        }

        function add_orgs($parent) {
            global $wpdb;
            $parent_name = $parent['org_name'];
            $wpdb->insert($wpdb->prefix . 'organisations', array('orgname' => $parent_name));
            if (array_key_exists('daughters', $parent)) {
                foreach ($parent['daughters'] as $daughter) {
                    $daughter_name = $daughter['org_name'];
                    $wpdb->insert($wpdb->prefix . 'relations', array('parent' => $parent_name, 'child' => $daughter_name));
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

        function activate() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $orgs_name = $wpdb->prefix . 'organisations';
            $orgs_sql = "CREATE TABLE $orgs_name (
              id int NOT NULL AUTO_INCREMENT,
              orgname varchar(255) NOT NULL,
              PRIMARY KEY  (orgname)
            ) $charset_collate;";

            $relations_name = $wpdb->prefix . 'relations';
            $relations_sql = "CREATE TABLE $relations_name (
              id int NOT NULL AUTO_INCREMENT,
              parent varchar(255) NOT NULL,
              child  varchar(255) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($orgs_sql);
            dbDelta($relations_sql);
        }
	}

	new DemoPlugin();
}