<?php
namespace Demo\Controller;
use WP_Error;

class FormController {
    public function post($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        return rest_ensure_response("Submit Successful");
    }
}