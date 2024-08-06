<?php
namespace Demo\Controller;
use Demo\Model\Organisation;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'models/class-demo-organisation.php';
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';
require_once DEMO_PLUGIN_DIR . 'controllers/class-demo-organisation-request-validator.php';
use WP_Error;

class OrganisationsController {
    public function get($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new OrganisationsService();
        $org = new Organisation($request['org_name'], $service);
        $validator = new OrganisationRequestValidator();
        $validator->validateAPIKey($request);
        $validator->orgExists($org);
        if ($validator->getError()->has_errors()) {
            return $validator->getError();
        }
        $response_arr = array_map(function ($org_name) {
            return ['relationship_type' => 'parent', 'org_name' => $org_name];
        }, $service->getParents($org->getOrgId()));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'daughter', 'org_name' => $org_name];
        }, $service->getChildren($org->getOrgId())));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'sister', 'org_name' => $org_name];
        }, $service->getSisters($org->getOrgId())));
        usort($response_arr, array($this, 'sortByOrgName'));
        $page = $request->get_param('page');
        if (!$page) {
            $page = 1;
        }
        $per_page = $request->get_param('limit');
        if (!$per_page || $per_page > 100) {
            $per_page = 100;
        }
        $start_index = ($page - 1) * $per_page;
        return rest_ensure_response(array_slice($response_arr, $start_index, $per_page));
    }

    private function sortByOrgName($a, $b): int {
        if ($a['org_name'] == $b['org_name']) return 0;
        return ($a['org_name'] < $b['org_name']) ? -1 : 1;
    }

    public function delete($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new OrganisationsService();
        $org = new Organisation($request['org_name'], $service);
        $validator = new OrganisationRequestValidator();
        $validator->validateAPIKey($request);
        $validator->orgExists($org);
        if ($validator->getError()->has_errors()) {
            return $validator->getError();
        }
        $service->removeOrg($org->getOrgId());
        return rest_ensure_response("Deletion Successful");
    }

    public function put($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $arr = $request->get_json_params();
        $service = new OrganisationsService();
        $org = new Organisation($request['org_name'], $service);
        $validator = new OrganisationRequestValidator();
        $validator->validateAPIKey($request);
        $validator->orgExists($org);
        $validator->hasKey($arr, 'daughters');
        $validator->hasKey($arr, 'parents');
        if ($validator->getError()->has_errors()) {
            return $validator->getError();
        }
        $service->removeRelations($org->getOrgId());
        foreach ($arr['daughters'] as $daughter) {
            $daughter_id = $service->getOrgId($daughter);
            if ($daughter_id) {
                $service->addRelation($org->getOrgId(), $daughter_id);
            }
        }
        foreach ($arr['parents'] as $parent) {
            $parent_id = $service->getOrgId($parent);
            if ($parent_id) {
                $service->addRelation($parent_id, $org->getOrgId());
            }
        }
        return rest_ensure_response("Edit Successful");
    }

    public function post($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $arr = $request->get_json_params();
        $validator = new OrganisationRequestValidator();
        $validator->validateAPIKey($request);
        $validator->validateJSON($request);
        if ($validator->getError()->has_errors()) {
            return $validator->getError();
        }
        $service = new OrganisationsService();
        $this->addOrgs($arr, null, $service);
        return rest_ensure_response("Add Successful");
    }

    private function addOrgs($arr, $parent, $service): void {
        $org = new Organisation($arr['org_name'], $service);
        if (!$org->getOrgId()) {
            $org->setOrgId($service->addOrg($org->getOrgName()));
        }
        if ($parent) {
            $service->addRelation($parent->getOrgId(), $org->getOrgId());
        }
        if (array_key_exists('daughters', $arr)) {
            foreach ($arr['daughters'] as $daughter_arr) {
                $this->addOrgs($daughter_arr, $org, $service);
            }
        }
    }
}