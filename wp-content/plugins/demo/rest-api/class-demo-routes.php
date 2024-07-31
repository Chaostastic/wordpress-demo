<?php
namespace Demo\REST_API;
use WP_REST_Server;

class Routes {
    static function register_routes() {
        require_once plugin_dir_path( __FILE__ ) . 'class-demo-organisations.php';

        register_rest_route('demo/v1', '/organisations', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array('Demo\REST_API\Organisations', 'post')
        ));
        register_rest_route('demo/v1', '/organisations/(?P<org_name>.+)', array(
            array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array('Demo\REST_API\Organisations', 'get')
            ),
            array(
                'methods'  => WP_REST_Server::DELETABLE,
                'callback' => array('Demo\REST_API\Organisations', 'delete')
            )
        ));
    }
}