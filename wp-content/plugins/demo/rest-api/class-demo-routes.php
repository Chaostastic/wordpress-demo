<?php
namespace Demo\REST_API;
use WP_REST_Server;

class Routes {
    static function register_routes() {
        register_rest_route('demo/v1', '/organisations', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array('Demo\REST_API\Organisations', 'post_orgs'),
        ));
    }
}