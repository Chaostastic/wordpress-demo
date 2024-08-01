<?php
namespace Demo\Controller;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';
use WP_Error;

class OrganisationsController {
    function get($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $model = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $model->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $response_arr = array_map(function ($org_name) {
            return ['relationship_type' => 'parent', 'org_name' => $org_name];
        }, $model->get_parents($org_id));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'daughter', 'org_name' => $org_name];
        }, $model->get_children($org_id)));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'sister', 'org_name' => $org_name];
        }, $model->get_sisters($org_id)));
        array_multisort($response_arr, SORT_ASC, $response_arr);
        usort($response_arr, self::sort_by_org_name(...));
        return rest_ensure_response($response_arr);
    }

    function sort_by_org_name($a, $b): int {
        if ($a['org_name'] == $b['org_name']) return 0;
        return ($a['org_name'] < $b['org_name']) ? -1 : 1;
    }

    function delete($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $model = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $model->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $model->remove_org($org_id);
        return rest_ensure_response("Deletion Successful");
    }

    function put($request): WP_Error|\WP_REST_Response|\WP_HTTP_Response {
        $model = new OrganisationsService();
        $org_name = (string) $request['org_name'];
        $org_id = $model->get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $model->remove_relations($org_id);
        return rest_ensure_response("Edit Successful");
    }

    function post($request): void {
        $model = new OrganisationsService();
        self::add_orgs($request->get_json_params(), null, $model);
    }

    function add_orgs($arr, $parent_id, $model): void {
        $org_name = $arr['org_name'];
        $org_id = $model->get_org_id($org_name);
        if (!$org_id) {
            $org_id = $model->add_org($org_name);
        }
        if ($parent_id) {
            $model->add_relation($parent_id, $org_id);
        }
        if (array_key_exists('daughters', $arr)) {
            foreach ($arr['daughters'] as $daughter_arr) {
                self::add_orgs($daughter_arr, $org_id, $model);
            }
        }
    }
}