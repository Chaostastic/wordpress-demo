<?php
namespace Demo\REST_API;
use WP_Error;

class Organisations {
    static function get($request) {
        $org_name = (string) $request['org_name'];
        $org_id = self::get_org_id($org_name);
        if (!$org_id) {
            return new WP_Error( 'organisation_not_found', esc_html__( 'This organisation does not exist.'), array( 'status' => 404 ));
        }
        $response_arr = array_map(function ($org_name) {
            return ['relationship_type' => 'parent', 'org_name' => $org_name];
        }, self::get_parents($org_id));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'daughter', 'org_name' => $org_name];
        }, self::get_children($org_id)));
        array_push($response_arr, ...array_map(function ($org_name) {
            return ['relationship_type' => 'sister', 'org_name' => $org_name];
        }, self::get_sisters($org_id)));
        array_multisort($response_arr, SORT_ASC, $response_arr);
        usort($response_arr, self::sort_by_org_name(...));
        return rest_ensure_response($response_arr);
    }

    static function post($request) {
        self::add_orgs($request->get_json_params(), null);
    }

    static function add_orgs($arr, $parent_id) {
        global $wpdb;
        $org_name = $arr['org_name'];
        $org_id = self::get_org_id($org_name);
        if (!$org_id) {
            $wpdb->insert($wpdb->prefix . 'demo_organisations', array('orgname' => $org_name));
            $org_id = $wpdb->insert_id;
        }
        if ($parent_id) {
            $wpdb->insert($wpdb->prefix . 'demo_relations', array('parent' => $parent_id, 'child' => $org_id));
        }
        if (array_key_exists('daughters', $arr)) {
            foreach ($arr['daughters'] as $daughter_arr) {
                self::add_orgs($daughter_arr, $org_id);
            }
        }
    }

    static function get_org_id($org_name) {
        global $wpdb;
        $orgs_table = $wpdb->prefix . 'demo_organisations';
        return $wpdb->get_var("SELECT id FROM $orgs_table WHERE orgname = '$org_name'");
    }

    static function sort_by_org_name($a, $b) {
        if ($a['org_name'] == $b['org_name']) return 0;
        return ($a['org_name'] < $b['org_name']) ? -1 : 1;
    }

    static function get_parents($org_id) {
        global $wpdb;
        $orgs_table = $wpdb->prefix . 'demo_organisations';
        $relations_table = $wpdb->prefix . 'demo_relations';
        return $wpdb->get_col("
            SELECT $orgs_table.orgname
            FROM $relations_table
            INNER JOIN $orgs_table ON $relations_table.parent=$orgs_table.id
            WHERE child = '$org_id'
        ");
    }

    static function get_children($org_id) {
        global $wpdb;
        $orgs_table = $wpdb->prefix . 'demo_organisations';
        $relations_table = $wpdb->prefix . 'demo_relations';
        return $wpdb->get_col("
            SELECT $orgs_table.orgname
            FROM $relations_table
            INNER JOIN $orgs_table ON $relations_table.child=$orgs_table.id
            WHERE parent = '$org_id'
        ");
    }

    static function get_sisters($org_id) {
        global $wpdb;
        $orgs_table = $wpdb->prefix . 'demo_organisations';
        $relations_table = $wpdb->prefix . 'demo_relations';
        return $wpdb->get_col("
            SELECT DISTINCT $orgs_table.orgname
            FROM $relations_table
            INNER JOIN $orgs_table ON $relations_table.child=$orgs_table.id
            WHERE parent IN (SELECT parent FROM $relations_table WHERE child = '$org_id') AND NOT child = '$org_id'; 
        ");
    }
}