<?php
namespace Demo;
use Demo\Controller\OrganisationsController;
require_once DEMO_PLUGIN_DIR . 'controllers/class-demo-organisations-controller.php';
use WP_REST_Server;

class Routes {
    public function registerRoutes(): void {
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