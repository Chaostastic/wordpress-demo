<?php
namespace Demo\Controller;
use WP_Error;

class OrganisationRequestValidator {
    private WP_Error $error;

    public function __construct() {
        $this->error = new WP_Error();
    }

    public function validateAPIKey($request): void {
        if ($request->get_param('api_key') != get_option('demo_api_key')) {
            $this->error->add('invalid_api_key', esc_html__('API Key is incorrect'), array('status' => 403));
        }
    }

    public function orgExists($org): void {
        if (!$org->getOrgId()) {
            $this->error->add( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
    }

    public function hasKey($arr, $key): void {
        if (!array_key_exists($key, $arr)) {
            $this->error->add( 'rest_invalid_json', esc_html__( 'Missing required field: ' . $key), array( 'status' => 400 ));
        }
    }

    public function validateJSON($arr): void {
        $this->hasKey($arr, 'org_name');
        if (array_key_exists('daughters', $arr)) {
            foreach ($arr['daughters'] as $daughter_arr) {
                $this::validateJSON($daughter_arr);
            }
        }
    }

    public function getError(): WP_Error {
        return $this->error;
    }
}