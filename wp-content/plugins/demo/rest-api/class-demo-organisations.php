<?php

namespace Demo\REST_API;

class Organisations {
    static function post_orgs($request) {
        Organisations::add_orgs($request->get_json_params());
    }

    static function add_orgs($parent) {
        global $wpdb;
        $parent_name = $parent['org_name'];
        $wpdb->insert($wpdb->prefix . 'demo_organisations', array('orgname' => $parent_name));
        if (array_key_exists('daughters', $parent)) {
            foreach ($parent['daughters'] as $daughter) {
                $daughter_name = $daughter['org_name'];
                $wpdb->insert($wpdb->prefix . 'demo_relations', array('parent' => $parent_name, 'child' => $daughter_name));
                Organisations::add_orgs($daughter);
            }
        }
    }
}