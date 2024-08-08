<?php
namespace Demo\Routes;
use Demo\Controller\OrganisationsController;
use WP_REST_Server;
require_once DEMO_PLUGIN_DIR . 'controllers/class-demo-organisations-controller.php';

class OrganisationsRoutes {
    public function __construct() {
        $controller = new OrganisationsController();

        register_rest_route('demo/v1', '/organisations', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($controller, 'post')
        ));
        register_rest_route('demo/v1', '/organisations/(?P<org_name>.+)', array(
            array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array($controller, 'get')
            ),
            array(
                'methods'  => WP_REST_Server::DELETABLE,
                'callback' => array($controller, 'delete')
            ),
            array(
                'methods'  => 'PUT',
                'callback' => array($controller, 'put')
            )
        ));
    }
}