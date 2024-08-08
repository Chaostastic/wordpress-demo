<?php
namespace Demo\Routes;
use Demo\Controller\FormController;
use WP_REST_Server;
require_once DEMO_PLUGIN_DIR . 'controllers/class-demo-form-controller.php';

class FormRoutes {

    public function __construct() {
        $controller = new FormController();

        register_rest_route('demo/v1', '/form', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($controller, 'post')
        ));
    }
}