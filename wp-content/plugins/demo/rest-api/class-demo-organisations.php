<?php
namespace Demo\REST_API;
use WP_Error;

class Organisations {
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
}