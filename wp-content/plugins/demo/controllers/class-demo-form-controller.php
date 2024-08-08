<?php
namespace Demo\Controller;
use Demo\Service\FormsService;
use WP_Error;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-forms-service.php';

class FormController {
    public function post($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new FormsService();
        $form = array(
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
        );
        $service->addFrom($form);
        return rest_ensure_response("Submit Successful");
    }
}