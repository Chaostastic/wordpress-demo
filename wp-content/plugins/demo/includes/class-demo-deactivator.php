<?php
namespace Demo;

class DeActivator {
    static function de_activate() {
        global $wpdb;
        $orgs_name = $wpdb->prefix . 'demo_organisations';
        $relations_name = $wpdb->prefix . 'demo_relations';
        $wpdb->query("DROP TABLE $orgs_name");
        $wpdb->query("DROP TABLE $relations_name");
    }
}