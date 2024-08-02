<?php
namespace Demo\Controller;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';
use WP_Error;

class OrganisationsController {
    function get($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $service->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $response_arr = array_map(function ($org_name) {
            return ['relationship_type' => 'parent', 'org_name' => $org_name];
        }, $service->get_parents($org_id));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'daughter', 'org_name' => $org_name];
        }, $service->get_children($org_id)));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'sister', 'org_name' => $org_name];
        }, $service->get_sisters($org_id)));
        usort($response_arr, self::sort_by_org_name(...));
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

    function sort_by_org_name($a, $b): int {
        if ($a['org_name'] == $b['org_name']) return 0;
        return ($a['org_name'] < $b['org_name']) ? -1 : 1;
    }

    function delete($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $service->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $service->remove_org($org_id);
        return rest_ensure_response("Deletion Successful");
    }

    function put($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $service = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $service->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $service->remove_relations($org_id);
        $arr = $request->get_json_params();
        foreach ($arr['daughters'] as $daughter) {
            $daughter_id = $service->get_org_id($daughter);
            if ($daughter_id) {
                $service->add_relation($org_id, $daughter_id);
            }
        }
        foreach ($arr['parents'] as $parent) {
            $parent_id = $service->get_org_id($parent);
            if ($parent_id) {
                $service->add_relation($parent_id, $org_id);
            }
        }
        return rest_ensure_response("Edit Successful");
    }

    function post($request): void {
        $service = new OrganisationsService();
        self::add_orgs($request->get_json_params(), null, $service);
    }

    function add_orgs($arr, $parent_id, $service): void {
        $org_name = $arr['org_name'];
        $org_id = $service->get_org_id($org_name);
        if (!$org_id) {
            $org_id = $service->add_org($org_name);
        }
        if ($parent_id) {
            $service->add_relation($parent_id, $org_id);
        }
        if (array_key_exists('daughters', $arr)) {
            foreach ($arr['daughters'] as $daughter_arr) {
                self::add_orgs($daughter_arr, $org_id, $service);
            }
        }
    }
}